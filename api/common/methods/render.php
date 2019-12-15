<?php

$_method = $api->checkPost('method');

// Validate method
if (! $_method)
{
	$api->data = array('error' => 'Could not find render');
	$api->leave();
}

// HTML var
$_html = false;

// What html do we need?
switch ($_method)
{
	
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Set data if there is html :)
if ($_html && !empty($_html)) { $api->data = array ('html' => $_html); }