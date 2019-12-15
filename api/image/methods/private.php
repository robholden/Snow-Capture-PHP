<?php

// Get post values we need
$_image = $api->checkPost('id');

// Validate image
if (! $_image)
{
	$api->data = array('error' => 'Could not find picture');
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

// Make sure the users' match
if ($_image->user->id !== $api->user()->id)
{
	$api->data = array('error' => (new Security)->addToNaughtyList(1));
	$api->leave();
}

// Make sure the image is at least privated
if ($_image->status < IMAGE_PRIVATE)
{
	$api->data = array('error' => 'Picture has not been published yet');
	$api->leave();
}

// Now we can private the image :)
$_image->status = ($_image->status == IMAGE_PRIVATE) ? IMAGE_PUBLISHED : IMAGE_PRIVATE;
if($_image->append())
{
	$_text = ($_image->status == IMAGE_PRIVATE) ? 'privated' : 'published';
	$api->data = array('success' => 'Picture ' . $_text . ' successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}