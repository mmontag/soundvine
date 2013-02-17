<?php

require(".db.config.php");

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
		<!--		<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">-->
		<!--		<script src="http://vjs.zencdn.net/c/video.js"></script>--><!--		<script>-->
		<!--			_V_.options.flash.swf = "video-js.swf";--><!--			_V_.options.techOrder = ['html5', 'flash'];-->
		<!--			_V_.ControlBar.prototype.options.components = {'playToggle':{}}--><!--		</script>-->
	</head>
	<body>
		<h1>soundvine</h1>

		<div class="big_input">
			<label for="vine_url">Vine:</label>
			<input type="text" id="vine_url" placeholder="Paste a Vine link" value="http://t.co/DoB26eFI">
			<input type="hidden" id="video_url">
			<input type="hidden" id="alias" value="none">
		</div>
		<div class="big_input">
			<label for="audio_url">Audio:</label>
			<input type="text" id="audio_url" placeholder="Paste an audio link"
				   value="http://mattmontag.com/music/Audible Automan.mp3">
		</div>
		<div>
			<label for="video_speed">Playback speed:</label>
			<select id="video_speed">
				<option>0.25
				<option>0.5
				<option selected>1.0
				<option>1.5
			</select>
			<button type="button" class="btn" id="preview">Preview</button>
			<button type="button" class="btn" id="submit">Combine</button>
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

      </div>
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