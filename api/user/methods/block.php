<?php

// Get post values we need
$_user = $api->checkPost('id');

// Validate user
if (! $_user)
{
	$api->data = array('error' => 'Could not find user');
	$api->leave();
}

// Let's start blocking 
$_user = new User((new Security)->decrypt($_user));

// Make sure the user exists
if (! $_user->exists())
{
	$api->data = array('success' => 'Could not find user');
	$api->leave();
}

// Make sure the users' DON'T match
if ($_user->id == $api->user()->id)
{
	$api->data = array('error' => 'You cannot report yourself :/');
	$api->leave();
}

// Report / un-report
$_unreport = $api->checkPost('undo');

if (! $_unreport)
{
	// Make sure user isn't already blocked
	if ($api->user()->hasBlockedUser($_user->id))
	{
		$api->data = array('error' => 'User already blocked');
		$api->leave();
	}
}

// Send data
$_response = $api->user()->blockUser($_user, $_unreport);
if ($_response)
{
	$api->data = array('success' => 'User ' . ($_report ? '' : 'un-') . 'blocked successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}