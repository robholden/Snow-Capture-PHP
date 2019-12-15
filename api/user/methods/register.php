<?php

// Get recaptcha library
require_once LIB_ROOT . 'lib/recaptchalib.php';

// Get post vars
$_token = $api->checkPost('FORM_TOKEN');
$_name = $api->checkPost('name');
$_username = $api->checkPost('username');
$_password = $api->checkPost('password');
$_email = $api->checkPost('email');
$_recaptcha = $api->checkPost('recaptcha');
$_recaptcha = ! $_recaptcha ? $api->checkPost('g-recaptcha-response') : $_recaptcha;

// Validate token
if (! $_token)
{
	$api->data = array('error' => 'Invalid request. You are using an insecure page');
	$api->leave();
}

if (! ($_token == (new Common)->getFormToken('REGISTER')))
{
	$api->data = array('error' => 'Something went wrong :/');	
	$api->leave();
}

// Validate recaptcha
if (! $_recaptcha)
{
	$api->data = array('error' => 'Please fill out the reCaptcha');
	$api->leave();
}

if(! (new ReCaptcha(RECAPTCHA_SECRET))->verifyResponse($_SERVER["REMOTE_ADDR"], $_recaptcha))
{
	$api->data = array('error' => 'the reCaptcha was entered incorrectly');
	$api->leave();
}

// Validate email
if (! $_email)
{
	$api->data = array('error' => 'Please enter your email');
	$api->leave();
}

if ((new Validation)->emailExists($_email))
{
	$api->data = array('error' => 'Email is already registered');
	$api->leave();
}

// Validate username
if (! $_username)
{
	$api->data = array('error' => 'Please enter your username');
	$api->leave();
}

if ((new Validation)->usernameExists($_username))
{
	$api->data = array('error' => 'Username is already registered');
	$api->leave();
}

// Validate password
if (! $_password)
{
	$api->data = array('error' => 'Please enter your password');
	$api->leave();
}

// Validate name
if (! $_name)
{
	$api->data = array('error' => 'Please enter your name');
	$api->leave();
}

// Create user
$_user = new User();
$_user->name = $_name;
$_user->email = $_email;
$_user->username = $_username;
$_user->displayName = $_username;
$_user->status = LEVEL_USER;
$_output = $_user->append();

if ($_output === true)
{
	$_user->appendPassword($_password, true);
	$_user->appendLimits(true);
	$_user->appendOptions(true);
	$_guid = $api->checkPost('guid');
	$_guid = (! $_guid) ? '' : $_guid;
	
	// Create session
	$_user->createSession(false, $_guid);
	
	// Send confirmation email
	$_user->confirmEmail();
	
	// Send success message
	$api->data = array('success' => 'Welcome to Snow Capture, ' . $_user->username);
}

elseif ($_output !== false)
{
	$api->data = array('error' => 'Missing or invalid ' . $_output);
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}