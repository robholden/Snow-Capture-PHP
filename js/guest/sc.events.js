/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
$(document).ready(function() {
	$('.shared-media').click(function(e)
	{
		var url = $(this).attr('href');
		window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
		return false;
	});
	
	$(document).on('click', '.spinner-load', function(e)
	{
		$(this).addClass('disabled');
		$(this).children('i').addClass('fa-spin');
		$(this).children('i').removeClass('hidden');

		e.preventDefault();
	});
	
	$(document).on('click', '.register-local', function(e)
	{
		functions.scrollTo('#register', 250, function(){
			$('#name').focus();
		});

		e.preventDefault();
	});
	
	$(document).on('click', '*[data-scroll]', function(e){
		var id = $(this).attr('data-scroll'),
				speed = $(this).attr('data-speed');
		
		functions.scrollTo(id, speed);
		e.preventDefault();
	});

	// Scroll down to login form if on login page
	$(document).on('click', '.login-local', function(e)
	{
		$('body').stop().animate({
			scrollTop : $('#login').offset().top - 100
		}, 250, function()
		{
			$('#login-username').focus();
		});

		e.preventDefault();
	});
	
	$(document).on('keydown', '.numbers-only', function(e)
	{
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		// Allow: Ctrl+A, Command+A
		(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
		// Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40))
		{
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
		{
			e.preventDefault();
		}
	});

	// Redirect search bar to search page
	$(document).on('submit', '#search-form', function(e)
	{
		var val = $(this).find('input').val();
		if (val != '' && typeof val !== 'undefined')
		{
			location.href = "/?q=" + val;
		}
		else
		{
			location.href = "/";
		}
		e.preventDefault();
	});

	// Preload user page with link press
	$(document).on('click', '.preload-profile', function(e)
	{
		var type = $(this).data('type'), url = $(this).attr('href');
		if (type == $('#type').val().trim()) return;
		
		var title = '';
		switch (type.toLowerCase())
	  {
	    case 'user':
	    	title = '<i class="fa fa-star icon-left"></i> PUBLISHED';
	      break;

	    case 'processing':
	    	title = '<i class="fa ion-load-c icon-left"></i> PROCESSING';
	      break;

	    case 'drafts':
	    	title = '<i class="fa fa-edit icon-left"></i> DRAFTS';
	      break;

	    case 'privates':
	    	title = '<i class="fa fa-lock icon-left"></i> PRIVATES';
	      break;

	    case 'likes':
	    	title = '<i class="fa fa-heart icon-left"></i> LIKES';
	      break;
	  }
		
		$('#preload-title').html(title);
		$('#preload-title').attr('class', 'image-header ' + type);

		$('#type').val(type);
		ajax.URL(url);
		filter.watch(false);

		$('.user-link').removeClass('active');
		$(this).addClass('active');
		
		$('body').stop().animate({
			scrollTop : $('#stick-user').offset().top - (60)
		}, 250); 

		e.preventDefault();
	});

	//Assign tooltips if not mobile
	if (vars.mobile === false)
	{
		$('[data-tooltip]').tooltip();
	}
});

function passwordConfirmCheck(toasted)
{
	var status = false;
  var password = $('#password');
  var confirmed = $('#confirm-password');
  var warning = $('#password-warning');
  var isRequired = typeof password.prev('.strength-indicator').data('not-required') === 'undefined';
  
  if (confirmed.val() == '' && password.val() == '' && ! isRequired)
  	return true;
  
	var score = form.strength(password.val());
	password.attr('data-strength', score);
	password.prev('.strength-indicator').attr('data-strength', score);

	password.parent('.validate-group').removeClass('has-error has-success');
	confirmed.parent('.validate-group').removeClass('has-error has-success');
	warning.addClass('hidden');
	
  if (score < 2.5 && score > -1) {
  	warning.removeClass('hidden');
    password.parent('.validate-group').addClass('has-error');
	}
	
  if(password.val() != confirmed.val() && password.val().length > 0)
  {  
    confirmed.parent('.validate-group').addClass('has-error');
    
    if(typeof toasted !== 'undefined') {
    	functions.toast('Passwords do not match', false);
    	confirmed.focus();
    }
  }          
  else
  {
  	if (score >= 2.5) {
  		confirmed.parent('.validate-group').addClass('has-success');
  		status = true;
  	}
  	
  	else if(typeof toasted !== 'undefined') {
  		functions.toast('Please enter a more secure password', false);
			password.focus();
  	}
  }

  return status;
}