<?php

// Get post values we need
$_image = $api->checkPost('id');

// Validate image
if (! $_image)
{
	$api->data = array('error' => 'Could not find picture');
	$api->leave();
}

// Let's start reporting 
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
	$api->data = array('error' => 'You cannot report your own picture :/');
	$api->leave();
}

// Make sure the image is published
if ($_image->status < IMAGE_PUBLISHED)
{
	$api->data = array('error' => 'Picture has not been published');
	$api->leave();
}

// Report / un-report
$_type = $api->checkPost('type');
$_comment = $api->checkPost('comment');
$_undo = $api->checkPost('undo');
$_report = ($_type !== false && ! $_undo);

if ($_report)
{
	// Make sure image isn't already reported
	if ($api->user()->hasReportedImage($_image->id))
	{
		$api->data = array('error' => 'Picture already reported');
		$api->leave();
	}
}

// Send data
$_response = $_report ? $api->user()->reportImage($_image, (! $_comment ? '' : $_comment), $_type) : $api->user()->unReportImage($_image);
if ($_response)
{
	$api->data = array('success' => 'Picture ' . ($_report ? '' : 'un-') . 'reported successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}