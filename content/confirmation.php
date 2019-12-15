<?php

  require_once('../api/site.init.php');
  
  $success = false;
  if(!empty($_GET['token']))
  {
    $_user = (new User)->confirmUser($_GET['token']);
    
    // Send welcome email?
    if ($_user->exists())
    {
      $success = true;
      $_user->clearConfirmations();
      
      $emailer = new Email('Welcome');
      $emailer->user = $_user;
      $emailer->send();
      
      if ($sc->session->exists())
      {
        $sc->user->status = LEVEL_USER_CONFIRMED;
      }
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

  <title>Confirmation | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>


<?php include('helpers/header.php'); ?>

<main>

  <section class="posh start end">
    <div class="container">      
      
      <h1>
        Email Confirmation
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <br />     
      
  <?php 
    
    // IsSuccess
    if ($success):
    
  ?>
  
      <p class="green">Your email has been successfully confirmed!</p>
      <br />
      <a href="/" class="button button-primary margin-top"><i class="fa fa-home icon-left"></i> Home</a>
    
  
  <?php 
    
    // IsNotSuccess
    else:
    
  ?>
      
      <p class="red">You have an invalid confirmation token!</p>
      
  <?php 
    
    // End IsSuccess
    endif;
    
  ?>
    </div><!-- .container -->
  </section><!-- .posh -->
</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
