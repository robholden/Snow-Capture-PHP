<?php

// Get post vars
$_name = $api->checkPost('name');
$_displayname = $api->checkPost('display_name');
$_email = $api->checkPost('email');
$_password = $api->checkPost('password');
$_user = $api->user();
$_confirm = false;

// Validate name
if (! $_name)
{
	$api->data = array('error' => 'Please enter your name');
	$api->leave();
}

// Validate display name
if (! $_displayname)
{
	$api->data = array('error' => 'Please enter your display name');
	$api->leave();
}

// Validate email
if (! $_email)
{
	$api->data = array('error' => 'Please enter your email');
	$api->leave();
}

// Ensure password has been entered if changing email
if (strtolower($_email) != strtolower($_user->email))
{
	
	// Validate password
	if (! $_password)
	{
		$api->data = array('error' => 'Password is incorrect');
		$api->leave();
	}
	
	if (! ($_user->password() === (new Session)->saltData($_user, $_password)))
	{
		$api->data = array('error' => 'Password is incorrect');
		$api->leave();
	}
	
	if ((new Validation)->emailExists($_email))
	{
		$api->data = array('error' => 'Email is already registered');
		$api->leave();
	}
	
	$_user->email = $_email;
	$_user->status = LEVEL_USER;
	$_confirm = true;
}

// Update
$_user->name = $_name;
$_user->displayName = $_displayname;
$_output = $_user->append();

if ($_output === true)
{
  if ($_confirm)
  {
    $_user->confirmEmail();
  }
  
	$api->data = array('success' => 'General settings updated successfully');
}

elseif ($_output !== false)
{
	$api->data = array('error' => 'Missing or invalid ' . $_output);
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}