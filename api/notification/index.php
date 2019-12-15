<?php

/**
 * 
 * 
 * NOTIFICATION
 * 
 * 
 **/

// API init file
require_once '../api.init.php';

// Only allow post call
$api->ensurePost();

// Ensure user is logged in (via web)
$api->ensureWebAccess(LEVEL_USER);

// Lets do this
// Point to correct method, where the api data property will be set :)
// For logical methods, separate into method/*.php file
switch ($api->method)
{
	case 'get':
		require_once 'methods/get.php';
		break;
	
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();