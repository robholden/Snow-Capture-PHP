/**
 * 
 * Author: Robert Holden Project: Snow Capture
 * 
 */
var upload = {
	acceptTerms: function () {
		ajax.connect ({
			url: '/api/user/accept_process',
			type: 'POST',
			data: { accepted : 'yes' },
			dataType: 'json',
			loader: true,
			done: function (data) { 
				if (typeof data.error === 'undefined')
					$('.open-image-terms').addClass('open-upload').removeClass('open-image-terms');
				else
					functions.toast(data.error, false);
			},
		});
	},
	
	progressCircle: Circles.create({
		id : 'upload-progress',
		radius : 60,
		value : 0,
		maxValue : 100,
		width : 10,
		text : function(value)
		{
			return value + '%';
		},
		colors : ['#1F4D7B', '#467DB3'],
		duration : 250,
		wrpClass : 'circles-wrp',
		textClass : 'circles-text',
		styleWrapper : true,
		styleText : true
	}),
	
	go: function (event) {
		// Create a formdata object and add the files
		var formData = new FormData(document.getElementById("form-upload"));
		var uploader = $.ajax({
			url : '/api/image/upload',
			type : 'POST',
			data : formData,
			cache : false,
			beforeSend : function()
			{
				$('#upload-status').html('Uploading');
				$('#upload-progress').show();
				$('#upload-cover').fadeIn('250');
				$('#upload-dots').hide();
			},
			xhr : function()
			{
				var xhr = $.ajaxSettings.xhr();
				xhr.upload.addEventListener('progress', function(ev)
				{
					var progress = parseInt(ev.loaded / (ev.total / 100));

					if (progress == 100)  
					{
						$('#upload-status').html('<span class="processing-image">Processing Image(s), Please Wait...</span>');
						$('#upload-progress').fadeOut(250);
						$('#upload-dots').fadeIn(250);
					}

					upload.progressCircle.update(progress, 0);
				}, false);

				return xhr;
			},
			dataType : 'json',
			processData : false,
			contentType : false
		})

		.done(function(data, textStatus, jqXHR)
		{
			if (typeof data.error === 'undefined' && typeof data.location !== 'undefined')
			{
				upload.progressCircle.update(100, 0);
			}

			else
			{

				// Handle errors here
				$('#upload-status').html('<span class="red">' + data.error + "</span><a href='#' class='close-upload'>OK</a>");
				setTimeout(function()
				{
					upload.progressCircle.update(0, 500)
				}, 500);

			}

			if (typeof data.location !== 'undefined')
			{
				setTimeout(function()
				{
					location.href = data.location;
				}, 1000);
			}
		})

		.always(function()
		{
			var clone = $('#upload-image').clone();
			$('#upload-image').remove();
			$('#form-upload').append(clone);
		})

		.fail(function(jqXHR, textStatus, errorThrown)
		{
			if (vars.debug)
			{
				console.log(jqXHR.responseText);
			}
			$('#upload-cover').fadeOut('250');
		});
	},
	
	open: function () {
		$('#upload-image').click();
	},
	
	close: function () {
		$('#upload-cover').fadeOut('250');
	}
}

$(document).ready(function()
{
	$(document).on('change', '#upload-image', function(e)
	{
		var uploadCount = $(this).data('count');
		var uploadLimit = $(this).data('limit');
		var fileUpload = $("#upload-image[type='file']");
		var numOfFiles = parseInt(fileUpload.get(0).files.length);

		if (numOfFiles > uploadCount)
		{
			if (uploadLimit == uploadCount)
			{
				functions.toast('You can only upload ' + uploadCount + ' images at a time.', false);
			}
			else
			{
				var sep = uploadCount > 1 ? 's' : '';
				functions.toast('You can only upload ' + uploadCount + ' more image' + sep + '.', false);
			}
		}
		else
		{
			if (numOfFiles > 0)
				upload.go(e);
		}

		$(this).blur();
	});

	$(document).on('click', '.limit-reached', function(e)
	{
		var uploadLimit = $(this).data('limit');
		functions.toast('Draft limit reached: ' + uploadLimit + '/' + uploadLimit, false);
		e.preventDefault();
	});

	$(document).on('click', '.open-upload', function(e)
	{
		upload.open();
		e.preventDefault();
	});

	$(document).on('click', '.open-image-terms', function(e)
	{
		$('#ImageTermsModal').modal('show');
		e.preventDefault();
	});

	$(document).on('click', '#image-terms-agree', function(e)
	{
		upload.acceptTerms();
		setTimeout(upload.open, 250);
		e.preventDefault();
	});
});

// Close uploader
$(document).on('click', '.close-upload', function(e)
{
	upload.close();
	e.preventDefault();
});


