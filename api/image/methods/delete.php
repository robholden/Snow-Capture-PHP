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

// Make sure the image exists, if not then job done
if (! $_image->exists())
{
	$api->data = array('success' => 'Picture removed successfully');
	$api->leave();
}

// Make sure the users' match
if ($_image->user->id !== $api->user()->id && ! $api->user()->isAdmin())
{
	$api->data = array('error' => (new Security)->addToNaughtyList(1));
	$api->leave();
}

// Now we can delete the image :)
if($_image->delete($api->user()))
{
	$api->data = array('success' => 'Picture removed successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}