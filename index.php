<?php

require(".db.config.php");

$id_re = "/^[0-9]{1,10}$/";
$alias_re = "/^[\w\d\-]{1,30}$/";
$_requestedItem = "";
if (isset($_GET['sv'])) {
  $q = $_GET['sv'];
  if (preg_match($id_re, $q) === 1) {
    $query = "SELECT * FROM vines WHERE id = '$q' LIMIT 1";
  } else if (preg_match($alias_re, $q) === 1) {
    $query = "SELECT * FROM vines WHERE alias = '$q' LIMIT 1";
  }
  $result = $mysqli->query($query);
  if ($result->num_rows > 0) {
    $row = $result->fetch_object();
    $_requestedItem = "<script>var _requestedItem = " . json_encode($row) . ";</script>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Soundvine</title>
		<meta property="fb:admins" content="16903206"/>
		<link href="http://fonts.googleapis.com/css?family=Fredoka+One" rel="stylesheet" type="text/css">
		<link href="soundvine.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="soundvine.js"></script>
      <?=$_requestedItem?>
		<!--    Disabled video.js until there's more time to tweak it:-->
		<!--		<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">-->
		<!--		<script src="http://vjs.zencdn.net/c/video.js"></script>--><!--		<script>-->
		<!--			_V_.options.flash.swf = "video-js.swf";--><!--			_V_.options.techOrder = ['html5', 'flash'];-->
		<!--			_V_.ControlBar.prototype.options.components = {'playToggle':{}}--><!--		</script>-->
	</head>
	<body<?=$_requestedItem ? ' class="viewer"' : ''?>>
		<h1><a href="http://soundvine.co">soundvine</a></h1>

		<div class="mini">
			<a href="http://soundvine.co">Make your own</a>
		</div>
		<div class="masthead">
			<div class="input_row big_input">
				<label for="vine_url">Vine:</label>
				<input type="text" id="vine_url" placeholder="Paste a Vine link" value="">
				<input type="hidden" id="video_url">
			</div>
			<div class="input_row big_input">
				<label for="audio_url">Audio:</label>
				<input type="text" id="audio_url" placeholder="Paste an MP3 link"
					   value="">
			</div>
			<div class="input_row advanced">
				<label for="alias">soundvine.co/</label>
				<input type="text" id="alias" placeholder="some-name" maxlength="30">
				<label for="video_speed">Video playback speed:</label>
				<select id="video_speed">
					<option>0.25
					<option>0.5
					<option selected>1.0
					<option>1.5
				</select>
			</div>
			<div class="input_row">
				<button type="button" class="btn" id="preview">Preview</button>
				<button type="button" class="btn" id="submit">Create</button>
			</div>
		</div>
		<div id="status">
			<span class="message"></span> <span class="dismiss"></span>
		</div>
		<div class="player">
			<video id="video"
				   data-setup="{}"
				   class="video-js vjs-default-skin"
				   preload="none"
				   loop=""
				   width="400"
				   height="400"
				   poster="">
				<source src="" type="video/mp4">
			</video>
			<audio id="audio" src="" loop="true"></audio>
			<div class="video_border"></div>
		</div>
		<div class="recent">
			<h2>Recent:</h2>
			<ul class="links"></ul>
		</div>
		<div class="footer">
			Right now soundvine only supports Chrome and Safari. 
		</div>
		<script>if ((/webkit/i).test(navigator.userAgent)) { $('.footer').hide() }</script>
		<script type="text/javascript">

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-25089589-3']);
			_gaq.push(['_setDomainName', 'soundvine.co']);
			_gaq.push(['_trackPageview']);

			(function () {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();

		</script>
	</body>
</html>