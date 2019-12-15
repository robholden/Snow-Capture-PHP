<?php

require_once ('../api/site.init.php');
require_once ('helpers/immediate.php');

?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  
  <?php include('helpers/meta.php'); ?>

	<meta name="description" content="Search the globe for images at a location" />
	<meta name="keywords" content="The Map" />

	<title>The Map | <?php echo SITE_NAME; ?></title>

  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_BROWSER_KEY; ?>" type="text/javascript"></script>
	
</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main id="style-page">
	<?php 
	   
    if (isset($_GET['livefeed'], $_GET['latitude'], $_GET['longitude']))
    {
      include_once 'map/livefeed.php';
    }
    
    else 
    {
      include_once 'map/globe.php';
    }
	
	?>
</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
