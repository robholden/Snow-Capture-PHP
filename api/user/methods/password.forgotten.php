<?php

// Get post values we need
$_email = $api->checkPost('email');

// Validate email
if (! $_email)
{
	$api->data = array('error' => 'Your email is required');
	$api->leave();
}

// Validate email again
if (! filter_var($_email, FILTER_VALIDATE_EMAIL))
{
	$api->data = array('error' => 'Please enter a valid email');
	$api->leave();
}

// Get user by their email
$_user = new User($_email);

// Make sure they exist
if (! $_user->exists())
{
	$api->data = array('error' => 'Please enter a valid email');
	$api->leave();
}

// Make sure they aren't locked out
if ($_user->isLockedOut())
{
	$api->data = array('error' => 'Your account is locked out, please try again later');
	$api->leave();
}

// Now we can send a reminder :)
// Always assume ok
$_user->forgottenPassword();
$api->data = array('success' => 'We have emailed instructions to you :)');