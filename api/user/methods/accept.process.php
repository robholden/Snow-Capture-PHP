<?php

// Get post values we need
$_accepted = $api->checkPost('accepted');

// Ensure they have accepted
if (! $_accepted)
{
	$api->data = array('error' => 'Please accept the process');
	$api->leave();
}

// Save user
$api->user()->imageTerms = true;
if($api->user()->append())
{
	$api->data = array('success' => 'Process accepted successfully');
}

else
{
	$api->data = array('error' => METHOD_ERROR);
}