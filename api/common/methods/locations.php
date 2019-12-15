<?php

// Get country id
$_country = $api->checkPost('country');

// If set search for resorts
if ($_country !== false)
{
  $_resort_dump = (new Resort)->getByCountry($_country);
  $_resorts = '';
  foreach ($_resort_dump as $key => $value)
  {
    $_resorts[] = array(
        "id" => $value->id,
        "name" => $value->name
    );
  }
  
  $api->data = array(
      'resorts' => $_resorts
  );
}

