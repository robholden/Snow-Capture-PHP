<?php

  require_once('../api/site.init.php');
  require_once('_code/class.capture.php');
  
  // Set capture class
  $_image = $sc->common->image();
  $_user = $sc->user;
  $_capture = new Capture($_image, ($sc->session->exists() ? $_user : false));
  
  // Make sure it's valid
  if (! $_capture->canView())
  {
    $sc->common->goNoWhere();
  }

  // Create pagename
  $_page_title = $_image->title . ' | ' . $_image->username;

  // Build url
  $_url = $sc->common->siteURL() . '/capture/' . $_image->displayID;    

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

  <meta property="og:title" content="<?php echo $_image->title; ?>">
  <meta property="og:description" content="A picture uploaded by @<?php echo $_image->user->displayName; ?>" />
	<meta property="og:type" content="website">
  <meta property="og:image" content="<?php echo $sc->common->siteURL() . $_image->filePath; ?>" />
  
  <meta name="twitter:card" content="photo" />
  <meta name="twitter:site" content="@TheSnowCapture" />
  <meta name="twitter:title" content="<?php echo $_image->title; ?>" />
  <meta name="twitter:description" content="A picture uploaded by @<?php echo $_image->user->displayName; ?>" />
  <meta name="twitter:url" content="<?php echo $_url; ?>" />
  <meta name="twitter:image" content="<?php echo $sc->common->siteURL() . $_image->filePath; ?>" />
  
  <meta name="description" content="<?php echo $_image->title . ' by ' . $_image->username; ?>" />
  <meta name="keywords" content="Snow Capture<?php echo !empty($_image->resort) ? ', ' . $_image->resort : ''; ?>" />
  
  <title><?php echo $_page_title . ' | ' . SITE_NAME; ?> </title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 
  <?php echo $_image->hasGeo ? '<script src="https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_BROWSER_KEY . '" type="text/javascript"></script' : ''; ?>

</head>

<?php include('helpers/body-start.php'); ?>
<?php include('helpers/header.php'); ?>
<?php include_once 'capture/topbar.php'; ?>

<main>

<?php 

// Has this image been rejected?
// If so, let's show the reason, only if they're the author... of course.
if ($_image->deletedReason() !== false):

  echo $_capture->showDeleted();

// Image exists, let's show it
else:

?>

	<article id="capture-details">
		<?php echo $_capture->showImage(); ?>
		
		<?php if ($sc->common->isMobile): ?>
		<div class="mobile-action text-center">
			<?php echo $_capture->showLike(); ?>
			<?php echo $_capture->showRate(); ?>
		</div>
		
		<div class="text-center">
			<?php echo $_capture->showSpotlight(); ?>
			<?php echo $_capture->showReport(); ?>
			<?php echo $_capture->showSharing(); ?>
		</div>
		<?php endif; ?>
		
		<?php if ($_image->status == IMAGE_PUBLISHED): ?>
		<div id="capture-stat-bar" class="bg-white">
      <div class="container">
      	<div class="row">
      		<div class="col-sm-4 col-xs-12 <?php echo (! $sc->common->isMobile ? '' : 'text-center'); ?>">
      			<?php echo $_capture->showStats(); ?>
      		</div>
    			
    			<?php if (! $sc->common->isMobile): ?>
      		<div class="col-sm-4 col-xs-12 text-center">
      			<?php echo $_capture->showLike(); ?>
      			<?php echo $_capture->showRate(); ?>
      		</div>
      		
      		<div class="col-sm-4 col-xs-12 text-right">
      			<?php echo $_capture->showSpotlight(); ?>
      			<?php echo $_capture->showReport(); ?>
      			<?php echo $_capture->showSharing(); ?>
      		</div>
      		<?php endif; ?>
      	</div><!-- .row -->
      </div><!-- .container -->
    </div><!-- #capture-stat-bar -->
    <?php endif; ?>
    
		<h1 class="container text-center"><?php echo $_image->title; ?></h1>
				
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-xs-12">
					<?php echo $_capture->showAuthor(); ?>
					<?php echo $_capture->showTags(); ?>
				</div>
				
  			<div class="col-sm-8 col-xs-12">
  				<div id="image-meta" class="bg-white capture-padding">
    	  		<?php echo $_capture->showLocation(); ?>
    	  		<?php echo $_capture->showDates(); ?>
    	  		<?php echo $_capture->showActivity(); ?>
    	  		<?php echo $_capture->showAltitude(); ?>
    	  		<div class="clear"></div>
  	  		</div>
    		</div>
			</div>
  	  		
			<?php echo $_capture->showDescription(); ?>
			<?php echo $_capture->showNearby(); ?>
		</div><!-- .container -->
		
		<?php echo $_capture->showMap(); ?>
	</article><!-- #capture-details -->

<?php 

endif; // End image content
    
?>

</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

<script>
	$(document).ready(function() {
		if (! vars.mobile)
		{
  		var h	= 60;
  		var i	= $('#author-bar').outerHeight();
  
  		$('#top-bar').sticky({
  			stickOn: -(i + (h - i)),
  			offset: h,
  			className: 'stuck', 
  			emptyClass: false,
  			animate: 'slide',
  			animateSpeed: 200
  		});
  		
  		var mH = $('#image-meta').outerHeight();
  		var tH = $('#image-tags').outerHeight();
  		var cH = $('.capture-author').outerHeight();
  
  		var extra = (mH - (tH + cH)) - 15; 
			$('.capture-author .cover').css('height', (175 + extra) + 'px');
		}
	});
</script>

</body>
</html>
