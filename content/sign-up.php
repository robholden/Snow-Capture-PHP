<?php

  require_once('../api/site.init.php');

  if($sc->session->exists())
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

  <meta name="description" content="Sign Up to Snow Capture" />
  <meta name="keywords" content="Sign Up, Snow Capture" />
   
  <title>Sign Up | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 
 
</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main>
  <section id="register" class="posh start"><!-- .end -->
    <div class="container">
      <h1>
        Start Uploading
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <br />
      
      <p>
        <a href="/about-us" class="button button-primary button-large">About Us</a>
      </p>
      
      <br />
    </div><!-- .container -->
  </section><!-- .posh -->

  


  <section id="register" class="posh sub-white">
    <div class="container align-left">
      <?php include('account/register.php'); ?>     
    </div><!-- .container -->
  </section><!-- .posh -->
  
  <section class="text-center margin-bottom">
    <div class="container small">
      Already registered? 
      <a href="/sign-in">
        Sign in.
      </a>
    </div><!-- .container -->
  </section><!-- .posh -->
</main>



<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
