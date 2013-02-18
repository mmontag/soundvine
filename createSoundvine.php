<?php

require(".db.config.php");

$regs = array();
$regs['alias'] = "/^[a-zA-Z][a-zA-Z0-9\-]{1,29}$/";
// Must be a Vine video, for now.
$regs['video_url'] = "#^(https://vines\.s3\.amazonaws\.com/[a-zA-Z0-9_\-\./]+\.mp4\?[a-zA-Z0-9=_\.\-]+)$#";
$regs['audio_url'] = "#^(http|https)://#";

foreach ($regs as $field => $regex) {
  if (preg_match($regex, $_POST[$field]) !== 1) {
    // TODO: fix up. Getting hacky here.
    if ($field == 'alias') {
      unset($_POST[$field]);
    } else {
      header(':', true, 400);
      echo json_encode(array("error"=>"`$field` wasn't formatted correctly."));
      exit;
    }
  }
}

$audio_url = $_POST['audio_url'];
$ch = curl_init($audio_url);
// Check headers of the audio URL and see if it's the right mime-type
// Not completely foolproof, but good enough
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_exec($ch);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);
if (preg_match("/audio/", $content_type) !== 1 && preg_match("/\.(mp3|ogg|wav)$/", $audio_url) !== 1) {
  header(':', true, 400);
  echo json_encode(array("error"=>"No audio detected at audio URL."));
  exit;
}

$video_url = $mysqli->escape_string($_POST['video_url']);
$audio_url = $mysqli->escape_string($audio_url);

// Optional fields
$_f = 'alias';
$alias = null;
if (isset($_POST[$_f])) {
  // Already been validated
  $alias = $mysqli->escape_string($_POST[$_f]);
  $opt_fields .= "$_f, ";
  $opt_values .= "'$alias', ";
}

$_f = 'video_speed';
if (isset($_POST[$_f])) {
  $_v = 1.0 * $_POST[$_f];
  $opt_fields .= "$_f, ";
  $opt_values .= "$_v, ";
}

$_f = 'audio_start_time';
if (isset($_POST[$_f])) {
  $_v = 1.0 * $_POST[$_f];
  $opt_fields .= "$_f, ";
  $opt_values .= "$_v, ";
}

$_f = 'audio_end_time';
if (isset($_POST[$_f])) {
  $_v = 1.0 * $_POST[$_f];
  $opt_fields .= "$_f, ";
  $opt_values .= "$_v, ";
}

$ip_address = $mysqli->escape_string($_SERVER['REMOTE_HOST']);

$query = "INSERT INTO vines (video_url, audio_url, $opt_fields ip_address, time_stamp)
          VALUES ('$video_url', '$audio_url', $opt_values '$ip_address', CURRENT_TIMESTAMP)";
$result = $mysqli->query($query);

if ($mysqli->affected_rows < 1) {
  header(':', true, 400);
  echo json_encode(array("error"=>"Database error."));
  exit;
}

$id = $mysqli->insert_id;
echo json_encode(array(
  "success" => "Record added.",
  "alias" => $alias,
  "id" => $id
));

$mysqli->close();