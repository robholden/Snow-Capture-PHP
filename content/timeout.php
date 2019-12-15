<?php

  require_once('../api/site.init.php');
  
  // Must be logged in to access
  $sc->session->privacyCheck(1);
  
  if(!$sc->session->isTimedOut())
  {
    $sc->common->goHome();
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

  <meta name="description" content="Sign into Snow Capture" />
  <meta name="keywords" content="Sign In, Snow Capture" />
    
  <title>Session Timeout | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main>
  <section id="register" class="posh start">
    <div class="container">
      <h1>
        Session Timeout
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <p><code>You have been inactive for <?php echo (new HTMLRender)->timeoutString(); ?>.</code></p>
    </div><!-- .container -->
  </section><!-- .posh -->

  <section class="posh text-center sub-white">
    <div class="container text-left no-min">
      <form id="passcode-form" class="small-form" action="/api/user/unlock" method="POST">
        <div class="row">         
          <div class="col-sm-12 margin-bottom">      
            <label for="passcode">Please enter your passcode to continue.</label>
            <div class="custom-field validate-group">
              <i class="fa fa-lock icon-left"></i>
              <input name="passcode" id="passcode" class="validate numbers-only" type="password" value=" " placeholder="1234" autocomplete="off">
              <input type="hidden" id="PASSCODE_URL" value="<?php echo empty($_GET['url']) ? '' : $_GET['url']; ?>" />
              <input type="hidden" id="PASSCODE_FORM_TOKEN" name="FORM_TOKEN" value="<?php echo $sc->common->generateFormToken('PASSCODE'); ?>" />        
            </div><!-- .custom-field -->
          </div><!-- .col -->
        </div><!-- .row -->
      
        <div class="row align-center"> 
          <div class="col-sm-12 align-center">
            <button type="submit" class="button button-primary block margin-top">Unlock <i class="fa fa-lock icon-right"></i></button>
          </div><!-- .col -->
        </div><!-- .row -->
      </form>
            
    </div><!-- .container -->
  </section><!-- .posh -->
  
  <section class="text-center posh start end">
    <div class="container small">
      If you have forgotten your passcode, <a href="/sign-out">sign out</a> - then sign back in. This will reset your session, then go to settings -> security to change your passcode
      <br /><br />
      <em><strong>To turn off this feature, go to settings -> security and untick "Enable Timeout"</strong></em>    
    </div><!-- .container -->
  </section><!-- .posh -->
</main>


<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

<script>
</script>

</body>
</html>
