<?php

  require_once('../api/site.init.php');

  $profile_user = $sc->common->user();
  if(! $profile_user->exists())
  {
    $sc->common->goNoWhere();
  }

  $pageType = $sc->vars->pageType;
  $is_self = $profile_user->id == $sc->user->id;
  
  if (isset($_GET['owner']))
  {
    if (! $is_self)
    {
      $sc->session->goToLogin();
    }
  }
  
  $is_profile = ($sc->vars->pageType == 'user');

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

	<?php 
	
    if ($is_profile):
	
	?>
  <meta property="og:title" content="<?php echo $profile_user->displayName; ?>">
  <meta property="og:type" content="profile">
  <meta property="og:image" content="<?php echo $sc->common->siteURL() . $profile_user->pictureCover; ?>" />
  
  <meta name="twitter:card" content="photo" />
  <meta name="twitter:site" content="@TheSnowCapture" />
  <meta name="twitter:title" content="<?php echo $profile_user->displayName; ?>" />
  <meta name="twitter:image" content="<?php echo $sc->common->siteURL() . $profile_user->pictureCover; ?>" />
  <meta name="twitter:url" content="<?php echo $sc->common->siteURL() . '/' . $profile_user->username; ?>" />

  <meta name="description" content="Snow Capture user: <?php echo $profile_user->displayName; ?>" />
  <meta name="keywords" content="Snow Capture, <?php echo $profile_user->displayName; ?>" />
  
  <?php 
  
    endif;
  
  ?>
  
  <title><?php echo ($is_profile ? $profile_user->displayName : ucwords($sc->vars->pageType)) . ' | ' . SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 
</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<?php include('profile/user-nav.php'); ?>  

<main>  
<?php 

if (! $sc->common->isMobile)
{
  echo '<div id="image-bookmark"></div>';
  
  $img_title = '';
  switch ($sc->vars->pageType)
  {
    case 'user':
      $img_title = '<i class="fa fa-star icon-left"></i> PUBLISHED';
      break;

    case 'processing':
      $img_title = '<i class="fa ion-load-c icon-left"></i> PROCESSING';
      break;

    case 'drafts':
      $img_title = '<i class="fa fa-edit icon-left"></i> DRAFTS';
      break;

    case 'privates':
      $img_title = '<i class="fa fa-lock icon-left"></i> PRIVATES';
      break;

    case 'likes':
      $img_title = '<i class="fa fa-heart icon-left"></i> LIKES';
      break;
  }

  echo '<h2 id="preload-title" class="image-header ' . $sc->vars->pageType . '">' . $img_title . '</h2>';
}

?>	
</main>

<?php include('views/image-display.php'); ?>     

<?php if (! $sc->common->isMobile): ?>
<script>
	$(document).ready(function() {
		var h = 60;
		$('#stick-user').sticky({
			stickOn: h,
			offset: h,
			className: 'stuck', 
			emptyClass: false
		});
	});
</script>
<?php endif; ?>

<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
