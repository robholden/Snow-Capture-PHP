<?php

// Get post values we need
$_image = $api->checkPost('id');

// Validate image
if (! $_image)
{
	$api->data = array('error' => 'Could not find picture');
	$api->leave();
}

// Ensure user has verfied  email
if ($api->user()->status == LEVEL_USER)
{
  $api->data = array('error' => 'Please confirm your email address');
  $api->leave();
}

// Ensure user has valid status
if ($api->user()->status < LEVEL_USER_CONFIRMED)
{
  $api->data = array('error' => 'Your account appears to be invalid');
  $api->leave();
}

// Let's start deleting 
$_image = new Image((new Security)->decrypt($_image));

// Make sure the image exists
if (! $_image->exists())
{
	$api->data = array('success' => 'Could not find picture');
	$api->leave();
}

// Let's either like or un-like the image :)
$_resp = $api->user()->toggleLike($_image);
if($_resp !== false)
{
	$api->data = array('success' => $_resp);
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}