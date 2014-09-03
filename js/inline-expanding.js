/*
 * inline-expanding.js
 * https://github.com/savetheinternet/Tinyboard/blob/master/js/inline-expanding.js
 *
 * Released under the MIT license
 * Copyright (c) 2012-2013 Michael Save <savetheinternet@tinyboard.org>
 * Copyright (c) 2013-2014 Marcin Łabanowski <marcin@6irc.net>
 *
 * Usage:
 *   // $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/inline-expanding.js';
 *
 */

onready(function(){

	$(document).on('imageexpand', '.image-expand:not(.init)', function() {
		var trigger = $(this);
		var img = $('img', trigger);
		var fullSrc = trigger.prop('href');

		if(fullSrc.match(/\.webm$/i)) {
			trigger.click(function() {
				if(trigger.hasClass('expanded')) {
					img.show();

					$('video', trigger).remove();
				}
				else {
					img.hide();

					var video = $('<video loop autoplay />');
					video.prop('src', fullSrc);
					video.appendTo(trigger);
				}

				trigger.toggleClass('expanded');
				
				return false;
			});
		}
		else {
			var src = img.prop('src');
			var width = img.width();
			var height = img.height();

			img.load(function() {
				img.css({ opacity: 1 });
			});

			trigger.click(function() {
				if(trigger.hasClass('expanded')) {
					img.prop('src', src);
					img.css({
						width: width,
						height: height
					});
				}
				else {
					img.prop('src', fullSrc);
					img.css({
						width: 'auto',
						height: 'auto'
					});

					if(img.width() === width) {
						img.css({ opacity: 0 });
					}
				}

				trigger.toggleClass('expanded');

				return false;
			});
		}

		trigger.addClass('init');

	}).find('.image-expand:not(.init)').trigger('imageexpand');

	// allow to work with auto-reload.js, etc.
	$(document).bind('new_post', function(e, post) {
		$('.image-expand:not(.init)').trigger('imageexpand');
	});


	var inline_expand_post = function() {

		return;
		var link = this.getElementsByTagName('a');

		for (var i = 0; i < link.length; i++) {
			if (typeof link[i] == "object" && link[i].childNodes && typeof link[i].childNodes[0] !== 'undefined' && link[i].childNodes[0].src && link[i].childNodes[0].className.match(/post-image/) && !link[i].className.match(/file/)) {
				link[i].childNodes[0].style.maxWidth = '95%';
				link[i].onclick = function(e) {
					if (this.childNodes[0].className == 'hidden')
						return false;
					if (e.which == 2 || e.metaKey)
						return true;
					if (!this.dataset.src) {
						this.dataset.expanded = 'true';
						this.dataset.src= this.childNodes[0].src;
						this.dataset.width = this.childNodes[0].style.width;
						this.dataset.height = this.childNodes[0].style.height;
						this.childNodes[0].src = this.href;
						this.childNodes[0].style.width = 'auto';
						this.childNodes[0].style.height = 'auto';
						this.childNodes[0].style.opacity = '0.4';
						this.childNodes[0].style.filter = 'alpha(opacity=40)';
						this.childNodes[0].onload = function() {
							this.style.opacity = '';
							delete this.style.filter;
						}
					} else {
						this.childNodes[0].src = this.dataset.src;
						this.childNodes[0].style.width = this.dataset.width;
						this.childNodes[0].style.height = this.dataset.height;
						delete this.dataset.expanded;
						delete this.dataset.src;
						delete this.childNodes[0].style.opacity;
						delete this.childNodes[0].style.filter;
					}
					return false;
				}
			}
		}
	}

});
