<?php

/**
 * WEBSITE CONFIG
 */

require_once 'global.init.php';

/*
 * Redirects
 */
$_maintenance = file_get_contents(WEB_ROOT . 'things/.maintenance.cfg');

if ($_maintenance == 'true')
{
  header('Location: /errors/offline');
  exit();
}

if (isset($_SESSION['banned']))
{
  header('Location: /tut-tut-tut');
  exit();
}

if (isset($_GET['logout']))
{
  $sc->session->logout();
}

if (isset($_POST['cookie']))
{
  if (setcookie('cookies_enabled', '1', (time() + (2592000 * 60)), '/', '', false, true))
  {
    header("Location:" . $_SERVER['PHP_SELF']);
  }
}

// Stop session manipulation
if (isset($_SESSION['HTTP_USER_AGENT'], $_SERVER['HTTP_USER_AGENT']))
{
  if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'] . AUTH))
  {
    if ($sc->session->exists())
    {
      // Write to session
      unset($_SESSION['HTTP_USER_AGENT']);
      $sc->session->logout();
    }
  }
}

else
{
  if (isset($_SERVER['HTTP_USER_AGENT']))
  {
    // Write to session
    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT'] . AUTH);
  }
}