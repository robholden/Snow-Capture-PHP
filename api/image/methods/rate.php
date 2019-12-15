<?php

// Get post values we need
$_image = $api->checkPost('id');
$_rating = $api->checkPost('rating');

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

// Make sure the users' DON'T match
if ($_image->user->id == $api->user()->id)
{
	$api->data = array('error' => 'You cannot rate your own picture :/');
	$api->leave();
}

// Make sure the image is published
if ($_image->status < IMAGE_PUBLISHED)
{
	$api->data = array('error' => 'Picture has not been published');
	$api->leave();
}

// Now we can rate the image :)
if($api->user()->rateImage($_image, (! $_rating ? 0 : $_rating)))
{
  // Get new rating
  $_img = new Image($_image->id);
	$api->data = array(
	    'success' => 'Picture rated successfully',
	    'rating' => $_img->rating
	);
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}