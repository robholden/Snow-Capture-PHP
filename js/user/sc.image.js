/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var image = {
		like: function (id, callbacks) {
			ajax.connect ({
				url: '/api/image/like',
				type: 'POST',
				data: { id: id },
				dataType: 'json',
				loader: false,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		},
		
		lock: function (id, callbacks) {
			ajax.connect ({
				url: '/api/image/private',
				type: 'POST',
				data: { id: id },
				dataType: 'json',
				loader: true,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		},
		
		rate: function (id, rating, callbacks) {
			ajax.connect ({
				url: '/api/image/rate',
				type: 'POST',
				data: { id: id, rating: rating },
				dataType: 'json',
				loader: false,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		},
		
		remove: function (id, callbacks) {
			ajax.connect ({
				url: '/api/image/delete',
				type: 'POST',
				data: { id: id },
				dataType: 'json',
				loader: true,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		},
		
		removeGeo: function (id, callbacks) {
			ajax.connect ({
				url: '/api/image/delete_geo',
				type: 'POST',
				data: { id: id },
				dataType: 'json',
				loader: true,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		},
		
		update: function (image, callbacks) {
			ajax.connect ({
				url: '/api/image/update',
				type: 'POST',
				data: image,
				dataType: 'json',
				loader: true,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		},
		
		events: {
			addTag: function () {
				var totalTags = 11;
				var tag = $('#image-tag');
				var newValue = tag.val().replace(/,/g, "").toLowerCase();
				tag.removeClass('duplicate');
				
				var contained = false;
				var limit = false;
				var count = 0;
				
				if (! (newValue.length > 0 && newValue.length <= 100))
					return;

				$('.new-tag').each(function(index, element)
				{
					var oldValue = $(element).text().replace(/\s+/, " ").toLowerCase();
					count = count + 1;

					if (oldValue == newValue.toLowerCase())
						contained = true;

					if (count >= totalTags)
						limit = true;
				});

				if (contained == false && limit == false)
				{
					$('#edit-tag').after('<div class="col-sm-6 col-md-3 generated-tag"><p class="capture-tag a-tag form-group"><i class="fa fa-tag icon-left"></i><span class="new-tag">' + newValue + '</span><a href="#" class="fa fa-times remove-tag icon-right"></a></p></div>')
					tag.val('');
					return true;
				}
				else
				{
					tag.addClass('duplicate');
				}

				if (limit == true)
					functions.toast('Maximum of ' + totalTags + ' tags', false);

				tag.val('');
			},
		
			rate: function (id, rating) {
				var callbacks = ajax.callbacks();
				callbacks.done = function (data) {
					if (typeof data.error == 'undefined')
						$('.rating-container').attr('data-rating', data.rating);
					else
						functions.toast(data.error, false);
				}
				
				image.rate(id, rating, callbacks);
			},
			
			resort: {
				reset: function () {
					$('#image-resort').html('');
					$('#image-resort').append($('<option>', {
						value : '0',
						text : 'Not Known'
					}));
				},
				
				update: function () {
					var country = $('#image-country').val();
					
					ajax.connect ({
						url: '/api/common/locations',
						type: 'POST',
						data: { country: country },
						dataType: 'json',
						loader: true,
						done: function (data) {
							image.events.resort.reset();
							
							if (typeof data.resorts === 'undefined')
								return;
								
							if (data.resorts.length == 0)
							{
								$('#resort-holder').addClass('hidden');
								return;
							}
							
							$('#resort-holder').removeClass('hidden');
							functions.resizeIconEls();
							$.each(data.resorts, function(i, item)
							{
								$('#image-resort').append($('<option>', {
									value : item.id,
									text : functions.utf8_decode(item.name)
								}));
							});
						}
					});
				}
			},
			
			update: function (id, url, save)
			{
				if (! image.events.validate())
					return;

				var prev = $('#preview');
				var data = {
					id: id,
					name: $('#image-name').val(),
					description: $('#image-description').val(),
					country_id: $('#image-country').val(),
					resort_id: $('#image-resort').val(),
					activity_id: $('#image-activity').val(),
					altitude_id: $('#image-altitude').val(),
					date_taken: $('#image-date').val(),
					show_cover: $('#show-cover').is(':checked') ? 'true' : 'false',
					show_map: $('#show-map').is(':checked') ? 'true' : 'false',
					generate: vars.mobile ? false : prev.hasClass('generate'),
					save: save
				}

				// Ensure user has saved/cancelled thumbnail
				if (prev.hasClass('edited'))
				{
					functions.toast("Please confirm your thumbnail", false);
					return;
				}

				// Get thumbnail
				var x1, x2, y1, y2, height, width;
				if (data.generate)
				{
					data.x1 = prev.data('x1');
					data.x2 = prev.data('x2');
					data.y1 = prev.data('y1');
					data.y2 = prev.data('y2');

					data.height = prev.data('height');
					data.width = prev.data('width');
				}

				// Get tags
				var hasTags = false;
				var temp_tags = [];
				var tags = '';
				$('.new-tag').each(function()
				{
					var val = $(this).text();
					temp_tags.push(val);			
				});
				
				temp_tags.reverse();
				for (var i = 0; i < temp_tags.length; i++)
				{			
					var val = temp_tags[i];
					hasTags = true;
					tags += (val != '') ? val : '';
					tags += ',';
				}
				
				data.tags = (hasTags == true) ? tags.substring(0, tags.length - 1) : '';

				var callbacks = ajax.callbacks();
				callbacks.done = function (data) {
					if (typeof data.error === 'undefined')
					{
						functions.toast(data.success, true);
						
						if (typeof url !== 'undefined') 
						{
							setTimeout(function()
							{
								if (url == '')
									history.back();
								else
									location.href = url;
							}, 1000);
						}
					}
					else
					{
						functions.toast(data.error, false);
					}
				}
				
				image.update(data, callbacks);
			},
			
			validate: function () {
				var status = true;
				var el_name = $('#image-name');
				var name = $.trim(el_name.val());
				var namegroup = el_name.parent('.form-group');

				var el_date = $('#image-date');
				var date = el_date.val();
				var dategroup = el_date.parent('.form-group');

				namegroup.removeClass('has-error');
				dategroup.removeClass('has-error');
				var error = "";

				if (name.length == 0)
				{
					namegroup.addClass('has-error');

					error = "Please name your photo!"
					$('body').stop().animate({
						scrollTop : el_name.offset().top - 175
					});
					el_name.focus();
					status = false;
				}

				if (status)
				{
					if (date.length == 0)
					{
						dategroup.addClass('has-error');

						error = "When was this taken?"
						$('body').stop().animate({
							scrollTop : el_date.offset().top - 175
						});
						el_date.focus();
						status = false;
					}
				}

				if (status == false)
				{
					functions.toast(error, false);
				}

				return status;
			}
		}
}

$(document).ready(function(e)
{
	
	// Event to update resorts based on country
	$(document).on('change', '#image-country', function(e)
	{
		image.events.resort.update(0);
		e.preventDefault();
	});
	
	// Remove geo from image
	$(document).on('click', '#show-map', function(e) {
		var prevImg = $('#preview img');
		$('#map').toggle();
		googleMap.initialise(lat, lon, path, zoom);
	});

	$(document).on('click', '.remove-geo', function(e)
	{
		var id = $(this).attr('data-image');
		functions.dialog.confirm("Remove Geo Data", "Are you sure you want to remove the Geo Data?", [['class', 'confirm-remove-geo'], ['data-image', id]]);

		e.preventDefault();
	});
	
	$(document).on('submit', '#confirm-dialog.confirm-remove-geo', function(e)
	{		
		var id = $(this).attr('data-image');		
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				$('.edit-location').removeClass('disabled').children('select').removeAttr('disabled');
			}

			else
			{
				functions.toast(data.error, false);
			}
		}
		
		image.removeGeo(id, callbacks);
		
		$('#geo-data, .remove-geo-parent').fadeOut(250, function() {
			$('#map-container').fadeOut(250, function() {
				$(this).remove();
			});
		});
		
		functions.dialog.close();
		e.preventDefault();
	});
	
	
	// Event to like an image
	$(document).on('click', '.like-image', function(e)
	{
		var like = $(this).attr('data-original-title');
		if (like == "Like")
			like = "Un-Like";
		else
			like = "Like";
		
		$(this).attr('data-original-title', like);
		$(this).tooltip('hide');
		
		// Get fav count
		var elCount = $('.capture-like-count');
		var count = parseInt(elCount.html());

		// Set new count
		count = ($(this).hasClass('liked')) ? (count - 1) : (count + 1);
		elCount.html(count);

		// Toggle class
		$(this).toggleClass('liked');

		var id = $(this).attr('data-image');
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error != 'undefined')
			{
				functions.toast(data.error, false);
			}
		}
		
		image.like(id, callbacks);
		
		e.preventDefault();
	});
	
	
	
	// Event to open rate an image
	$(document).on('click', '.image-rate:not(".rated")', function(e)
	{
		var parent = $(this).parent('.rate-stat');
		
		if ($(this).hasClass('rating'))
		{
			$(this).attr('data-original-title', "Rate");
			parent.children('.rate-image').hide().addClass('off');
		}
		
		else
		{
			$(this).attr('data-original-title', "Cancel");
			parent.children('.rate-image').each(function(i, element){
				var el = $(element);
				el.css('display', 'inline-block');
				
				setTimeout(function() {
					el.removeClass('off');
				}, (4 - i) * 5);
			});
		}
		
		$(this).tooltip('hide');

		$(this).toggleClass('rating');
		e.preventDefault();
	});
	
	
	//Event to rate an image
	$(document).on('click', '.image-rate.rated', function(e)
	{
		$(this).attr('data-original-title', "Rate");
		$(this).tooltip('hide');
		
		// Toggle class
		$(this).toggleClass('rated');
		
		image.events.rate($(this).data('image'), 0);
		e.preventDefault();
	});
	
	
	//Event to rate an image
	$(document).on('click', '.rate-image', function(e)
	{
		var imgR = $('.image-rate');
		imgR.attr('data-original-title', "Remove");
		imgR.parent('.rate-stat').children('.rate-image').hide().addClass('off');
		imgR.addClass('rated').removeClass('rating');
		
		$(this).tooltip('hide');
		
		image.events.rate($(this).data('image'), $(this).data('rating'));
		e.preventDefault();
	});
	
	
	//Event to rate an image
	$(document).on('mouseover', '.rate-image', function(e)
	{
		$(this).prevAll('.rate-image').addClass('active');
	}).on('mouseout', function(e) {
		$('.rate-image').removeClass('active');
	});
	
  
	// Event to update image
	$(document).on('click', '.update-image', function(e)
	{
		// Validate all fields are filled
		// Method has logic to move to next panel
		var id = $(this).attr('data-image');
		var url = $(this).attr('data-url');
		
		if (typeof $(this).attr('data-draft') !== 'undefined')
		{
			functions.dialog.confirm(
					"Publish Image", 
					"Are you sure you want to send this picture for publishing?", 
					[
				 		['class', 'publish-image'], 
				 		['data-image', id], 
				 		['data-url', url]
			 		]
				);
		}
		
		else
		{
			image.events.update(id, url, $(this).hasClass('save'));
		}

		e.preventDefault();
	});
	
	
	
	
	//Event to update image
	$(document).on('submit', '.publish-image', function(e)
	{
		// Validate all fields are filled
		// Method has logic to move to next panel
		var id = $(this).attr('data-image');
		var url = $(this).attr('data-url');
		
		image.events.update(id, url, $(this).hasClass('save'));
		functions.dialog.close();
		
		e.preventDefault();
	});
	


	// Event to delete image
	$(document).on('click', '.delete-image', function(e)
	{
		var id = $(this).attr('data-image');
		var url = $(this).attr('data-url');
		functions.dialog.confirm("Delete Image", "Are you sure you want to delete this?", [['class', 'confirm-delete-image'], ['data-image', id], ['data-url', url]]);

		e.preventDefault();
	});
	
	$(document).on('submit', '#confirm-dialog.confirm-delete-image', function(e)
	{		
		var id = $(this).attr('data-image');
		var url = $(this).attr('data-url');
		url = (typeof url == 'undefined') ? "/" : url;
		
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function()
				{
					location.href = url;
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}
		
		image.remove(id, callbacks);
		
		functions.dialog.close();
		
		e.preventDefault();
	});

	
	
	// Event to private an image
	$(document).on('click', '.private-image', function(e)
	{
		var id = $(this).attr('data-image');
		var type = 'ublish';
		if ($(this).hasClass('private-this'))
			type = 'rivate';

		functions.dialog.confirm("P" + type + " Image", "Are you sure you want to p" + type + " this?", [['class', 'confirm-private-image'], ['data-image', id]]);
		e.preventDefault();
	});
	
	$(document).on('submit', '#confirm-dialog.confirm-private-image', function(e)
	{		
		var id = $(this).attr('data-image');
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function()
				{
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}
		
		image.lock(id, callbacks);
		
		functions.dialog.close();
		e.preventDefault();
	});
	
	
	
	// Event to remove a tag
	$(document).on('click', '.remove-tag', function(e)
	{
		var value = $.trim($(this).prev('.new-tag').text());
		var parent = $(this).closest('.generated-tag');
		$('.tags a[data-value="' + value + '"]').fadeIn(250);
		parent.fadeOut(250, function()
		{
			parent.remove()
		});
		e.preventDefault();
	});
	
	
	
	//Event add a quick tag
	$(document).on('click', '.quick-tag', function(e)
	{
		var el = $(this),
				value = $.trim(el.attr('data-value'));
		
		$('#image-tag').val(value);
		if (image.events.addTag() === true)
		{
			el.fadeOut(250);
		}
		
		e.preventDefault();
	});

	
	
	// Dynamically add tags to page
	$('#image-tag').on('keypress', function(e)
	{
		if (e.keyCode == 13 || e.keyCode == 44)
		{
			image.events.addTag();
			e.preventDefault();
		}
	});
	
	
	
	// Stop form submitting on enter
	$(document).on('keypress', '.edit-image-form input', function(e)
	{
		if (e.keyCode == 13)
		{
			e.preventDefault();
		}
	});
	
});