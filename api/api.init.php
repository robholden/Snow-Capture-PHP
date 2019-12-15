<?php

/**
 * API CONFIG
 */

require_once 'global.init.php';

/*
 * Redirects
 */
$_maintenance = file_get_contents(WEB_ROOT . 'things/.maintenance.cfg');

if ($_maintenance == 'true')
{
  echo 'Site under maintenance';
  exit();
}

if (isset($_SESSION['banned']))
{
  echo "You're banned :/";
  exit();
}

/*
 * API setup
 */

// Get global api
$api = new APIController();

// Ensure method is post & set
$api->ensureMethod();