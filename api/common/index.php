<?php

/**
 * 
 * 
 * COMMON
 * 
 * 
 **/

// API init file
require_once '../api.init.php';

// Lets do this
// Point to correct method, where the api data property will be set :)
// For logical methods, separate into method/*.php file

// General methods
switch ($api->method)
{
	case 'geocoder':
		require_once 'methods/geocoder.php';
		break;
		
	case 'locations':
		require_once 'methods/locations.php';
		break;
		
	case 'render':
		require_once 'methods/render.php';
		break;			
		
	case 'resort_request':
			
		// Must be logged in
		$api->ensureWebAccess(LEVEL_USER);
			
		// Get resort
		$_resort = $api->checkPost('resort');
			
		// Validate
		if (!$_resort) 
		{
			$api->data = array('error' => 'Please provide a resort name');
		}
		
		// Make sure it doesn't exist
		if ($sc->validate->resortExists($_resort))
		{
			$api->data = array('error' => 'This resort has already been added');
		}
			
		// Send to admin
		else if($api->user()->addResortRequest($_resort))
		{
			$api->data = array('success' => 'Request submitted successfully');
		}
			
		else
		{
			$api->data = array('error' => METHOD_ERROR);
		}
			
		break;
		
	case 'tags':
		require_once 'methods/tags.php';
	  break;
		
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();