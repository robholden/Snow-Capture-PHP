<?php

// Get post values we need
$_image = $api->checkPost('id');

// Let's start updating 
$_image = (! $_image) ? new Image() : new Image((new Security)->decrypt($_image));

// Set new file path
$_cover = $api->checkPost('cover');
if ($api->user()->appendPicture($_image, ($_cover !== false)))
{
	$api->data = array('success' => (! $_cover ? 'Profile' : 'Cover') . ' picture updated successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}