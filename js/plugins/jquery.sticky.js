/*
jQuery Sticky - v1.1
Copyright (c) 2013 Robert Holden
Dual licensed under the MIT license and GPL license.
*/








// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

		// undefined is used here as the undefined global variable in ECMAScript 3 is
		// mutable (ie. it can be changed by someone else). undefined isn't really being
		// passed in so we can ensure the value of it is truly undefined. In ES5, undefined
		// can no longer be modified.

		// window and document are passed through as local variable rather than global
		// as this (slightly) quickens the resolution process and can be more efficiently
		// minified (especially when both are regularly referenced in your plugin).

		// Create the defaults once
		var pluginName = "sticky",
			defaults = {
				stickOn: 0,
				offset: 0,
				className: 'sticky', 
				emptyClass: false,
				animate: '',
				animateSpeed: 250
		};

		// The actual plugin constructor
		function Plugin ( element, options ) {
				this.element = element;
				// jQuery has an extend method which merges the contents of two or
				// more objects, storing the result in the first object. The first object
				// is generally empty as we don't want to alter the default options for
				// future instances of the plugin
				this.settings = $.extend( {}, defaults, options );
				this._defaults = defaults;
				this._name = pluginName;
				this.init();
				this.createStick();
		}

		Plugin.prototype = {
				init: function () {
					// Place initialization logic here
					// You already have access to the DOM element and
					// the options via the instance, e.g. this.element
					// and this.settings
					// you can add more functions like the one below and
					// call them like so: this.yourOtherFunction(this.element, this.settings).												
					
					//console.log("xD");
				},
				createStick: function () {
					
					var mobile = this.settings.mobile;									
					var id = this.element;
					var stickOn = this.settings.stickOn;
					var offset = this.settings.offset
					var className = this.settings.className;					
					var emptyClass = this.settings.emptyClass;
					var animate = this.settings.animate;		
					var speed = this.settings.animateSpeed;
					var created = false;
					var diff = ($(id).offset().top - offset) - window.pageYOffset;					
					var name = $(id).attr('id');												
					var mobileSize = this.settings.mobileSize;	
					var ccc = 0;
													
					$(document).scroll(function(e) {
						getStuck(mobileSize, emptyClass);
					});
					$(window).resize(function(e) {
						getStuck(mobileSize, emptyClass);
					});
					
					function getStuck(mobileSize, classEmpty) {						
						
						// Calculate offset distance
						if($('#'+ name +'-sticky-replace').length > 0){
							diff = ($('#'+ name +'-sticky-replace').offset().top - stickOn) - window.pageYOffset;
						}	else {
							diff = ($(id).offset().top - stickOn) - window.pageYOffset;
						}
						
						if(diff < 0 ) {	
							if(!created) {																
								$(id).before('<div id="'+ name + '-sticky-replace"></div>');								
								
								var position = $(id).css('position');
								var display = $(id).css('display');
								var margin = $(id).css('margin');
								var padding = $(id).css('padding');
								var left = $(id).css('left');
								var right = $(id).css('right');
								var top = $(id).css('top');
								var bottom = $(id).css('bottom');
								var floated = $(id).css('float');
								var height = $(id).innerHeight();
								var width = $(id).innerWidth();		
								
								if (floated != 'none')
									width -= 1;
								
								$('#'+ name +'-sticky-replace').css({
										'width':width+'px',
										'height':height+'px',
										'position':position,				
										'display': display,
										'top':top,			
										'bottom':bottom,	
										'right':right,	
										'left':left,								
										'float': floated,
										'margin':margin,
										'padding':padding
									});
									
								if(emptyClass == false){
									$(id).wrap('<div id="'+ name + '-sticky-container"></div>');
									ccc++;
								}
								 
								$(id).addClass(className);
								
								if(emptyClass == false){									
									$('#'+name + '-sticky-container').css({
										'position':'fixed',
										'display' : 'none',
										'width':(width == $(window).width()) ? '100%' : (width+'px'),
										'top': -height+'px',
										'height': height+'px',
										'left': $('#'+ name +'-sticky-replace').offset().left,
										'margin': 0,
										'padding':0,
										'z-index': '995'
									});
									
									switch (animate) {
										case 'slide':
											
											// Slide Top
											$('#'+name + '-sticky-container').show();
											$('#'+ name +'-sticky-container').animate({
												'top': offset+'px'
											}, speed);
											
											break;
										case 'fade':
											
											// Fadeout
											$('#'+ name + '-sticky-container').css('top', offset+'px');
											$('#'+ name +'-sticky-container').fadeIn(speed);
											
											break;
										
										default:
											$('#' + name + '-sticky-container').show();
											$('#' + name + '-sticky-container').css('top', offset+'px');
											break;
									}
									
									$(id).css({'top':0});
									$(id).css({'margin-top':0});
									
									created = true;
								}
							}
						
						} else {
							if(created && diff >= 0) {		
								$('#'+ name +'-sticky-replace').remove();
								$(id).removeClass(className);
								$(id).removeAttr('style');
								
								if(emptyClass == false){										
									//$('#'+ name +'-sticky-container').removeAttr('style');
									$(id).unwrap('<div id="'+ name + '-sticky-container"></div>');
								}  
						
								created = false;
							}
						}
						
						//document.getElementById('offset').innerHTML = diff;
					}
					
				}
		};

		// A really lightweight plugin wrapper around the constructor,
		// preventing against multiple instantiations
		$.fn[ pluginName ] = function ( options ) {
				return this.each(function() {
						if ( !$.data( this, "plugin_" + pluginName ) ) {
								$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
						}
				});
		};

})( jQuery, window, document );
