/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var functions = {
	init: function () 
	{
		this.lazyLoad();
		this.dynamicTime();
		this.resizeIconEls();
		this.widths();
	},
		
	dialog: 
	{
		confirm: function (title, body, data)
		{
			var el = $('#confirm-dialog');

			for (var i = 0; i < data.length; i++)
			{
				el.attr(data[i][0], data[i][1]);
			}

			el.find('h3').text(title);
			el.find('.modal-body').html(body);

			$('#confirm-modal').modal('show');
			setTimeout(function()
			{
				el.find('.button-primary').focus();
			}, 500);
		},

		close: function ()
		{
			$('#confirm-modal').modal('hide');
		}
	},
	
	dynamicTime: function ()
	{
		// Load time ago plugin
		if ($.isFunction($.fn.timeago))
			$('.dynamic-time').timeago();
	},
	
	guid: function () 
	{
		var nav = window.navigator;
	  var screen = window.screen;
	  var guid = nav.mimeTypes.length;
	  guid += nav.userAgent.replace(/\D+/g, '');
	  guid += nav.plugins.length;
	  guid += screen.height || '';
	  guid += screen.width || '';
	  guid += screen.pixelDepth || '';

	  return guid;
	},
	
	isInt: function (n)
	{
		return Number(n) === n && n % 1 === 0;
	},
	
	loading: function (state)
	{
		if (state)
		{
			$('#logo').addClass('is-loading');
		}
		else
		{
			$('#logo').removeClass('is-loading');
		}
	},
	
	resizeIconEls: function () 
	{
		var resizer = function () 
		{
			$('input, select').each(function(i, element){
				var el = $(element);
				var icon = (el.prev('.fa').size() == 0) ? false : el.prev('.fa');
				if (! icon)
				{
					icon = (el.next('.fa').size() == 0) ? false : el.next('.fa');
				}
				
				if (icon)
				{ 
					var pWidth = el.parent('*').width();
					var iWidth = icon.outerWidth(true);
					var sWidth = pWidth - iWidth - 2;
					
					el.css('width', sWidth);
				}
			});
		}
		
		setTimeout(resizer, 250);
		
		$(window).resize(resizer);
		
		$(document).on('focus', '#mobile-filter input', function(e) {
			setTimeout(resizer, 200);
		});
	},
	
	scrollTo: function (id, speed, callback) {
		speed = (typeof speed === 'undefined') ? 250 : parseInt(speed);
		callback = (typeof callback === 'undefined') ? function () {} : callback;

		var offset = 0;
		
		if ($('#stick-user').length > -1)
			offset = $('#stick-user').height();
		
		$('body,html').stop().animate({
			scrollTop : $(id).offset().top - 60 - offset
		}, speed, callback);
	},

	timestamp:	function () 
	{
		var dt = new Date();
		return dt.toISOString();
	},
	
	lazyLoad: function (time) 
	{
		var t = (typeof time === 'undefined') ? 0 : time;
		
		setTimeout(function()
		{
			var bLazy = new Blazy({
				selector : 'img.lazy',
				success : function(element)	{
					element.style.opacity = 1;
				}
			});
		}, t);
	},
		
	toast: function (message, state, fn)
	{
		var c = state ? 'good' : state == false ? 'bad' : '';
		var top = $('header').outerHeight();
		
		$('body').append('<div id="toast-container"><div id="toast" class="' + c + '">' + message + '</div></div>');
		$('#toast-container').css('top', 0).slideDown(100, function()
		{
			setTimeout(function()
			{
				$('#toast-container').slideUp(100, function()
				{
					$('#toast-container').remove();
					if (typeof fn !== 'undefined') 
						fn();
				});
			}, 2500);
		});
	},
	
	widths: function ()
	{
		$('*[data-width]').each(function(i, element){
			var el = $(element);
			var w = parseInt(el.data('width'));
			el.width(w);
		}); 
	},
	
	utf8_decode: function (str_data)
	{
		// discuss at: http://phpjs.org/functions/utf8_decode/
		// original by: Webtoolkit.info (http://www.webtoolkit.info/)
		// input by: Aman Gupta
		// input by: Brett Zamir (http://brett-zamir.me)
		// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: Norman "zEh" Fuchs
		// bugfixed by: hitwork
		// bugfixed by: Onno Marsman
		// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// bugfixed by: kirilloid
		// example 1: utf8_decode('Kevin van Zonneveld');
		// returns 1: 'Kevin van Zonneveld'

		var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0, c4 = 0;

		str_data += '';

		while (i < str_data.length)
		{
			c1 = str_data.charCodeAt(i);
			if (c1 <= 191)
			{
				tmp_arr[ac++] = String.fromCharCode(c1);
				i++;
			}
			else if (c1 <= 223)
			{
				c2 = str_data.charCodeAt(i + 1);
				tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else if (c1 <= 239)
			{
				// http://en.wikipedia.org/wiki/UTF-8#Codepage_layout
				c2 = str_data.charCodeAt(i + 1);
				c3 = str_data.charCodeAt(i + 2);
				tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
			else
			{
				c2 = str_data.charCodeAt(i + 1);
				c3 = str_data.charCodeAt(i + 2);
				c4 = str_data.charCodeAt(i + 3);
				c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
				c1 -= 0x10000;
				tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1 >> 10) & 0x3FF));
				tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
				i += 4;
			}
		}

		return tmp_arr.join('');
	}
}