<?php

/**
 * 
 * 
 * IMAGE
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

// General methods
switch ($api->method)
{
	case 'get':
		break;
		
	case 'by_geo':
	  require_once 'methods/by.geo.php';
		break;
		
	case 'by_region':
	  require_once 'methods/by.region.php';
		break;
		
	case 'delete':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/delete.php';
		break;
	
	case 'delete_geo':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/delete.geo.php';
		break;
		
	case 'like':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/like.php';
		break;
		
	case 'private':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/private.php';
		break;
		
	case 'rate':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/rate.php';
		break;
	
	case 'report':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/report.php';
		break;
		
	case 'update':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/update.php';
		break;
			
	case 'upload':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/upload.php';
		break;
		
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();