<?php

// Get post vars
$_token = $api->checkPost('FORM_TOKEN');
$_passcode = $api->checkPost('passcode');

// Validate token
if (! $_token)
{
	$api->data = array('error' => 'Invalid request. You are using an insecure page');
	$api->leave();
}

if (! $_token == (new Common)->getFormToken('PASSCODE'))
{
	$api->data = array('error' => 'An error occurred. Please ensure cookies are enabled');
	$api->leave();
}

// Validate passcode
if (! $_passcode)
{
	$api->data = array('error' => 'Please enter your passcode');
	$api->leave();
}

// Let's see if they got it right
$_encpasscode = (new Session)->saltData($api->user(), $_passcode);
if (! ($api->user()->passcode() == $_encpasscode))
{
	$api->data = array('error' => 'Passcode incorrect, please try again');
	$api->leave();
}

// Untimeout user!
if ($api->session()->unTimeout())
{
	$api->data = array('success' => 'Welcome back... ' . $api->user()->displayName);
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}