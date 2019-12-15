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

  <meta name="description" content="About Snow Capture" />
  <meta name="keywords" content="Snow Capture, About, Donate, Stay Updated" />
  
  <title>About Us | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<main id="style-page">
  <section class="style-section glory background text-center">
		<div class="inner-section">
			<h1>About Us</h1>
			<h2>Snow Capture is a small project created by <a href="http://www.iamrobert.co.uk" target="_blank">Robert Holden</a></h2>
			
			<a href="#" class="button button-large button-white" data-scroll="#about-us" data-speed="1000">
				Read More <i class="fa fa-chevron-down icon-right"></i>
			</a>
		</div><!-- .inner-section -->
		
		<div class="clear"></div>
	</section><!-- .glory -->
  
  <section id="about-us" class="posh start end white normal-content to-left restricted">
    <div class="container">    
      <h2>
        Intro
      </h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
    
      <p>
        Welcome to Snow Capture!
      </p>
      
      <br />
      
      <p>
        Snow Capture is a small project that I <span class="small">(<a href="https://www.twitter.com/RobHolden">@RobHolden</a>)</span> 
        have been working on since December 2014. It is essentially a high quality library of <strong>snow</strong> related pictures 
        from around the world captured by you. The images go through a selection process where I choose which pictures will be displayed to the public.
      </p>
      
      <br />
      
      <p> 
        I work full-time as an Application Developer, so I try and work on Snow Capture as much as I can in my spare time,
        which can be very limited at times! 
      </p>
      
      <br />
      
      <p>
        <a class="button button-primary see-through margin-right-xs" href='/how-it-works'>Click here</a> to find out more about the process of Snow Capture 
        <br /> 
      </p>
    </div><!-- .container -->
  </section><!-- .posh -->
  
  
  <section class="posh start end normal-content to-left restricted">
    <div class="container">
      <h2>
        The Team
      </h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <p>
        Well, Snow Capture is just a one man band. Designed & built by myself <a href="https://www.twitter.com/RobHolden">@RobHolden</a>. 
        It is a project I have been working on behind closed doors.
        I love doing Front-End and Back-End development and this is the result of pure boredom & freetime.

        <br />
        <br />

        I shall post all my thoughts and upcoming plans over on my <a href="/stay-updated">Stay Updated</a> page.
      </p>
    </div><!-- .container -->
  </section><!-- .posh -->
  
  
  <section class="posh start end normal-content white to-left restricted">
    <div class="container">
      <h2>
        The Data
      </h2>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <p>
        Well to put it simply... I do nothing with it. Your images are all stored on a server and is untouched and will remain so. I will not own any rights to your images what so ever, all I do is decide whether I want them on my site!
        
        <br /><br />
        
        If this changes, I will of course let you know. 
      </p>
    </div><!-- .container -->
  </section><!-- .posh -->
  
  
  <section class="posh start end normal-content to-left restricted">
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
  

</main>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
