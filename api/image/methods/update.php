<?php

// Get post values
$_image = $api->checkPost('id');
$_name = $api->checkPost('name');
$_date = $api->checkPost('date_taken');

// Validate image
if (! $_image)
{
  $api->data = array(
      'error' => 'Could not find picture'
  );
  $api->leave();
}

// Validate name
if (! $_name)
{
  $api->data = array(
      'error' => 'Please name your picture'
  );
  $api->leave();
}

// Validate date
if (! $_date)
{
  $api->data = array(
      'error' => 'Please tell us when your picture was taken'
  );
  $api->leave();
}

// Let's start updating
$_image = new Image((new Security())->decrypt($_image));

// Make sure the image exists
if (! $_image->exists())
{
  $api->data = array(
      'success' => 'Could not find picture'
  );
  $api->leave();
}

// Make sure the users' match
if ($_image->user->id !== $api->user()->id)
{
  $api->data = array(
      'error' => (new Security())->addToNaughtyList(1)
  );
  $api->leave();
}

// Now we can update the image :)
// Get other properties to change
$_activity = $api->checkPost('activity_id');
$_altitude = $api->checkPost('altitude_id');
$_description = $api->checkPost('description');
$_showmap = $api->checkPost('show_map');
$_showcover = $api->checkPost('show_cover');
$_resort = $api->checkPost('resort_id');
$_country = $api->checkPost('country_id');

// Are they just saving?
$_save = $api->checkPost('save');
$_save = (strtolower($_save) == 'true');
$_draft = ($_image->status == IMAGE_DRAFT);
$_showmap = (strtolower($_showmap) == 'true');
$_showcover = (strtolower($_showcover) == 'true');

// Update properties
$_image->title = $_name;
$_image->dateTaken = $_date;
$_image->status = ($_draft && ! $_save) ? IMAGE_PROCESSING : $_image->status;

// If admin go straight to published
$_publish = ($api->user()->isAdmin() && ($_draft && ! $_save && ($_image->status == IMAGE_PROCESSING)));

$_image->activityID = ! $_activity ? 0 : $_activity;
$_image->altitudeID = ! $_altitude ? 0 : $_altitude;
$_image->description = ! $_description ? '' : $_description;
$_image->showMap = ! $_showmap ? false : $_showmap;
$_image->showCover = ! $_showcover ? false : $_showcover;

// Get actual locations
$_resort = new Resort($_resort);
$_country = new Country($_country);

// If this image has real geo data and they're trying to change the location
// (sneaky buggers)
if (! ($_image->hasRealGeo && ($_image->resortID != $_resort->id || $_image->countryID != $_country->id)))
{
  // Update image location
  $_image->resortID = $_resort->id;
  $_image->countryID = $_country->id;
}

// Thumbnail generation
$_generate = $api->checkPost('generate');
if (strtolower($_generate) == 'true')
{
  $_x1 = $api->checkPost('x1');
  $_x2 = $api->checkPost('x2');
  $_y1 = $api->checkPost('y1');
  $_y2 = $api->checkPost('y2');
  $_height = $api->checkPost('height');
  $_width = $api->checkPost('width');
  
  $_x1 = is_numeric($_x1) ? $_x1 : 0;
  $_x2 = is_numeric($_x2) ? $_x2 : 0;
  $_y1 = is_numeric($_y1) ? $_y1 : 0;
  $_y2 = is_numeric($_y2) ? $_y2 : 0;
  $_height = is_numeric($_height) ? $_height : 1;
  $_width = is_numeric($_width) ? $_width : 1;
  
  $_manipulator = new ImageManipulator(WEB_ROOT . $_image->filePath);
  
  $_manipulator->resample($_width, $_height, false);
  $_manipulator->crop($_x1, $_y1, $_x2, $_y2);
  
  $_manipulator->resample(THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, false);
  $_manipulator->save(WEB_ROOT . $_image->thumbnails['custom']);
}

// Save tags
$_tags = $api->checkPost('tags');
if (! $_tags)
{
  $_tags = array();
} 

else
{
  $_tags = htmlentities($_tags);
  $_tags = preg_replace('/\s*,\s*/', ',', $_tags);
  $_tags = explode(',', $_tags);
}

// Apply changes
$_output = $_publish ? $_image->publish() : $_image->append();
$_output = ($_output === true) ? $_image->addTags($_tags) : $_output;

if ($_output === true)
{
  $api->data = array(
      'success' => 'Picture ' . ($_save ? 'saved' : 'updated') . ' successfully'
  );
  
  if ($_draft && ! $_save && ($_image->status == IMAGE_PROCESSING))
  {
    $_emailer = new Email('Review Image');
    $_emailer->sendReviewImage((new Common())->siteURL() . $_image->thumbnails['custom']);
  }
} 

elseif ($_output !== false)
{
  switch ($_output)
  {
    case 'badword':
      $api->data = array(
          'error' => 'No Offensive Language'
      );
      break;
    
    default:
      $api->data = array(
          'error' => 'Missing or invalid ' . $_output
      );
      break;
  }
} 

else
{
  $api->data = array(
      'error' => METHOD_ERROR
  );
}
