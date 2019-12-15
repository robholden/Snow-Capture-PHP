<?php

  require_once('../api/site.init.php');
  
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

	<meta name="description" content="Search for images" />
	<meta name="keywords" content="Search" />
	   
  <title>Search | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 
  
</head>  

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main id="style-page">
	<section class="style-section glory background half">
		<div class="inner-section">
			<div class="inner-content">
  			<h1>Search</h1>
  			<div class="clear"></div>
  			<span class="break-dot"></span>
        <span class="break-dot"></span>
        <span class="break-dot"></span>
  			
        <div class="search-box">
          <form id="hard-search-form" action="/search" method="GET" class="ajax-to-search">
            <input id="keyword" name="q" class="form-control" placeholder="Search for something..." type="text" value="<?php echo $sc->common->keyword(); ?>" />
            <button class="search-btn">
              <i class="fa fa-search"></i>
            </button>
          </form>
        </div>
  		</div><!-- .inner-content -->
		</div><!-- .inner-section -->
		
		<div class="clear"></div>
	</section><!-- .glory -->
	
  <?php include('views/image-display.php'); ?>     
</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
