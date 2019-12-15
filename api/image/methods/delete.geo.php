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
	$api->data = array('success' => 'Cannot find picture');
	$api->leave();
}

// Make sure the users' match
if ($_image->user->id !== $api->user()->id)
{
	$api->data = array('error' => (new Security)->addToNaughtyList(1));
	$api->leave();
}

// Now we can delete the geo data :)
if($_image->removeGeo())
{
	$api->data = array('success' => 'Geo data removed successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}