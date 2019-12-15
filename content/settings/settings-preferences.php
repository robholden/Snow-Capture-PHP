<div class="normal-content">

  <h3>
    Preference Manager
  </h3>
  <p>
    Manage all your preferences here.
  </p>
  
  <hr />
	
	<form id='settings-preferences'>
  	<div class="row">
  		<div class="col-xs-12 group-toggles">
    		<div class='switch'>
  				<label for='upload_geo' class='margin-right'>Upload Geolocation</label>
  				<input type="checkbox" class='toggle normal' name="upload_geo" id="upload_geo" <?php echo $sc->user->options->uploadGeo ? 'checked' : ''; ?> />
  				<label for='upload_geo'></label>
  			</div>
  			
  			<div class='switch margin-top'>
  				<label for='enable_emails' class='margin-right'>Enable Emails</label>
  				<input type="checkbox" class='toggle normal' name="enable_emails" id="enable_emails" <?php echo $sc->user->options->enableEmails ? 'checked' : ''; ?> />
  				<label for='enable_emails'></label>
  			</div>

    		<div id='inner-emails' class='clear' style='display: <?php echo ($sc->user->options->enableEmails) ? 'block' : 'none'; ?>'>
  				<div class='switch margin-top'>
    				<label for='send_likes' class='margin-right'><span class='inner-label'>Email</span> New Like</label>
    				<input type="checkbox" class='toggle normal' name="send_likes" id="send_likes" <?php echo $sc->user->options->sendLikes ? 'checked' : ''; ?> />
    				<label for='send_likes'></label>
    			</div>
    			
    			<div class='switch margin-top'>
    				<label for='send_processing' class='margin-right'><span class='inner-label'>Email</span> Picture Processed</label>
    				<input type="checkbox" class='toggle normal' name="send_processing" id="send_processing" <?php echo $sc->user->options->sendProcessing ? 'checked' : ''; ?> />
    				<label for='send_processing'></label>
    			</div>
    		</div>
  		</div>
  	</div>
    
    <hr />
    
    <button type="submit" class="button button-primary margin-top">Save Settings</button>
	</form>
</div>





