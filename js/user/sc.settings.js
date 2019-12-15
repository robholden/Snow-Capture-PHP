/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var settings = {
		general: {
			update: function (user, callbacks) {
				ajax.connect ({
					url: '/api/user/update_general',
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
			},
			
			deleteAccount: function (data, callbacks) {
				ajax.connect ({
					url: '/api/user/delete',
					type: 'POST',
					data: data,
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
				update: function (isPass) {
					var origemail = $('#email').attr('data-original');
				  var user = {
						name: $('#name').val(),
						display_name: $('#display-name').val(),
						email: $('#email').val(),
						password: ($('#email').val() !== origemail) ? $('#email-password').val() : '',
				  }
				  
				  var callbacks = ajax.callbacks();
				  
				  callbacks.done = function (data) {
				  	if(typeof data.error === 'undefined')
				    {
				      functions.toast(data.success, true);
				      $('#email').val(user.email);
				      $('#email').attr('data-original', user.email);
				      $('#settings-form .has-error, #settings-form .has-success').removeClass('has-error has-success');
				  		$('#ConfirmModal').modal('hide');
				  		
				  		if (isPass)
				  		{
				  			setTimeout(function () {
				  				location.reload();
				  			}, 1000);
				  		}
				    }

				    else
				    {
				      functions.toast(data.error, false);

				      var err = data.error.toLowerCase();
				      if(err.indexOf('name') > -1)
				      {
				        if(err.indexOf('user') > -1)
				          $('#display-name').parent('.validate-group').addClass('has-error');
				        else
				          $('#name').parent('.validate-group').addClass('has-error');
				      }

				      if(err.indexOf('email') > -1)
				        $('#email').parent('.validate-group').addClass('has-error');
				      
				      if(err.indexOf('password') > -1) 
				      {
				      	$('#email-password').parent('.validate-group').addClass('has-error');
					      $('#email-password').focus();
				      }
				      else 
				    	{
				    		$('#ConfirmModal').modal('hide');
				    	}
				    }
				  } 

				  this.general.update(user, callbacks);
				},
				
				displayCheck: function () {
					var status = true;
				  var oldName = $('#display-name').val();
				  var newName = $('#display-name').data('original');

				  $('#display-name').parent('.validate-group').removeClass('has-error has-success');
				  if(newName != oldName)
				  {
				    if(oldName.toLowerCase() != newName.toLowerCase())
				    {  
				      $('#display-name').parent('.validate-group').addClass('has-error');
				      status = false;
				    }          
				    else
				    {
				      $('#display-name').parent('.validate-group').addClass('has-success');
				    }
				  }

				  return status;
				}
			}
		},
		
		session: {
			end: function (data, callbacks) {
				ajax.connect ({
					url: '/api/session/end',
					type: 'POST',
					data: data,
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
		
		security: {
			update: function (data, callbacks) {
				ajax.connect ({
					url: '/api/user/update_security',
					type: 'POST',
					data: data,
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
		
		preferences: {
			update: function (options, callbacks) {
				ajax.connect ({
					url: '/api/user/update_options',
					type: 'POST',
					data: options,
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
};

$(document).ready(function(e){

	// Remove error class on focus
  $(document).on('focus', '.validate', function(e){
    $(this).parent('.validate-group').removeClass('has-error');
  });
  
  //Add email check event
  $(document).on('change', '#email', function(e){
  	
  	// Build ajax callbacks
  	var email = $(this).val();
  	var callbacks = ajax.callbacks();
  	
  	$('#email').parent('.validate-group').removeClass('has-error has-success');
    if(email == $('#email').data('original'))
    	return;
    
  	callbacks.done = function (data) {
  		if (data.exists == true)
	      $('#email').parent('.validate-group').addClass('has-error');
	    else
	      $('#email').parent('.validate-group').addClass('has-success');
  	}
  	
    register.check.email($(this).val(), callbacks);
  });
  
  
  
  // Add display username check event
  $(document).on('change keyup', '#display-name', function(e){
    settings.general.events.displayCheck();
  });
  
  
  // Event to save general settings (WITHOUT EMAIL)
  $(document).on('submit', '#settings-form', function(e){
  	e.preventDefault();
  	
    var validated = form.validate($(this));
    var emailOK = ! $('#email').parent('.validate-group').hasClass('has-error');
    var displayOK = settings.general.events.displayCheck();

    if(! validated || ! emailOK || ! displayOK)
    	return;
    
    var oldEmail = $('#email').data('original');
    var email = $('#email').val();

    if(oldEmail.toLowerCase() != email.toLowerCase())
    {
      $('#ConfirmModal').modal('show');

      setTimeout(function(){
        $('#email-password').focus();
      }, 500);
    }

    else
    {
    	settings.general.events.update();
    }
  });
  
  // Event to save general settings (WITH EMAIL)
  $(document).on('submit', '#save-email', function(e){
    var validated = form.validate($('#settings-form'));
    var emailOK = ! $('#email').parent('.validate-group').hasClass('has-error');
    var displayOK = settings.general.events.displayCheck();

    if(validated && emailOK && displayOK)
    {
      if($('#email-password').val() !== '')
      {
      	settings.general.events.update(true);
        $('#email-password').val('');
      }
    }

    e.preventDefault();
  });
 
  
  // Event to delete account
  $(document).on('submit', '#delete-account', function(e){
    var token = $('#token').val();
    var pass = $('#delete-password').val();
    var btn = $('#delete-account button[type="submit"]');
    var beforeText = btn.text();
  	var callbacks = ajax.callbacks();
  	
  	callbacks.before = function () {
  		btn.text('Deleting...');
  	}
  	
  	callbacks.done = function (data) {
  		if(typeof data.error === 'undefined')
      {
  			btn.text('Deleted');
        functions.toast(data.success, true);
        setTimeout(function() {
          location.href = "/";
  			}, 2500);
      }

      else
      {
        functions.toast(data.error, false);
  			btn.text(beforeText);
        $('#delete-password').focus();
      }
  	}
  	
  	settings.general.deleteAccount({
  		token: token,
  		password: pass
  	}, callbacks);
  	
    e.preventDefault();
  });  
  
  
  
//Add password confirm check event
  $(document).on('change keyup', '#password', function(e){
    passwordConfirmCheck();
  });

  
  
  // Add password confirm check event
  $(document).on('change keyup', '#confirm-password', function(e){
    passwordConfirmCheck();
  });
	
  
  
  // Toggle timeout panel visibility
  $(document).on('click', '#timeout', function(e){
  	$('#timeout-section').slideToggle(250);
  	$('#timeout-label').toggleClass('enabled');
  });

  
  
  // Detect if timeout setting has been changed
  $(document).on('change', '#timeout', function(e){
  	$(this).attr('data-changed', 'true');
  });
  
  
  
  // Validate settings and display password popup
  $(document).on('submit', '#submit-security', function(e){  	  	  
  	if($('#passcode').val() == '' && $('#password').val() == '' && $('#timeout').attr('data-changed') == '')
  	{  		
  		$('#SecurityModal').modal('hide');
  		functions.toast("Nothing to save", true);
  	}
  	
  	else
  	{
  		var passwordOK = passwordConfirmCheck(true);
      if(passwordOK)
      {
        $('#SecurityModal').modal('show');

        setTimeout(function () {
          $('#old-password').focus();
        }, 500);
      }
		}

    e.preventDefault();
  });  

  
  
  // Event to save security settings
  $(document).on('submit', '#security-form', function(e){  	
    e.preventDefault();
    
    var passwordOK = passwordConfirmCheck(true);
    if(! passwordOK)
    	return;

    $('#old-password').blur();
    var data = {
  		password: $('#old-password').val(),
  		new_password: $('#password').val(),
  		timeout: $('#timeout').is(':checked') ? 'true' : 'false',
			passcode: timeout ? $('#passcode').val() : ''
    }
    
    var callbacks = ajax.callbacks();
    
    callbacks.done = function (data) {
    	if(typeof data.error === 'undefined')
      {
        functions.toast(data.success, true);
        $('#SecurityModal').modal('hide');
        $('#password').val('');
        $('#confirm-password').val('');
        $('#passcode').val('');
      }

      else
      {
      	if(data.error.toLowerCase().indexOf('incorrect') > -1)
      	{
    			$('#old-password').focus();
      	}
    
  	  	else
  	  	{
  	      $('#SecurityModal').modal('hide');
    			$('#passcode').focus();	  		
    		  $('#passcode').parent('.validate-group').addClass('has-error');
  	  	}
        
        functions.toast(data.error, false);
      }

      $('#old-password').val('');
    }
    
    settings.security.update(data, callbacks);
  }); 

  $(document).on('change', '#enable_emails', function (e){
		$('#inner-emails').slideToggle(250);
	});
	
	$(document).on('submit', '#settings-preferences', function(e){
		var options = {
			enable_emails 		: $('#enable_emails').is(':checked'),
			send_likes 				: $('#send_likes').is(':checked'),
			send_processing 	: $('#send_processing').is(':checked'),
			upload_geo			 	: $('#upload_geo').is(':checked')
		}
		
		var callbacks = ajax.callbacks();
		
		callbacks.done = function (data) {
			if(typeof data.error === 'undefined')
	      functions.toast(data.success, true);
	    else
	      functions.toast(data.error, false);
		}
		
		settings.preferences.update(options, callbacks);
		
    e.preventDefault();
  }); 
	
	
	//Display end session dialog
	$(document).on('click', '.init-end-session', function(e){
    var sessid = $(this).data('session');
    $('#end-session').attr('data-session', sessid);

    setTimeout(function(){
      $('#session-password').focus();
    }, 500);
  });
  
  
  
  //Display end all sessions dialog
  $(document).on('click', '.clear-sessions', function(e){
    setTimeout(function(){
      $('#session-password').focus();
    }, 500);
  });

	
	
	// Event to end a session
  $(document).on('submit', '#end-session', function(e){
  	e.preventDefault();
  	
    var id = $(this).attr('data-session');
    id = (id ? id : $('#active-sessions').val())
    var pass = $('#session-password');

    if(pass.val() == '')
    {
      pass.parent('.session-group').addClass('has-error');
      return;
    }
    
    var callbacks = ajax.callbacks();
    
    callbacks.done = function (data) {
    	if(typeof data.error === 'undefined')
      {
        functions.toast(data.success, true);
        
        if (id)
        {
  	      $('div[data-session="' + id + '"]').slideUp();
  	      $('#active-count').html(parseInt($('#active-count').html()) - 1);
        }
        
        else
        {
        	$('.active-session').slideUp();
  	      $('#active-count').html(1);
        }
        
        $('#session-cancel').click();
      }

      else
      {
      	if (data.error.indexOf('password') > -1)
      		$('#session-password').focus();
      	
        functions.toast(data.error, false);
      }
    }
    
    settings.session.end({
    	session_id: id, 
    	password: pass.val()
    }, callbacks);

    pass.blur();
    pass.parent('.session-group').removeClass('has-error');    
    pass.val('');
  });
});