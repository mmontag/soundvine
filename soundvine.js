/**
 * Some Vine URLs:
 * http://t.co/9uMSwivn
 * http://t.co/DoB26eFI
 * http://t.co/f27oOWp2
 *
 * ...Redirects to:
 * http://vine.co/v/brMraJq2aW3
 * http://vine.co/v/brAeHaWb9Hx
 * http://vine.co/v/brMuWZgrxbK
 *
 * ...which gives HTML containing:
 * https://vines.s3.amazonaws.com/videos/ABE34DE5-4A2F-4F09-B2A4-27462C45522A-16411-0000043947E734CD_1.0.1.mp4?versionId=7B2rb7ro7Ml8NrbiP9P8SJ3oLBvHcqtY
 *
 */

$(document).ready(function() {
	SndVine.init();
});

var SndVine = {};

SndVine.init = function() {
	this.$video = $('#video');
	this.$audio = $('#audio');
	this.$video.get(0).volume = 0;
	var self = this;
	$('#video, .video_border').on('click', function(event) {
		var video = $('#video').get(0);
		if (video.paused) {
			video.play();
		} else {
			video.pause();
		}
	});
	this.$video.on('pause', function(event) {
		self.$audio.get(0).pause();
	});
	this.$video.on('play', function(event) {
		self.$audio.get(0).play();
	});

	$('#preview').on('click', function() {
		self.getFormData(self.load);
	});
	$('#submit').on('click', function() {
		self.save();
	});
	$('#vine_url').on('change', function() {
		// Clear the hidden video_url so that stale values aren't used.
		$('#video_url').val('');
	});
};

/**
 * Set up video and audio with given options.
 * @param options An object with options for video/audio playback:
 * 	- video_url
 * 	- video_loop
 * 	-
 * 	- audio_url
 * 	-
 */
SndVine.load = function(data) {
	$('.player').css('display','inline-block');

	this.$video.get(0).src = data.video_url;
	this.$video.get(0).autoplay = true;
	this.$video.get(0).playbackRate = data.video_speed;
	this.$audio.get(0).src = data.audio_url;

	console.log("loaded data.");
}.bind(SndVine);

/**
 * Fetches the Vine video URL if necessary, then calls
 * continuation with populated data.
 * @param k Function
 */
SndVine.getFormData = function(k) {
	k = k || function() {};
	var data = {
		alias: $('#alias').val(),
		video_url: $('#video_url').val(),
		audio_url: $('#audio_url').val(),
		video_speed: $('#video_speed').val()
	}
	if (!data.video_url) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "getVine.php?vine_url=" + $('#vine_url').val(),
			success: function(result) {
				console.log("got a vine url:", result.video_url);
				data.video_url = result.video_url;
				k(data);
			},
			error: function(result) {
				SndVine.statusMessage("Oops, trouble extracting a video from that Vine link there.", "error");
				console.log("error getting vine data.");
			}
		});
	} else {
		k(data);
	}
};

SndVine.statusMessage = function(message, type) {
	type = type || "notice";
	$('.status').show().removeClass().addClass(type);
	$('.status').text(message);
};

SndVine.save = function() {
	this.getFormData(function(formData) {
		$.ajax({
			url: "createSoundvine.php",
			type: "POST",
			dataType: "json",
			data: formData,
			success: function(data) {
				var url = "http://soundvine.co/" + data.id;
				SndVine.statusMessage("<a href='"+url+"'>"+url+"</a>", "success");
				console.log("saved:", data);
			},
			error: function(data) {
				SndVine.statusMessage("Oops, there was an error creating your Soundvine link.", "error");
				console.log("error saving:", data);
			}
		});
	});
};