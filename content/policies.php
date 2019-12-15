<?php

  require_once('../api/site.init.php');
  require_once('helpers/immediate.php'); 
  
?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  
  <?php include('helpers/meta.php'); ?>

  <meta name="description" content="Snow Capture's Cookie Policy" />
  <meta name="keywords" content="<?php echo ucwords($sc->vars->pageType); ?>, Policies" />
  
  <title><?php echo ucwords($sc->vars->pageType); ?> | Policies | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main>
  <section class="posh start end">
    <div class="container">
      <h1>
        <?php echo ucwords($sc->vars->pageType); ?>
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
    </div><!-- .container -->
  </section><!-- .posh -->

    
  <section class="posh start end normal-content to-left white">
    <div class="container"> 
			<?php 
			
			 switch (strtolower($sc->vars->pageType)) 
			 {
			   case 'cookies':
			     include_once 'policies/cookies.php';
			     break;

		     case 'privacy':
		       include_once 'policies/privacy.php';
		       break;

	       default:
	         include_once 'policies/terms.php';
	         break;
			 }
			
			?>
    </div><!-- .container -->
  </section><!-- .posh -->
</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
