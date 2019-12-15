<?php

  require_once('../api/site.init.php');

  if($sc->session->exists())
  {
    $sc->common->goHome();
  }

  $allow_reset = false;
  if(!empty($_GET['token']))
  {
    $token = $_GET['token'];    
    $user = new User();
    $user->getByResetToken($token);

    if($user->exists())
    {
      $allow_reset = true;
    }
  }

?>

<?php require_once('helpers/immediate.php'); ?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  
  <?php include('helpers/meta.php'); ?>

  <meta name="description" content="Forgot password Snow Capture account" />
  <meta name="keywords" content="Forgot Password, Snow Capture" />
   
  <title>Forgot Password | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main>

<?php if(!$allow_reset): ?>

  <section class="posh start end">
    <div class="container">
      <h1>
        Forgot Password
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <br />
      

			<form id="forgotten-form" class="form small-form" action="/api/user/forgotten_password" method="POST">
        <div class="row"> 
          <div class="col-sm-12">
            <div class="validate-group margin-bottom">
              <input name="email" id="forgotten-email" class="validate form-control padding" type="email" placeholder="Enter your email here" required />
            </div><!-- .validate-group -->

            <button type="submit" class="button button-primary button-large block">
              Reset Password
            </button>
          </div><!-- .col -->
        </div><!-- .row -->
      </form>
      
      <br />
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <br />

      <strong>Please be sure to check your junk e-mail just in case</strong>
    </div><!-- .container -->
  </section><!-- .posh -->

<?php else: ?>

<section class="posh start end">
    <div class="container">
      <h2>
        Reset Password
      </h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>  
      
			<form id="reset-form" class="form small-form text-left" action="/api/user/reset_password" method="POST">
        <div class="row"> 
          <div class="col-sm-12">
            <input name="token" id="token" type="hidden" value="<?php echo $_GET['token']; ?>" />
            <div class="validate-group margin-bottom">
              <label for="password">Password</label>
              <span class='strength-indicator pull-right' data-strength='-2'></span>
              <input value="" name="password" id="password" class="form-control validate" data-strength='-1' type="password" placeholder="Enter a new password" required>
            </div><!-- .validate-group -->
  
            <div id='password-warning' class='row hidden margin-bottom'>
            	<div class='col-sm-12'>
            		<div class='alert alert-danger small'>
            			Your password must be at least 6 characters long. It must contain at least 1 number or special character (! % & @ # $ ^ * ? _ ~)
            		</div>
            	</div>
            </div>

            <div class="validate-group margin-bottom">
              <label for="confirm-password">Confirm Password</label>
              <input value="" name="confirm_password" id="confirm-password" class="form-control validate" type="password" placeholder="Confirm your new password" required>
              <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        			<span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
            </div><!-- .validate-group -->

            <button type="submit" class="button button-primary button-large block">
              Reset Password
            </button>
          </div><!-- .col -->
        </div><!-- .row -->
      </form>
    </div><!-- .container -->
  </section><!-- .posh -->

<?php endif; ?>
</main>


<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>
<script>
</script>

</body>
</html>
