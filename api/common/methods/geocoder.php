<?php

// Get location
$_location = $api->checkPost('location');
$_data = false;

if ($_location)
{
  $_location = urlencode($_location);
  $_json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$_location}&key=" . GOOGLE_MAPS_API_SERVER_KEY);
  $_json = json_decode($_json);
  
  if ($_json->{'status'} == 'OK')
  {
    if (sizeof($_json->{'results'}) > 0)
    {
      $_result = $_json->{'results'}[0];
      $_obj = new stdClass();
      $_obj->lat = $_result->{'geometry'}->{'location'}->{'lat'};
      $_obj->lng = $_result->{'geometry'}->{'location'}->{'lng'};
      $_obj->address = $_result->{'formatted_address'};
      $_obj->zoom = ($_result->{'types'}[0] == 'country' || $_result->{'types'}[0] == 'administrative_area_level_1') ? 7 : 11;
      
      $_data = $_obj;
    }
  }
}

$api->data = array('result' => $_data);