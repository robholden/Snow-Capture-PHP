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

  <meta name="description" content="About Snow Capture" />
  <meta name="keywords" content="Snow Capture, How It Works" />
  
  <title>How It Works | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main id="style-page">
  <section class="style-section glory background text-center">
		<div class="inner-section">
			<h1>How It Works</h1>
			<h2>A simple guide on the process of Snow Capture</h2>
			
			<a href="#" class="button button-large button-white" data-scroll="#guide" data-speed="1000">
				Go to guide <i class="fa fa-chevron-down icon-right"></i>
			</a>
		</div><!-- .inner-section -->
		
		<div class="clear"></div>
	</section><!-- .glory -->
	
	
  
  <section id="guide" class="posh start end normal-content to-left white">
    <div class="container">    
			<h2>Step 1. Uploading</h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <p>
      	Now, you can't just upload any old photo - it must meet our criteria:
      </p>
      <ul>
      	<li>Must be <strong>snow</strong> related - if there's no snow, it will be rejected</li>
      	<li>You are allowed to store <?php echo UPLOAD_LIMIT; ?> images at any one time.</li>
      	<li>You can only upload a maximum of <?php echo DRAFT_LIMIT; ?> images at any one time.</li>
				<li>Your image must be in <?php echo ACCEPT_FILE_TYPES ?> formats <strong>only</strong></li>
				<li>Your image must not be larger than <?php echo (MAX_UPLOAD_SIZE / 100000000); ?>MB</li>
				<li>Each image must be unique to the others</li>
      </ul>
    </div><!-- .container -->
  </section><!-- .posh -->
        
 	<section class="posh start end normal-content to-left">
    <div class="container">    
      <h2>Step 2. Draft</h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <p>Once your image has been uploaded they are placed in your "drafts".</p>
			<ul>
				<li>Your image can only be seen by you</li>
				<li>You can add information to all your images before publishing</li>
				<li>Crop a thumbnail by dragging over the image (except on mobile)</li>
				<li>If your image contains GEO data, you have the option to remove it</li>
			</ul>
    </div><!-- .container -->
  </section><!-- .posh -->
        
 	<section class="posh start end normal-content to-left white">
    <div class="container"> 
			<h2>Step 3. Processing</h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
			<p>Once you "publish" your image, it will be placed into "processing".</p>
			<ul>
				<li>A moderator will determine whether your image will be published</li>
				<li>You will receive a notification on their decision</li>
				<li>If rejected, you will be given a reason why</li>
				<li>You can delete/modify/private your image during processing</li>
			</ul>
    </div><!-- .container -->
  </section><!-- .posh -->
          
 	<section class="posh start end normal-content to-left">
    <div class="container"> 
			<h2>Step 4. That's All Folks</h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
			<p>That's pretty much it for the uploading. If your image is accepted it will be available to see by everyone (unless you private/delete it)</p>
    </div><!-- .container -->
  </section><!-- .posh -->
  
  <section class="posh start end normal-content white to-left">
    <div class="container">
      <h2>
        Any Questions
      </h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
          
      <p>
        Just ping an email over to <a href="mailto:<?php echo $sc->validate->antispambot('contact@snowcapture.com'); ?>">contact@snowcapture.com</a> or tweet <a href="https://www.twitter.com/TheSnowCapture" class="inline">@TheSnowCapture</a> and I'll try and get back to you!
      </p>
    </div><!-- .container -->
  </section><!-- .posh -->
  
  
  <section class="style-section process with-padding hidden">
		<div class="container">
			<h2 class="text-center">It's a simple concept</h2>
			
			<div class="row process-item">			
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
				
				<div class="col-sm-2 text-center">
					<div class="circle-number one">1.</div>
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5">
					<div class="process-text">
						<h3>Upload</h3>
						<p>You may upload <?php echo DRAFT_LIMIT; ?> pictures at any one time &amp; maximum of <?php echo UPLOAD_LIMIT; ?> pictures overall</p>
					</div>
				</div><!-- .col-sm-5 -->
			</div><!-- .row -->
			
			<div class="row">				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
				
				<div class="col-sm-2 text-center">
					<div class="process-separator">
    				<span></span><br /><span></span><br /><span></span>
    			</div><!-- .process-separator -->
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
			</div><!-- .row -->
			
			
			
			
			
			
			<div class="row process-item">	
				<div class="col-sm-5 text-right">
					<div class="process-text">
						<h3>Drafts</h3>
						<p>Once you have uploaded a picture, you will be able to preview it. Here you will be able to add all the information about the picture (name, location, date etc.)</p>
					</div>
				</div><!-- .col-sm-5 -->	
						
				<div class="col-sm-2 text-center">
					<div class="circle-number two">2.</div>
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->	
			</div><!-- .row -->
			
			<div class="row">				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
				
				<div class="col-sm-2 text-center">
					<div class="process-separator">
    				<span></span><br /><span></span><br /><span></span>
    			</div><!-- .process-separator -->
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
			</div><!-- .row -->
			
			
			
			
			
			<div class="row process-item">			
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
				
				<div class="col-sm-2 text-center">
					<div class="circle-number three">3.</div>
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5">
					<div class="process-text">
						<h3>Processing</h3>
						<p>When you publish your picture it is sent of for review</p>
						<p>Click <a href="/how-it-works">here</a> to see the guidelines &amp; hints on how to get your pictures approved</p>
					</div>
				</div><!-- .col-sm-5 -->
			</div><!-- .row -->
			
			<div class="row">				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
				
				<div class="col-sm-2 text-center">
					<div class="process-separator">
    				<span></span><br /><span></span><br /><span></span>
    			</div><!-- .process-separator -->
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->
			</div><!-- .row -->
			
			
			
			
			
			<div class="row process-item">	
				<div class="col-sm-5 text-right">
					<div class="process-text">
						<h3>Published</h3>
						<p>	You will be notified if your picture has been approve or rejected.</p>
						<p> Oh, and don't worry - you keep all rights to the pictures you upload!</p>
					</div>
				</div><!-- .col-sm-5 -->	
						
				<div class="col-sm-2 text-center">
					<div class="circle-number four">4.</div>
				</div><!-- .col-sm-2 -->
				
				<div class="col-sm-5"></div><!-- .col-sm-5 -->	
			</div><!-- .row -->
			
			<div class="padding text-center">
				<a href="/sign-up" class="button button-large button-primary see-through">
					Sign Up
					<i class="fa fa-external-link icon-right"></i>
				</a>
			</div>
		</div><!-- .container -->
	</section><!-- .process -->

</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
