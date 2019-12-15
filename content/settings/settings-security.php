<div class="normal-content">
  <h3>
  	Security Manager
  </h3>
  <p>
    Manage all your security here.
  </p>
  
  <hr />
  
  <form id="submit-security" class="form" method="post" action="#">
    <div class="row margin-bottom-xl"> 
      <div class="col-xs-12 col-sm-4">
        <div class="validate-group">
          <label for="password"> New Password</label>
        	<span class='strength-indicator pull-right' data-strength='-2' data-not-required></span>
        	<input name="new_password" id="password" class="form-control validate" data-strength='-1' type="password" placeholder="...">
        </div><!-- .validate-group -->
      </div><!-- .col -->
    </div><!-- .row -->
    
    <div id='password-warning' class='row hidden'>
    	<div class='col-sm-12'>
    		<div class='alert alert-danger small inline'>
    			Your password must be at least 6 characters long. It must contain at least 1 number or special character (! % & @ # $ ^ * ? _ ~)
    		</div>
    	</div>
    </div>
    
    <div class="row"> 
      <div class="col-xs-12 col-sm-4">
        <div class="validate-group margin-bottom">
          <label for="confirm-password">Confirm Password</label>
          <input name="confirm_password" id="confirm-password" class="form-control validate" type="password" placeholder="...">
          <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
          <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        </div><!-- .validate-group -->
      </div><!-- .col -->
    </div><!-- .row -->
    
    <span class="small red">Changing your password will destroy all active sessions.</span>
    
    <hr />
      
    <div class="row">          
      <div class="col-xs-12 text-left">
        <h4 class="margin-top-xs margin-bottom-xs">Session Timeout</h4>
        <span class="small">After <?php echo (new HTMLRender)->timeoutString(); ?> of inactivity, you'll have to enter a passcode to re-activate the session. Or just simply logout-login.</span>
        
        <br />
        <div class='switch margin-top-xl'>
  				<label for='timeout' id='timeout-label' class='margin-right <?php echo $sc->user->canTimeout ? 'enabled' : ''; ?>'>
						<span class='on'>ENABLED</span>
						<span class='off'>DISABLED</span>
					</label>
  				<input type="checkbox" class='toggle normal' name="timeout" id="timeout" data-changed="" <?php echo $sc->user->canTimeout ? 'checked' : ''; ?> />
  				<label for='timeout'></label>
  			</div>
      </div><!-- .col -->
    </div><!-- .row -->   
  
    <div id="timeout-section" <?php echo $sc->user->canTimeout ? '' : 'style="display: none;"'; ?>>
    	<hr />
    	
      <div class="row margin-bottom">
        <div class="col-xs-12">    
          <label for="passcode"> New Passcode</label> <span href="#" title="Enter your 4 digit passcode" class="badge" data-tooltip data-placement="right">?</span>      
        </div><!-- .col -->
      </div><!-- .row -->   
        
      <div class="row">  
        <div class="col-xs-12 col-sm-2">
          <div class="validate-group margin-bottom">
            <input name="new_passcode" id="passcode" class="form-control validate numbers-only" maxlength="4" type="text" placeholder="1234" />
          </div><!-- .validate-group -->             
        </div><!-- .col -->
      </div><!-- .row -->
    </div><!-- #timeout-section -->
  
  	<hr />
    
    <button type="submit" class="button button-primary margin-top">Save Settings</button>
  </form><!-- .form -->
</div>  