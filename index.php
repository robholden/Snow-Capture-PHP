
<?php

  require_once('api/site.init.php');

?>
<?php require_once('content/helpers/immediate.php'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>

  <?php include('content/helpers/meta.php'); ?>

  <meta property="og:title" content="<?php echo SITE_NAME; ?>">
  <meta property="og:type" content="website">
  <meta property="og:description" content="<?php echo SITE_SLOGAN; ?>" />
  <meta property="og:image" content="<?php echo $sc->common->siteURL() . 'template/images/snow-capture.jpg'; ?>" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@TheSnowCapture" />
  <meta name="twitter:creator" content="@TheSnowCapture" />
  <meta name="twitter:title" content="<?php echo SITE_NAME; ?>">
  <meta name="twitter:description" content="<?php echo SITE_SLOGAN; ?>">
  <meta name="twitter:url" content="<?php echo $sc->common->siteURL(); ?>" />
  <meta name="twitter:image" content="<?php echo $sc->common->siteURL() . 'template/images/snow-capture.jpg'; ?>">

  <meta name="description" content="<?php echo SITE_SLOGAN; ?>" />
  <meta name="keywords" content="Snow Capture, Snow Media, Photography, Snow Images, Snow Holidays, Snow Adventure" />

  <title><?php echo SITE_NAME; ?></title>

  <?php include('content/helpers/css.php'); ?>
  <?php include('content/helpers/js.php'); ?>

</head>

<?php include('content/helpers/body-start.php'); ?>

<?php include('content/helpers/header.php'); ?>

<main id="style-page">
	<section class="style-section glory background explore">
		<div class="inner-section">
			<div class="inner-content">
  			<h1><?php echo SITE_NAME; ?></h1>

  			<h2 class="glory-sub"><?php echo SITE_SLOGAN; ?></h2>
  			<div class="clear"></div>
  			<span class="break-dot"></span>
        <span class="break-dot"></span>
        <span class="break-dot"></span>

        <div class="search-box margin-top-xl">
          <form id="hard-search-form" action="/search" method="GET">
            <input id="keyword" name="q" class="form-control" placeholder="Search for something..." type="text" />
            <button class="search-btn">
              <i class="fa fa-search"></i>
            </button>
          </form>
        </div>
  		</div><!-- .inner-content -->
		</div><!-- .inner-section -->

		<div class="clear"></div>
	</section><!-- .glory -->

	<section id="spotlight" class="style-section with-padding white spotlight-links with-shadow text-center">
		<div class="container">
			<h2>Trending</h2>
			<ul>
				<li>
					<a href="#" class="spotlight-link button button-large button-primary see-through" data-type="tag">
						Popular Tags
					</a>
				</li>

				<li>
					<a href="#" class="spotlight-link button button-large button-primary see-through active" data-type="featured">
						Featured
					</a>
				</li>

				<li>
					<a href="#" class="spotlight-link button button-large button-primary see-through" data-type="location">
						Popular Locations
					</a>
				</li>
			</ul>
		</div><!-- .container -->
	</section><!-- .spotlight-links -->

	<section id="spotlight-images" class="style-section spotlight">
		<?php include 'content/views/spotlight.php'; ?>
	</section><!-- .spotlight -->

	<section class="style-section white with-padding with-shadow">
		<div class="container text-center">
			<h2>A Random Selection</h2>
  		<a href="/search" class="button button-large button-primary see-through">
  			Explore
  			<i class='fa fa-search icon-right'></i>
  		</a>
		</div><!-- .container -->
	</section>

	<section id="random-images" class="style-section random-images">
		<?php include 'content/views/random-images.php'; ?>
	</section><!-- .random -->
</main>

<?php include('content/helpers/footer.php'); ?>
<?php include('content/helpers/end-js.php'); ?>

</body>
</html>
