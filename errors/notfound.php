<?php require_once('../api/site.init.php'); ?>
<?php require_once('../content/helpers/immediate.php'); ?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  
  <?php include('../content/helpers/meta.php'); ?>

  <meta name="description" content="" />
  <meta name="keywords" content="" />
  
  <title><?php echo SITE_NAME; ?> | Page Not Found :'(</title>
  
  <?php include('../content/helpers/css.php'); ?> 
  <?php include('../content/helpers/js.php'); ?> 

</head> 

<?php include('../content/helpers/body-start.php'); ?>

<?php include('../content/helpers/header.php'); ?>

<main>
  <section class="posh start end white">
    <div class="container">
      <h1>
        Page Not Found
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <br />
      <a href="/" class="button button-primary">
        Home <i class="fa fa-home icon-right"></i>
      </a>
    </div><!-- .container -->
  </section><!-- .posh -->
</main>

<?php include('../content/helpers/footer.php'); ?>

<?php include('../content/helpers/end-js.php'); ?>

</body>
</html>
