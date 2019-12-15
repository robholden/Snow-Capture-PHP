<?php

// Get post vars
$_password = $api->checkPost('password');
$_newpassword = $api->checkPost('new_password');
$_timeout = $api->checkPost('timeout');
$_passcode = $api->checkPost('passcode');
$_user = $api->user();

// Ensure password has been entered
if (! $_password)
{
	$api->data = array('error' => 'Password is incorrect');
	$api->leave();
}

// Validate password
if (! ($_user->password() === (new Session)->saltData($_user, $_password)))
{
	$api->data = array('error' => 'Password is incorrect');
	$api->leave();
}

// Track what to update
$_cantimeout = (strtolower($_timeout) == 'true');

// Update/set passcode?
if ($_cantimeout && (empty($_user->passcode()) && ! $_passcode))
{
	$api->data = array('error' => 'Please enter a passcode');
	$api->leave();
}

// Update password
if ($_newpassword !== false)
{
	$_user->appendPassword($_newpassword);
}

// Update passcode
$_user->canTimeout = $_cantimeout;
if ($_passcode !== false)
{
	$_user->appendPasscode($_passcode);
}

// Update
if ($_user->append())
{
	$api->data = array('success' => 'Security settings updated successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}