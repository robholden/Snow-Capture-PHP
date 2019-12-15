<?php

// Get post values we need
$_user = $api->checkPost('id');

// Validate user
if (! $_user)
{
	$api->data = array('error' => 'Could not find user');
	$api->leave();
}

// Let's start reporting 
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
$_type = $api->checkPost('type');
$_comment = $api->checkPost('comment');
$_undo = $api->checkPost('undo');
$_report = ($_type !== false && ! $_undo);

if ($_report)
{
	// Make sure user isn't already reported
	if ($api->user()->hasReportedUser($_user->id))
	{
		$api->data = array('error' => 'User already reported');
		$api->leave();
	}
}

// Send data
$_response = $_report ? $api->user()->reportUser($_user, (! $_comment ? '' : $_comment), $_type) : $api->user()->unReportUser($_user);
if ($_response)
{
	$api->data = array('success' => 'User ' . ($_report ? '' : 'un-') . 'reported successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}