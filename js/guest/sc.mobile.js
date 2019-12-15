/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var mobile = {
	hideNavs: function()
	{
		$('body').removeClass('opened');
		$('#filter-form, html').removeClass('filter-opened');
	},

	setFilterHeight: function()
	{
		var wh = $(window).outerHeight();
		var bh = $('.filter-bar').outerHeight();

		$('#mobile-filter').css({
			'height' : (wh - bh) + 'px'
		});
	}
}

$(document).ready(function(e)
{
	if (vars.mobile)
	{
		// Toggle open/close menu
		$(document).on('click', '#toggle-nav', function(e)
		{
			$('body').toggleClass('opened');
	
			e.preventDefault();
		});
	
		// Toggle open/close filter
		$(document).on('click', '#toggle-filter', function(e)
		{
			$('#filter-form, html').toggleClass('filter-opened');
	
			e.preventDefault();
		});
	
		// Event to close all open navs
		$(document).on('click', '.close-navs', function(e)
		{
			mobile.hideNavs();
			e.preventDefault();
		});
	
		// Automatically set the filter height
		mobile.setFilterHeight();
	}
});

