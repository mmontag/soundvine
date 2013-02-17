<?php

require(".db.config.php");

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Soundvine</title>
	  <link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
	  <link href="soundvine.css" rel="stylesheet">
	  <script src="http://vjs.zencdn.net/c/video.js"></script>
    <script src="soundvine.js"></script>
  </head>
	<body>
		<h1>Soundvine</h1>
		<input type="text" id="vinelink" placeholder="Paste a Vine link">
		<input type="text" id="audiolink" placeholder="Paste an audio link">
		<button class="btn" id="submit">Combine</button>
	</body>
</html>