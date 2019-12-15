<?php

/**
 * 
 * 
 * EMAIL
 * 
 * 
 **/

// API init file
require_once '../api.init.php';

// Only allow post call
$api->ensurePost();

// Lets do this
// Point to correct method, where the api data property will be set :)
// For logical methods, separate into method/*.php file
switch ($api->method)
{
	case '':
		require_once '...';
		break;
	
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();