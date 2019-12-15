<?php

// Get post values we need
$_NELat = $api->checkPost('NELat');
$_NELng = $api->checkPost('NELng');
$_SWLat = $api->checkPost('SWLat');
$_SWLng = $api->checkPost('SWLng');
$_latest = $api->checkPost('latest');

// Validate image
if (! is_numeric($_NELat) || ! is_numeric($_NELat) || ! is_numeric($_SWLat) || ! is_numeric($_SWLng))
{
  $api->data = array(
      'error' => 'Invalid Metrics'
  );
  $api->leave();
}

// Get images nearby location
$_NE = array($_NELat, $_NELng);
$_SW = array($_SWLat, $_SWLng);
$_filter = (new Image())->getByRegion($_NE, $_SW, $_latest);


// Load actual image template
// Determine classes
$_html = '';

foreach ($_filter->results as $image)
{  
  $_html .= (new HTMLRender())->imgGalleryImage($image);
}

$api->data = array(
    'html' => $_html,
    'more' => ($_filter->total - (ROW_LIMIT * $_filter->page)) > 0,
    'timestamp' => date("Y-m-d H:i:s")
);