<?php

// Get post values we need
$_lat = $api->checkPost('lat');
$_lng = $api->checkPost('lng');
$_latest = $api->checkPost('latest');

// Validate image
if (! is_numeric($_lat) || ! is_numeric($_lng))
{
  $api->data = array(
      'error' => 'Invalid Metrics'
  );
  $api->leave();
}

// Get images nearby location
$_filter = (new Image())->getByGeo($_lat, $_lng, $_latest);

if (! isset($_GET['count']))
{
  // Load actual image template
  // Determine classes
  $_html = '';
  
  foreach ($_filter->results as $image)
  {
    $_html .= (new HTMLRender())->imgGeo($image);
  }
  
  $api->data = array(
      'html' => $_html,
      'more' => ($_filter->total - (ROW_LIMIT * $_filter->page)) > 0,
      'timestamp' => date("Y-m-d H:i:s")
  );
} 

else
{  
  $api->data = array(
      'distance' => $_filter->distance . ' miles',
      'count' => $_filter->total
  );
}