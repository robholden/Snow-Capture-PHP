/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
$(document).ready(function() {
	
	$(document).on('click', '.spotlight-link', function(e)
	{
		var el = $(this);
		var spotlight = el.attr('data-type');
		
		$('.spotlight-link').removeClass('active');
		el.addClass('active');

		ajax.connect ({
			url: '/content/views/spotlight',
			type: 'GET',
			data: { 'spotlight' : spotlight, 'ajax' : true },
			dataType: 'text',
			loader: true,
			
			before: function () {
				$('#spotlight-images').stop().animate({'opacity' : 0.5});
			},
			
			done: function (data) {
				$('#spotlight-images').html(data);
				$('#spotlight-images').stop().animate({'opacity' : 1});
			}
		});

		e.preventDefault();
	});
	
});