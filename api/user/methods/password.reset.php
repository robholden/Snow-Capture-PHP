<?php

// Get post values we need
$_token = $api->checkPost('token');
$_password = $api->checkPost('password');
$_cpassword = $api->checkPost('confirm_password');


// Validate token
if (! $_token)
{
	$api->data = array('error' => 'Could not find token');
	$api->leave();
}

// Validate password again
if (! $_password)
{
	$api->data = array('error' => 'Please enter a password');
	$api->leave();
}

// Validate confirm password 
if (! $_cpassword)
{
	$api->data = array('error' => 'Please confirm your password');
	$api->leave();
}

// Validate confirm password and password are equal
if ($_cpassword != $_password)
{
	$api->data = array('error' => 'Passwords do not match');
	$api->leave();
}

// Get user by their token
$_user = new User();
$_user->getByResetToken($_token);

// Make sure they exist
if (! $_user->exists())
{
	$api->data = array('error' => 'Invalid token');
	$api->leave();
}

// Make sure they aren't locked out
if ($_user->isLockedOut())
{
	$api->data = array('error' => 'Your account is locked out, please try again later');
	$api->leave();
}

// Now we can reset their password :)
if($_user->resetPassword($_password, true))
{
	$api->data = array('success' => 'Your password has been reset :)');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}