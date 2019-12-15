/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var user = {
	changeImage: function (id, cover, callbacks) {
		ajax.connect ({
			url: '/api/user/update_image',
			type: 'POST',
			data: { id: id, cover: cover },
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
	
	email: {
		confirm: function (callbacks) {
			ajax.connect ({
				url: '/api/user/confirm',
				type: 'POST',
				data: {},
				dataType: 'json',
				loader: true,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		}
	},
	
	logout: function (callbacks) {
		ajax.connect ({
			url: '/',
			type: 'GET',
			data: { 'logout': true },
			dataType: 'text',
			loader: true,
			before: callbacks.before,
			done: function (data) {
				callbacks.done(data);
			},
			error: callbacks.fail,
			always: callbacks.always
		});
	},
	
	resort: {
		request: function (resort, callbacks) {
			ajax.connect ({
				url: '/api/common/resort_request',
				type: 'POST',
				data: { resort: resort },
				dataType: 'json',
				loader: true,
				before: callbacks.before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: callbacks.always
			});
		}
	},
	
	unlock: function (passcode, token, callbacks) {
		ajax.connect ({
			url: '/api/user/unlock',
			type: 'POST',
			data: { passcode: passcode, FORM_TOKEN: token },
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
		openSearch: function ()
		{
			$('.user-search').stop().animate({
				'width' : '250px'
			}, 1000);

			if (!$('.toggle-user-search').hasClass('opened'))
			{
				$('.toggle-user-search').addClass('opened');
			}

			$('.user-search').focus();
		},

		closeSearch: function ()
		{
			$('.user-search').stop().animate({
				'width' : '0'
			}, 1000);

			if ($('.toggle-user-search').hasClass('opened'))
			{
				$('.toggle-user-search').removeClass('opened');
			}
		}
	}
}










$(document).ready(function(e)
{
	
	// Event to open search bar
	$(document).on('click', '.toggle-user-search', function(e)
	{
		if (!$(this).hasClass('opened'))
		{
			e.preventDefault();
		}
		
		var val = $('.user-search').val();
		if (val != '')
		{
			location.href = $(this).attr('href') + val;
			e.preventDefault();
		}
		 
		else
		{
			user.events.openSearch();
		}
	});

	
	
	// Event to close search bar
	$(document).on('focus', '.user-search', function(e)
	{
		if (!$('.toggle-user-search').hasClass('opened'))
		{
			user.events.openSearch();
		}
	});

	
	
	// Event to close search bar after specific focusout time
	$(document).on('focusout', '.user-search', function()
	{
		setTimeout(function()
		{
			user.events.closeSearch();
		}, 250);
	});
	
	
	
	// Open uploader
	$(document).on('click', '.open-upload-link', function(e)
	{
		var u = document.getElementById('upload-link');
		u.click();
		e.preventDefault();
	});

	
	
	
	// Allow user to change profile - replies on filter.watch in custom.js
	$(document).on('click', '.profile-picture.choose-pic', function(e)
	{
		if ($('#type').val() == 'choosing' || $('#type').val() == 'choosing_cover')
		{
			$('#type').val($('#type').data('old'));
			$('#choose-alert').addClass('hidden');
		}
		else   
		{
			$('#type').attr('data-old', $('#type').val());
			$('#type').val('choosing');
			$('#choose-alert').removeClass('hidden');

			functions.scrollTo('#image-bookmark', 250);
		}
    
		filter.watch(false);
		e.preventDefault();
	});
	
	   
	
	
	//Allow user to change profile - replies on filter.watch in custom.js
	$(document).on('click', '.cover-picture.choose-pic', function(e)
	{
		if ($('#type').val() == 'choosing_cover')
		{
			$('#type').val($('#type').data('old'));
			$('#choose-alert').addClass('hidden');
		}
		else
		{
			$('#type').attr('data-old', $('#type').val());
			$('#type').val('choosing_cover');
			$('#choose-alert').removeClass('hidden');

			functions.scrollTo('#image-bookmark', 250);	
		}
    
		filter.watch(false);
		e.preventDefault();
	});

	
	
	// Event to change profile picture
	$(document).on('click', '.choosing', function(e)
	{
		var id = $(this).data('image');
		var cover = $(this).data('cover') ? 'true' : '';
		
		var callbacks = ajax.callbacks();
		callbacks.done = function(data){
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
		};
		
		user.changeImage(id, cover, callbacks);
		e.preventDefault();
	});


	
	// Event to request a resort
	$(document).on('submit', '#beta-resort-form', function(e)
	{
		e.preventDefault();
		
		var resort = $('#request-resort').val();
		if (resort.length == 0)
		{
			$('#request-resort').focus();
			return;
		}
		
		$('#beta-resort').removeClass('taken');

		var callbacks = ajax.callbacks();
		
		callbacks.done = function(data)	{
			if (typeof data.error === 'undefined')
			{
				$('#beta-resort').html('<h5>Request submitted, thanks :)</h5>');
				setTimeout(function()
				{
					$('#beta-resort').fadeOut(300, function()
					{
						$('#beta-resort').remove();
					});
				}, 1500);
			}
			else
			{
				$('#request-resort').val('');
				$('#beta-resort').addClass('taken');
				functions.functions.toast(data.error, false);
			}
		}
		
		user.resort.request(resort, callbacks);
	});
	
	//Event to logout
	$(document).on('click', '.logout', function(e)
	{
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			location.reload();
		}
		
		user.logout(callbacks);
		e.preventDefault();
	});
	
	
	
	// Event to send user email confirmation
	$(document).on('click', '.send-confirm', function(e)
	{
		var callbacks = ajax.callbacks();
		
		callbacks.before = function () {
			$('.send-confirm').fadeOut(250, function() {
				$('.send-confirm').remove();
			});
		}
		
		callbacks.done = function(data)	{
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}
		
		user.email.confirm(callbacks);
		
		e.preventDefault();
	});
	
	
	
	// Event to unlock session
	$(document).on('submit', '#passcode-form', function(e)
	{
		e.preventDefault();

		if (! form.validate($(this)))
			return;
		
		var url = $('#PASSCODE_URL').val();
		var token = $('#PASSCODE_FORM_TOKEN').val();
		var passcode = $('#passcode').val();
		
		var callbacks = ajax.callbacks();
		
		callbacks.before = function () {
			$('#passcode').parent('.validate-group').removeClass('has-error');
		}
		
		callbacks.done = function(data)	{
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function()
				{
					if(url.length == 0)
		      {
		        location.reload();
		      }
		      else
		      {
		        location.href = url;
		      }
				}, 1000);
			}

			else
			{
				$('#passcode').parent('.validate-group').addClass('has-error');
				$('#passcode').focus();
				functions.toast(data.error, false);
			}

			$('#passcode').val('');
		}
		
		user.unlock(passcode, token, callbacks);
	});
});


