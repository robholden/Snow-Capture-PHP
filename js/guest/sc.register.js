/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var register = {
	check: {
		connected: {
			email: false,
			user: false
		},

		email: function (email, callbacks) 
		{
			if (this.connected.email)
				return;
			
			// Hijack before callback 
			var before = function () {
				callbacks.before();
				register.check.connected.email = true;
			}
			
			var always = function () {
				callbacks.always();
				register.check.connected.email = false;
			} 
			
		  ajax.connect ({
				url: '/api/validate/email',
				type: 'POST',
				data: { email : email },
				dataType: 'json',
				loader: true,
				before: before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: always
			});
		},
		
		username: function (username, callbacks) 
		{
			if (this.connected.user)
				return;
			
			// Hijack before callback 
			var before = function () {
				callbacks.before();
				register.check.connected.user = true;
			}
			
			var always = function () {
				callbacks.always();
				register.check.connected.user = false;
			} 
			
		  ajax.connect ({
				url: '/api/validate/username',
				type: 'POST',
				data: { username : username },
				dataType: 'json',
				loader: true,
				before: before,
				done: function (data) {
					callbacks.done(data);
				},
				error: callbacks.fail,
				always: always
			});
		}
	},
	
	signUp: function (user, callbacks) 
	{
		// Validate obj
		if (! user.name || ! user.username || ! user.email || ! user.password || ! user.recaptcha || ! user.FORM_TOKEN) 
			return false;
	  
		// Add guid
		user.guid = functions.guid();
		
		ajax.connect ({
			url: '/api/user/register',
			type: 'POST',
			data: user,
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
}


$(document).ready(function () {

	// Add username check event
  $(document).on('change', '#username', function(e){
  	
  	// Build ajax callbacks
  	var callbacks = ajax.callbacks();
  	
		callbacks.before = function () {
			$('#username').parent('.validate-group').addClass('has-changed');
      $('#username').parent('.validate-group').removeClass('has-success').removeClass('has-error');
  	}
  	
		callbacks.done = function (data) {
  		if (data.exists == true)
	      $('#username').parent('.validate-group').addClass('has-error');
	    else
	      $('#username').parent('.validate-group').addClass('has-success');
  	}
  	
    register.check.username($(this).val(), callbacks);
  	
  });
  
  

  // Add email check event
  $(document).on('change', '#email', function(e){
  	
  	// Build ajax callbacks
  	var callbacks = ajax.callbacks();
  	
  	callbacks.before = function () {
			$('#email').parent('.validate-group').addClass('has-changed');
      $('#email').parent('.validate-group').removeClass('has-success').removeClass('has-error');
  	}
    	
  	callbacks.done = function (data) {
  		if (data.exists == true)
	      $('#email').parent('.validate-group').addClass('has-error');
	    else
	      $('#email').parent('.validate-group').addClass('has-success');
  	}
  	
    register.check.email($(this).val(), callbacks);
  	emailConfirmCheck();
  	
  });
  

  
  //Add email confirm check event
  $(document).on('keyup', '#email', function(e){
    emailConfirmCheck();
  });
 
  
  
  // Add email confirm check event
  $(document).on('change keyup', '#confirm-email', function(e){
    emailConfirmCheck();
  });

  
  
  // Add password confirm check event
  $(document).on('change keyup', '#password', function(e){
    passwordConfirmCheck();
  });
  
  
  
  // Add password confirm check event
  $(document).on('change keyup', '#confirm-password', function(e){
    passwordConfirmCheck();
  });
  
  
  // Register form submitted event
  $(document).on('submit', '#register-form', function(e){
    e.preventDefault();
    
    var validated = form.validate($(this));
    var userOK = $('#username').parent('.validate-group').hasClass('has-success');
    var emailOK = $('#email').parent('.validate-group').hasClass('has-success');
    var recaptcha = validateRecaptcha();
    var emailCheck = emailConfirmCheck();
    var passwordCheck = ($('#password').val() == $('#confirm-password').val());
    var passwordStrength = (form.strength($('#password').val()) >= 2);
    
    if (!validated)
    {
    	functions.toast('Please enter all the field marked in red', false);
    }
    
    else if (emailOK !== true)
    {
    	if (!mobile) { $('#email').focus(); }
    	functions.toast('Email is already registered or invalid', false);
    }
    
    else if (emailCheck !== true)
    {
    	if (!mobile) { $('#confirm-email').focus(); }
    	functions.toast('Email and confirmed email do not match', false);
    }
    
    else if (userOK !== true)
    {
    	if (!mobile) { $('#username').focus(); }
    	functions.toast('Username is already registered or invalid', false);
    }
    
    else if (passwordStrength !== true)
    {
    	if (!mobile) { $('#password').focus(); }
    	functions.toast('Please enter a more secure password', false);
    }
    
    else if (passwordCheck !== true)
    {
    	if (!mobile) { $('#confirm-password').focus(); }
    	functions.toast('Password and confirm password to not match', false);
    }
    
    else if (recaptcha !== true)
    {
    	functions.toast('Please enter the ReCaptcha', false);
    } 
    
    else
    {
    	// Build user obj
    	user = {
	  		name: $('#name').val(),
	  	  username: $('#username').val(),
	  	  email: $('#email').val(),
	  	  password: $('#password').val(),
	  	  recaptcha: $('#g-recaptcha-response').val(),
	  	  FORM_TOKEN: $('#FORM_TOKEN').val()
    	}
    	
    	// Build ajax callbacks
      var callbacks = ajax.callbacks();
    	
      callbacks.before = function () {
    		$('#register-button').addClass('disabled').attr('disabled', 'disabled').text('Signing Up...');
    	}
      
      callbacks.fail = function () {
      	$('#register-button').removeClass('disabled').removeAttr('disabled').text('Sign Up');
      }
      
      callbacks.done = function (data) {
    		if(typeof data.error === 'undefined')
		    {
		      $('#register-button').text('Signing In...');
	        location.reload();
		    }

		    else
		    {
		      functions.toast(data.error, false);
		      $('#register-button').removeClass('disabled').removeAttr('disabled').text('Sign Up');

		      var err = data.error.toLowerCase();
		      if(err.indexOf('name') > -1)
		      {
		        if(err.indexOf('user') > -1)
		        {
		          $('#username').parent('.validate-group').addClass('has-error');
		          $('#username').focus();
		        }

		        else
		        {
		          $('#name').parent('.validate-group').addClass('has-error');
		          $('#name').focus();
		        }
		      }

		      if(err.indexOf('email') > -1)
		      {
		        $('#email').parent('.validate-group').addClass('has-error');
		        $('#email').focus();
		      }

		      if(err.indexOf('password') > -1)
		      {
		        $('#password').parent('.validate-group').addClass('has-error');
		        $('#password').focus();
		      }
		    }
    	}
    	
      register.signUp(user, callbacks);
    }

    if (typeof grecaptcha !== 'undefined')
      grecaptcha.reset();
  });
	
});

function validateRecaptcha() 
{
  var recaptcha = $('#g-recaptcha-response').val();
  if (typeof recaptcha === 'undefined')
  {
    return false;
  }

  var status = (recaptcha.trim() !== '');
	return status;
}

function emailConfirmCheck()
{
  var status = false;
  
  if($('#email').parent('.validate-group').hasClass('has-error') == false)
  {
    var email = $('#email').val();
    var confirmed = $('#confirm-email').val();

    if(email == confirmed && email.length > 0 && confirmed.length > 0)
    {
      status = true;
    }
  }

  if(status)
  {
    $('#confirm-email').parent('.validate-group').removeClass('has-error');
    $('#confirm-email').parent('.validate-group').addClass('has-success');
  }

  else
  {
    $('#confirm-email').parent('.validate-group').removeClass('has-success');
    $('#confirm-email').parent('.validate-group').addClass('has-error');
  }
  
  return status;
}