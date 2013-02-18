<?php

$url = $_GET['vine_url'];
$timeout = 5;

if (preg_match('/^(http|https):\/\/(vine\.co|t\.co)\//',$url) !== 1) {
  header(':', true, 400);
  echo json_encode(array("error"=>"Not recognized as a Vine URL."));
}

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
$content = curl_exec( $ch );
$response = curl_getinfo( $ch );
curl_close ( $ch );

$title_re = '<meta property="twitter:description" content="(.+)">';
$image_re = '<meta property="twitter:image" content="(.+)">';
$video_re = '<meta property="twitter:player:stream" content="(.+)">';

preg_match("#$title_re#", $content, $matches);
$title_url = $matches[1];
preg_match("#$image_re#", $content, $matches);
$image_url = $matches[1];
preg_match("#$video_re#", $content, $matches);
$video_url = $matches[1];

if (!$video_url) {
  header(':', true, 400);
  echo json_encode(array("error"=>"Unable to parse video URL from HTML."));
}

print(json_encode(array(
  'title_url' => $title_url,
  'image_url' => $image_url,
  'video_url' => $video_url,
)));