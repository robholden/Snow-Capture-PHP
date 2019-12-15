<?php

// Get post vars
$_token = $api->checkPost('FORM_TOKEN');
$_username = $api->checkPost('username');
$_password = $api->checkPost('password');
$_msg = 'Sorry, wrong username or password';

// Validate token
if (! $_token)
{
	$api->data = array('error' => 'Invalid request. You are using an insecure page');
	$api->leave();
}

if (! ($_token == (new Common)->getFormToken('LOGIN')))
{
	$api->data = array('error' => 'Something went wrong :/');	
	$api->leave();
}

// Validate username
if (! $_username)
{
	$api->data = array('error' => 'Please enter your username');
	$api->leave();
}

// Validate password
if (! $_password)
{
	$api->data = array('error' => 'Please enter your password');
	$api->leave();
}

// Check user
$_user = new User(strtolower($_username));

// Validate user
if (! $_user->exists())
{
	$api->data = array('error' => $_msg);
	$api->leave();
}

if ($_user->isDisabled())
{
	$api->data = array('error' => 'Sorry, your account has been disabled');
	$api->leave();
}

if (! $_user->exists())
{
	$api->data = array('error' => $_msg);
	$api->leave();
}

$_now = date('Y-m-d H:i:s');
$_then = $_user->lastAttemptedDate;
$_diff = floor((abs(strtotime($_now)) - strtotime($_then)) / 3600);
$_diffmins = floor((abs(strtotime($_now)) - strtotime($_then)) / 60);
$_lockedout = ($_user->attempts >= MAX_LOGIN_ATTEMPTS && ($_diff < LOCKED_HOURS));

// Are they locked out?
if ($_lockedout)
{
	$_timetowait = ($_diffmins > 60) ? ((LOCKED_HOURS * 60) - $_diffmins) . ' minute' : (LOCKED_HOURS - $_diff) . ' hour';
	$_timetowait .= ($_timetowait == 1) ? '' : 's';
	
	$api->data = array('error' => 'You are locked out (' . $_timetowait . ')');
	$api->leave();
}

// Check password
$_encpassword = (new Session)->saltData($_user, $_password);
if (! ($_user->password() == $_encpassword))
{
	// Add attempt
	if ($_user->attempts < MAX_LOGIN_ATTEMPTS)
	{
		$_user->attempts += 1;
	}
	
	$_user->lastAttemptedDate = $_now;
	$_user->append();
	
	$api->data = array('error' => $_msg);
	$api->leave();
}

// Reset attempts
$_user->attempts = 0;
$_user->lastAttemptedDate = $_now;

// Create session
$_remember = $api->checkPost('remembered');
$_guid = $api->checkPost('guid');
$_guid = (! $_guid) ? '' : $_guid;
$_user->createSession(($_remember !== false), $_guid);

if ($_user->append())
{
	$api->data = array('success' => 'Welcome back, ' . $_user->displayName);
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}