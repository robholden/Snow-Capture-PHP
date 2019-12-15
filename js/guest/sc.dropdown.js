/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
$(document).ready(function() {
	$(document).on('click', '*[data-dropdown]', function(e)	{
		var el = $($(this).data('dropdown'));
		var enabled = $(this).hasClass('enabled') ? true : false;
		
		functions.resizeIconEls();
		el.show();
		dropdown.closeAll(el);
		
		if (! enabled)
		{
			$(this).addClass('enabled');
			el.addClass('enabled');
		}

		$(this).tooltip('hide');
		$(this).blur();
		e.preventDefault();
	});
	
	$(document).on('click', 'body', function(e)
	{
		if (($(e.target).hasClass('.enabled') === false) && ($(e.target).closest('.enabled').length == 0))
		{
			dropdown.closeAll();
		}		
	});
});

var dropdown = {
	closeAll: function (el) 
	{
		var vis = el ? el.is(':visible') : false;
		$('*[data-dropdown]').removeClass('enabled');
		$('.sc-dropdown').removeClass('enabled');
		$('.sc-dropdown').hide();
		
		if (vis && el)
			el.show();
	},
}