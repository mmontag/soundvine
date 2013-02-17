<?php

require(".db.config.php");

$regs = array();
$regs['alias'] = "/^[a-zA-Z0-9\-]{2,30}$/";
// Must be a Vine video, for now.
$regs['video_url'] = "#^(https://vines\.s3\.amazonaws\.com/[a-zA-Z0-9_\-\./]+\.mp4\?[a-zA-Z0-9=_\.\-]+)$#";
$regs['audio_url'] = "#^(http|https)://#";

foreach ($regs as $field => $regex) {
  if (preg_match($regex, $_POST[$field]) !== 1) {
    header(':', true, 400);
    echo json_encode(array("error"=>"`$field` wasn't formatted correctly."));
    exit;
  }
}

if (isset($_POST['alias'])) {
  $alias = $mysqli->escape_string($_POST['alias']);
} else {
  $alias = "NULL";
}
$video_url = $mysqli->escape_string($_POST['video_url']);
$audio_url = $mysqli->escape_string($_POST['audio_url']);

// Optional fields

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

$query = "INSERT INTO vines (alias, video_url, audio_url, $opt_fields ip_address, time_stamp)
          VALUES ('$alias', '$video_url', '$audio_url', $opt_values '$ip_address', CURRENT_TIMESTAMP)";
$result = $mysqli->query($query);

if ($mysqli->affected_rows < 1) {
  header(':', true, 400);
  echo json_encode(array("error"=>"Database error."));
  exit;
}

$id = $mysqli->insert_id;
echo json_encode(array(
  "success" => "Record added.",
  "id" => $id,
  "alias" => $alias,
));

$mysqli->close();