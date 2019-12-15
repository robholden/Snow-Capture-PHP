<?php

// Post vars we need
$_token = $api->checkPost('token');
$_password = $api->checkPost('password');

// Ensure token is set
if (! $_token)
{
	$api->data = array('error' => 'Insecure access');
	$api->leave();
}

// Make sure token is correct
if (! ($_token === hash('sha512', $api->user()->username . AUTH)))
{
	$api->data = array('error' => (new Security)->addToNaughtyList(1));
	$api->leave();
}

// Ensure password has been entered
if (! $_password)
{
	$api->data = array('error' => 'Password is incorrect');
	$api->leave();
}

// Validate password
if (! ($api->user()->password() === (new Session)->saltData($api->user(), $_password)))
{
	$api->data = array('error' => 'Password is incorrect');
	$api->leave();
}

if ($api->user()->isAdmin())
{
	$api->data = array('error' => 'Cannot delete an Admin');
	$api->leave();
}

// Delete user
if($api->user()->delete())
{
	$api->data = array('success' => 'Your account has been successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}