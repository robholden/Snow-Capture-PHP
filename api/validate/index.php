<?php

/**
 * 
 * 
 * VALIDATE
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
	case 'email':
		
		// Ensure email is set
		$api->checkPost('email');

		// Do the check
		$api->data = array ('exists' => $sc->validate->emailExists($_POST['email']));
		
		break;
		
	case 'resort':
	
		// Ensure email is set
		$api->checkPost('resort');

		// Do the check
		$api->data = array ('exists' => $sc->validate->resortExists($_POST['resort']));
		
		break;
	
	case 'username':
		
		// Ensure email is set
		$api->checkPost('username');

		// Do the check
		$api->data = array ('exists' => $sc->validate->usernameExists($_POST['username']));
		
		break;
	
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();