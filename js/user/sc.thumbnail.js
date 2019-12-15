/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */

var thumbnail = {
	preview: function (img, selection) {
		if (!selection.width || !selection.height) return;
	
		var mainImg = $('#capture-img');
		var prev = $('#preview');
		var prevImg = $('#preview img');
	
		if (prev.hasClass('edited') == false)
		{
			prevImg.attr('data-src', prevImg.attr('src'));
			prevImg.attr('src', mainImg.attr('src'));
		}
	     
		var scaleX = prev.innerWidth() / selection.width;
		var scaleY = prev.innerHeight() / selection.height;
	
		prevImg.css({
			width : Math.round(scaleX * mainImg.innerWidth()) + "px",
			height : Math.round(scaleY * mainImg.innerHeight()) + "px",
			marginLeft : -Math.round(scaleX * selection.x1),
			marginTop : -Math.round(scaleY * selection.y1)
		});
	
		prev.attr('data-x1', selection.x1);
		prev.attr('data-y1', selection.y1);
		prev.attr('data-x2', selection.x2);
		prev.attr('data-y2', selection.y2);
	
		prev.attr('data-width', mainImg.innerWidth());
		prev.attr('data-height', mainImg.innerHeight());
	
		prev.addClass('edited');
	}
}

$(document).ready(function(e) {
	
	var ias;
	
	$('#capture-img').load(function() {
		ias = $('#capture-img').imgAreaSelect({
			instance : true
		});
		ias.setOptions({
			parent : '#capture-img-container',
			aspectRatio : '4:3',
			handles : true,
			onSelectEnd : thumbnail.preview
		});
		ias.update();
	});

	$(document).on('click', '.cancel-crop', function(e) {
		var prevImg = $('#preview img');
		prevImg.attr('src', prevImg.data('src'));
		prevImg.attr('style', '');

		e.preventDefault();
	});

	$(document).on('click', '.crop-option', function(e) {
		$('#preview').removeClass('edited');
		ias.setOptions({
			hide : true
		});
		ias.update();

		if ($(this).hasClass('fa-check'))
		{
			$('#preview').addClass('generate');
		}

		e.preventDefault();
	});
});