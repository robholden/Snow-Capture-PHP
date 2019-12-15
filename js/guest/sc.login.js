/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var login = {
	signUp: function (user, callbacks) 
	{
		// Validate obj
		if (! user.username || ! user.password) 
			return false;
	  
		// Add guid
		user.guid = functions.guid();
	  
		ajax.connect ({
			url: '/api/user/login',
			type: 'POST',
			data: user,
			dataType: 'json',
			loader: true,
			before: callbacks.before,
			done: function (data) {
				callbacks.done(data);
			},
			error: callbacks.fail
		});
	}
}


$(document).ready(function () {
	
	//Open sign in dashboard
	$(document).on('click', '.sign-in-dashboard', function(e){
    var opened = $(this).hasClass('opened') ? true : false;

    if (! opened)
      $('#login-username').blur();

    e.preventDefault();
  });
	
	
	
	// Event to login
	$('#login-form').on('submit', function(e){
		
		// Validate form
    var validated = form.validate($(this));
    if (! validated)
    	return false;
    
    // Build user obj
  	user = {
  		FORM_TOKEN: $('#LOGIN_FORM_TOKEN').val(),
  	  password: $('#login-password').val(),
  	  remembered: ($('#remembered').is(':checked') == true),
  	  url: $('#LOGIN_URL').val(),
  	  username: $('#login-username').val()
  	}
  	
  	// Build ajax callbacks
  	var callbacks = ajax.callbacks();
  	
    callbacks.before = function () {
    	$('#login-alert').addClass('hidden');
      $('#login-button').addClass('disabled').attr('disabled', 'disabled').text('Signing In...');
    }
    
    callbacks.fail = function (data) {
    	$('#login-alert').removeClass('hidden').html('<p>Hmmm. Something has gone wrong, please try again later :/</p>');
      $('#login-button').removeClass('disabled').removeAttr('disabled').text('Sign In');
    }
    
    callbacks.done = function (data) {
    	if(typeof data.error === 'undefined')
      {
      	$('#login-button').text(data.success);
      	setTimeout(function(){
  	      if(user.url.length == 0)
  	    		location.reload();
  	      else
  	        location.href = user.url;
      	}, 500); 
      } 
   
      else
      {
        $('#login-password').val('');
        $('#login-password').parent('.validate-group').addClass('has-error');
        $('#login-alert').removeClass('hidden').html(data.error);
        $('#login-button').removeClass('disabled').removeAttr('disabled').text('Sign In');
      }
    }
    
    login.signUp(user, callbacks);

    e.preventDefault();
  });
	
});