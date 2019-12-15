<?php 

$_maintenance = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/things/.maintenance.cfg');
if ($_maintenance != 'true')
{
  header('Location: /');
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
  <meta http-equiv="refresh" content="15" />
  <link rel="shortcut icon" type="image/ico" href="/template/images/snowflake.png" />
  <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
  
  <title>Snow Capture</title>
  
  <?php include('../content/helpers/css.php'); ?> 

</head> 
<body class="mobile">

<main>
  <section class="posh start">
    <div class="container">
      <h1>
        Snow Capture
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      <br />
      
      <p>
      	We're currently working on a few things!
      </p>
    </div><!-- .container -->
  </section><!-- .posh -->
</main>

</body>
</html>
