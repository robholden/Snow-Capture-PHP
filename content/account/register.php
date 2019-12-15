
<?php 

  if(isset($_POST['ajax']))
  {
    require_once('../../api/site.init.php');
  }
  
  // include ReCaptcha library
  require_once(LIB_ROOT . 'lib/recaptchalib.php');

  // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
  $lang = "en";
  
?>
<div id="login-alert" class="alert alert-danger center hidden" role="alert"></div>
            
<form id="register-form" class="form" action="/api/user/register" method="POST">
  <div class="row"> 
    <div class="col-sm-12 col-md-6 margin-bottom">
      <div class="validate-group">
        <label for="name">Name</label>
        <input value="" name="name" id="name" class="form-control validate" type="text" placeholder="..." required>
      </div><!-- .validate-group -->
    </div><!-- .col -->
  </div><!-- .row -->

  <div class="row"> 
    <div class="col-sm-12 col-md-6 margin-bottom">
      <div class="validate-group">
        <label for="email">Email Address</label>
        <input value="" name="email" id="email" class="form-control validate" type="email" placeholder="..." required>
        <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="fa ion-load-c fa-spin form-control-feedback" aria-hidden="true"></span>
      </div><!-- .validate-group -->
    </div><!-- .col -->

    <div class="col-sm-12 col-md-6 margin-bottom">
      <div class="validate-group">
        <label for="confirm-email">Confirm Email Address</label>
        <input value="" name="confirm_email" id="confirm-email" class="form-control validate" type="email" autocomplete="off" placeholder="..." required>
        <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="fa ion-load-c fa-spin form-control-feedback" aria-hidden="true"></span>
      </div><!-- .validate-group -->
    </div><!-- .col -->
  </div><!-- .row -->

  <div class="row"> 
    <div class="col-sm-12 col-md-6 margin-bottom">
      <div class="validate-group">
        <label for="username">Username</label>
        <input value="" name="username" id="username" class="form-control validate" type="text" placeholder="..." required>
        <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="fa ion-load-c fa-spin form-control-feedback" aria-hidden="true"></span>
      </div><!-- .validate-group -->
    </div><!-- .col -->
  </div><!-- .row -->

  <div class="row"> 
    <div class="col-sm-12 col-md-6 margin-bottom">
      <div class="validate-group">
      <label for="password">Password</label>
      <span class='strength-indicator pull-right' data-strength='-2'></span>
      <input value="" name="password" id="password" class="form-control validate" data-strength='-1' type="password" placeholder="..." required>
      </div><!-- .validate-group -->
    </div><!-- .col -->

    <div class="col-sm-12 col-md-6 margin-bottom">
      <div class="validate-group">
        <label for="confirm-password">Confirm Password</label>
        <input value="" name="confirm_password" id="confirm-password" class="form-control validate" type="password" placeholder="..." required>
        <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
      </div><!-- .validate-group -->
    </div><!-- .col -->
  </div><!-- .row -->
  
  <div id='password-warning' class='row hidden'>
  	<div class='col-sm-12'>
  		<div class='alert alert-danger small'>
  			Your password must be at least 6 characters long. It must contain at least 1 number or special character (! % & @ # $ ^ * ? _ ~)
  		</div>
  	</div>
  </div>

  <div class="row margin-bottom"> 
    <div class="col-sm-12">
      <div class="g-recaptcha inline" data-sitekey="<?php echo RECAPTCHA_PUBLIC;?>"></div>
      <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang;?>">
      </script>
    </div><!-- .col -->
  </div><!-- .row -->

  <div class="row margin-top"> 
    <div class="col-sm-12">
      <div class="validate-group">
        <div class="terms alert alert-info small">
          By Signing Up, you agree to the <u><a href='/policies/terms' target='_blank'>Terms & Conditions</a></u> and <u><a href='/policies/privacy' target='_blank'>Privacy Policy</a></u>, including <u><a href='/policies/cookies' target='_blank'>Cookie Use</a></u>.
        </div>
      </div><!-- .validate-group -->
    </div><!-- .col -->
  </div><!-- .row -->
  
  <div class="row align-center"> 
    <div class="col-sm-12 align-center">
      <input type="hidden" id="FORM_TOKEN" name="FORM_TOKEN" value="<?php echo $sc->common->generateFormToken('REGISTER'); ?>" />
      <button id='register-button' type="submit" class="block button button-primary<?php echo ($sc->vars->pageType == 'sign-up') ? ' button-large' : ''; ?>">Sign Up</button>
    </div><!-- .col -->
  </div><!-- .row -->
</form>