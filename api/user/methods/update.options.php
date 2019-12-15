<?php

// Get post vars
$_emails = $api->checkPost('enable_emails');
$_likes = $api->checkPost('send_likes');
$_processing = $api->checkPost('send_processing');
$_geo = $api->checkPost('upload_geo');
$_user = $api->user();

if ($_emails)
{
	$_user->options->enableEmails = (strtolower($_emails) == 'true');
}

if ($_likes)
{
	$_user->options->sendLikes = (strtolower($_likes) == 'true');
}

if ($_processing)
{
	$_user->options->sendProcessing = (strtolower($_processing) == 'true');
}

if ($_geo)
{
  $_user->options->uploadGeo = (strtolower($_geo) == 'true');
}

// Save user
if($_user->appendOptions())
{
	$api->data = array('success' => 'Preference settings updated successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}