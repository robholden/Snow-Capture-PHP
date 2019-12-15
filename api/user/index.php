<?php

/**
 * 
 * 
 * USER
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
	case 'forgotten_password':
		require_once 'methods/password.forgotten.php';
		break;
	
	case 'login':
		require_once 'methods/login.php';
		break;
	
	case 'register':
		require_once 'methods/register.php';
		break;
		
	case 'reset_password':
		require_once 'methods/password.reset.php';
		break;

	case 'accept_process':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/accept.process.php';
		break;
			
	case 'block':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/block.php';
		break;
		
	case 'confirm':
	
		// Must be logged in
		$api->ensureWebAccess(LEVEL_USER); 
		
		// Send email
		if ($api->user()->confirmEmail())
		{
			$api->data = array('success' => 'A confirmation has been sent');
		}
		
		else
		{
			$api->data = array('error' => METHOD_ERROR);
		}
		
		break;
		
	case 'delete':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/delete.php';
		break;
		
	case 'report':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/report.php';
		break;
		
	case 'unlock':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/unlock.php';
		break;
	
	case 'update_general':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/update.general.php';
		break;
	
	case 'update_image':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/update.image.php';
		break;
	
	case 'update_options':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/update.options.php';
		break;
	
	case 'update_security':
		$api->ensureWebAccess(LEVEL_USER); // Must be logged in
		require_once 'methods/update.security.php';
		break;
		
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();