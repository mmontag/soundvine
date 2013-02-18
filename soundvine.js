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

var SndVine = {
	siteRoot: 'soundvine.co'
};

SndVine.init = function() {
	this.$video = $('#video');
	this.$audio = $('#audio');
	this.$video.get(0).volume = 0;
	var self = this;

	this.getRecent();
	if (typeof _requestedItem != 'undefined') {
		this.load(_requestedItem);
	}

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
	$('.dismiss').on('click', function() {
		$('#status').hide();
	});
	$('.mini a').on('click', function(event) {
		$('body').removeClass('viewer');
		event.preventDefault();
	});
	$("#alias").keyup(function(event){
		var str = $("#alias").val();
		if( str != "" ) {
			var regx = /^[A-Za-z0-9\-]+$/;
			if (!regx.test(str)) {
				SndVine.statusMessage("Only alphanumeric and dash (-) characters are allowed in the path.", "error");

			} else {
				$('#status').hide();
			}
		}
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
				SndVine.statusMessage("Oops, trouble extracting a video from the Vine link you provided." +
						"Vine links that start with <strong>t.co</strong> and <strong>vine.co</strong> should work.", "error");
				console.log("error getting vine data.");
			}
		});
	} else {
		k(data);
	}
};

SndVine.statusMessage = function(message, type) {
	type = type || "notice";
	$('#status').show().removeClass().addClass(type);
	$('#status .message').html(message);
};

SndVine.save = function() {
	// TODO: remove refs to SndVine and use 'this'
	this.getFormData(function(formData) {
		$.ajax({
			url: "createSoundvine.php",
			type: "POST",
			dataType: "json",
			data: formData,
			success: function(data) {
				var url = "http://" + SndVine.siteRoot + "/" + SndVine.getUrlFromData(data);
				SndVine.statusMessage("Your soundvine has been saved here: <strong><a href='"+url+"'>"+url+"</a></strong>", "success");
				console.log("saved:", data);
			},
			error: function(data) {
				SndVine.statusMessage("Oops, there was an error creating your Soundvine link. ", "error");
				console.log("error saving:", data);
			}
		});
	});
};

SndVine.getUrlFromData = function(data) {
	return data.url || data.alias || data.id;
}

SndVine.getRecent = function() {
	var $recent = $('.recent');
	var ul = $('<ul></ul>');
	$.ajax({
		url: "getRecent.php",
		dataType: "json",
		success: function(data) {
			for (var i = 0, length = data.length; i < length; i++) {
				var url = SndVine.siteRoot + '/' + SndVine.getUrlFromData(data[i]);
				var li = $('<li><a href="http://' + url +'">' + url + '</a></li>');
				ul.append(li);
			}
			$recent.find('ul').replaceWith(ul);
			$recent.show();
		},
		error: function() {
			// Just hide the recent thing...
			$recent.hide();
		}
	});
};