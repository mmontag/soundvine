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
	  <script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-25089589-3']);
		  _gaq.push(['_setDomainName', 'soundvine.co']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

	  </script>
	</body>
</html>