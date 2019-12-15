<?php

/*
 * Block iframes from accessing site
 */
header('X-Frame-Options: DENY');

/*
 * Start session
 */
if (! isset($_SESSION))
{
  session_start();
}

/*
 * Load config
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/_code/configs/config.php';

/*
 * Load classes
 */
spl_autoload_register(function($class)
{
  $_path = LIB_ROOT . 'classes/helpers/class.' . strtolower($class) . '.php';

  if (file_exists($_path))
  {
    include_once $_path;
  }

  else
  {
    $_path = LIB_ROOT . 'classes/class.' . strtolower($class) . '.php';
    if (file_exists($_path))
    {
      include_once $_path;
    }
  }
});

// Include non-declared
include LIB_ROOT . 'classes/helpers/class.structs.php';
include LIB_ROOT . 'classes/helpers/class.dataobject.php';

// Include interfaces
include LIB_ROOT . 'interfaces/interfaces.php';

/*
 * Error reporting
 */
switch (DEVELOPMENT)
{
  case 4:
    error_reporting(0);
    ini_set('display_errors', 0);
    break;

  default:
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    break;
}
/*
 * Is there a session?
 */
$_has_sess = isset($_SESSION);

/*
 * Connect to dv
 */
$db = null;
if ($_has_sess)
{
  if (isset($_SESSION['DB']))
  {
    try {
      $srdb = (new Functions())->decrypt($_SESSION['DB'], DEFAULT_IV);
      $db = unserialize($srdb);
    } catch (Exception $e) { $db = null; }
  }
}

// Ensure object has been found
if (! is_object($db))
{
  switch (DEVELOPMENT)
  {
    case 1:
      $config = new config(DB_HOST_LOCAL, DB_USER_LOCAL, DB_PASS_LOCAL, DB_NAME_LOCAL);
      break;

    case 2:
      $config = new config(DB_HOST_BETA, DB_USER_BETA, DB_PASS_BETA, DB_NAME_BETA);
      break;

    default:
      $config = new config(DB_HOST_LIVE, DB_USER_LIVE, DB_PASS_LIVE, DB_NAME_LIVE);
      break;
  }

  $db = new db($config, 'utf8');

  if ($_has_sess) { $_SESSION['DB'] = (new Functions())->encrypt(serialize($db), DEFAULT_IV); }
}

// Validate connection
if (! $db->openConnection())
{
  echo "Could not connect to the database :'(";
  if ($_has_sess) { unset($_SESSION['DB']); }
  exit();
}

/*
 * Set defaults
 */
date_default_timezone_set('Europe/London');

$sc = new SnowCapture($db);
$sc->setup();

// Require html render class
require_once WEB_ROOT . 'content/_code/class.htmlrender.php';

?>