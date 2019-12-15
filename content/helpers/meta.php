  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    

<?php 

  //$no = $sc->session->exists() || $sc->common->siteMode() < 2 ? 'no' : '';
  $no = '';

?>

  <meta name="robots" content="<?php echo $no; ?>index, <?php echo $no; ?>follow" />

  <?php echo $sc->common->isMobile ? '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />' : ''; ?>
    
  <link rel="shortcut icon" type="image/ico" href="/template/images/snowflake.png" />

  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/template/images/snowflake_black.png" />

  <meta name="msapplication-starturl" content="https://www.snowcapture.com" />
  <meta name="application-name" content="Snow Capture" />
  <meta name="msapplication-tooltip" content="Snow Capture" />
  <meta name="msapplication-navbutton-color" content="#00bcf4" />
  <meta name="msapplication-TileColor" content="#00bcf4" />
  <meta name="msapplication-TileImage" content="/template/images/snowflake_black.png" />
  <meta name="msapplication-square70x70logo" content="/template/images/snowflake_black.png" />
  <meta name="msapplication-square150x150logo" content="/template/images/snowflake_black.png" />
  <meta name="msapplication-wide310x150logo" content="/template/images/snowflake_black.png" />
  <meta name="msapplication-square310x310logo" content="/template/images/snowflake_black.png" />

  <!-- Open Graph Protocol -->
  <meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID; ?>" />
  <meta property="og:url" content="<?php echo $sc->common->currentURL(); ?>" />
  <meta property="og:site_name" content="<?php echo SITE_NAME; ?>" />
