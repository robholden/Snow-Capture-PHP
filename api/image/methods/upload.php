<?php

// Require image manipulator class
require_once LIB_ROOT . 'classes/class.imagemanipulator.php';

// Set local var
$_user = $api->user();
$_common = new Common();

// Ensure user has verfied email
if ($_user->status == LEVEL_USER)
{
  $api->data = array(
      'error' => 'Please confirm your email address'
  );
  $api->leave();
}

// Ensure user has valid status
if ($_user->status < LEVEL_USER_CONFIRMED)
{
  $api->data = array(
      'error' => 'Your account appears to be invalid'
  );
  $api->leave();
}

// Ensure user can upload
if (! $_user->canAccessUpload())
{
  $api->data = array(
      'error' => 'Upload limit reached'
  );
  $api->leave();
}

// Make sure they haven't reached their limit
if ($_user->drafts > $_user->limits->drafts)
{
  $_image = $_user->getDraftImage();
  $api->data = array(
      'error' => 'You can only upload ' . $_user->limits->drafts . ' image(s) at a time',
      'location' => '/capture/' . $_image->displayID . '/edit'
  );
  $api->leave();
}

// Let's upload some pictures! :)
$_drafts = $_user->drafts;

// Get allowed file types from config
$_filetypes = explode('/', ACCEPT_FILE_TYPES);
foreach ($_filetypes as $key => $_type)
{
  $_filetypes[$_type] = 'image/' . $_type;
}

// Make sure there are pictures
if (! isset($_FILES['images']))
{
  $api->data = array(
      'error' => 'Please choose a valid picture'
  );
  $api->leave();
}

try
{
  foreach ($_FILES['images']['name'] as $key => $_picture)
  {
    // Ensure below allowance
    if ($_drafts >= $_user->limits->drafts)
    {
      throw new RuntimeException('Draft limit reached (' . $_user->limits->drafts . ')');
    }
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (! isset($_FILES['images']['error'][$key]) || is_array($_FILES['images']['error'][$key]))
    {
      throw new RuntimeException('An error occurred');
    }
    
    switch ($_FILES['images']['error'][$key])
    {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new RuntimeException('No picture found');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new RuntimeException('Picture too large');
      default:
        throw new RuntimeException('Unknown errors.');
    }
    
    // Assign vars
    $_picturesize = $_FILES['images']['size'][$key];
    $_pictureinfo = new finfo(FILEINFO_MIME_TYPE);
    
    $_ext = array_search($_pictureinfo->file($_FILES['images']['tmp_name'][$key]), $_filetypes, true);
    $_hashname = sha1($_FILES['images']['name'][$key] . $_user->id . date_timestamp_get(date_create()) . $key);
    $_picturepath = WEB_ROOT . UPLOAD_DIR . $_hashname . '.' . $_ext;
    $_picturename = basename($_FILES['images']['name'][$key], $_ext);
    $_thumbnailpath_system = WEB_ROOT . UPLOAD_DIR . THUMBNAIL_PATH_SYSTEM . $_hashname . '.jpg';
    $_thumbnailpath_custom = WEB_ROOT . UPLOAD_DIR . THUMBNAIL_PATH_CUSTOM . $_hashname . '.jpg';
    
    if ($_picturesize > MAX_UPLOAD_SIZE)
    {
      throw new RuntimeException('Picture is too large');
    }
    
    if ($_picturesize < MIN_UPLOAD_SIZE)
    {
      throw new RuntimeException('Picture is too small');
    }
    
    if ($_ext === false)
    {
      throw new RuntimeException('Invalid format (' . ACCEPT_FILE_TYPES . ' Only)');
    }
    
    if (! move_uploaded_file($_FILES['images']['tmp_name'][$key], $_picturepath))
    {
      @unlink($_picturepath);
      throw new RuntimeException('Could not save');
    }
    
    $_resort = new Resort();
    $_country= new Country();
    $_altitude = new Altitude();
    $_date_taken = date("Y-m-d");
    $_latitude = '';
    $_longitude = '';
    
    // Get EXIF info if jpeg
    if ($_user->options->uploadGeo && (strtolower($_ext) == 'jpg' || strtolower($_ext) == 'jpeg'))
    {
      $_exif = exif_read_data($_picturepath, 0, true);
      
      // Get meta
      if (isset($_exif['EXIF']['DateTimeOriginal']))
      {
        $_date_taken = date("Y-m-d", strtotime($_exif['EXIF']['DateTimeOriginal']));
      }
      
      // Get GPS Co-ords
      if (isset($_exif['GPS']))
      {               
        // Altitude
        $_altref = $_exif['GPS']['GPSAltitudeRef'];
        if ($_altref == 0)
        {
          $_alt = explode('/', $_exif['GPS']['GPSAltitude']);
          $_altitude->getByHeight($_alt[0] / $_alt[1]);
        }
        
        // Latitude
        $_degrees = $_exif['GPS']['GPSLatitude'][0];
        $_parts = explode('/', $_degrees);
        $_degrees = $_parts[0] / $_parts[1];
        
        $_minutes = $_exif['GPS']['GPSLatitude'][1];
        $_parts = explode('/', $_minutes);
        $_minutes = $_parts[0] / $_parts[1];
        
        $_seconds = $_exif['GPS']['GPSLatitude'][2];
        $_parts = explode('/', $_seconds);
        $_seconds = $_parts[0] / $_parts[1];
        
        $_ref = $_exif['GPS']['GPSLatitudeRef'];
        $_latitude = $_common->DMStoDEC($_degrees, $_minutes, $_seconds, $_ref);
        
        // Longitude
        $_degrees = $_exif['GPS']['GPSLongitude'][0];
        $_parts = explode('/', $_degrees);
        $_degrees = $_parts[0] / $_parts[1];
        
        $_minutes = $_exif['GPS']['GPSLongitude'][1];
        $_parts = explode('/', $_minutes);
        $_minutes = $_parts[0] / $_parts[1];
        
        $_seconds = $_exif['GPS']['GPSLongitude'][2];
        $_parts = explode('/', $_seconds);
        $_seconds = $_parts[0] / $_parts[1];
        $_ref = $_exif['GPS']['GPSLongitudeRef'];
        $_longitude = $_common->DMStoDEC($_degrees, $_minutes, $_seconds, $_ref);
        
        // Get location
        $_geo_data = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$_latitude},{$_longitude}&key=" . GOOGLE_MAPS_API_SERVER_KEY);
        $_location_data = json_decode($_geo_data, true);
        
        $_temp_village = '';
        $_temp_town = ''; 
        $_temp_country = '';
        $_temp_lat = '';
        $_temp_lng = '';
        
        if (is_array($_location_data) && $_location_data['status'] == 'OK')
        {
          $_addr_comp = $_location_data['results'][0]['address_components'];
          foreach ($_addr_comp as $_addr)
          {
            if (! empty($_addr['types']))
            {
              foreach ($_addr['types'] as $_type)
              {
                if ($_type == 'locality')
                {
                  $_temp_village = ! empty($_addr['long_name']) ? $_addr['long_name'] : '';
                } 
                
                elseif ($_type == 'postal_town')
                {
                  $_temp_town = ! empty($_addr['long_name']) ? $_addr['long_name'] : '';
                } 
                
                elseif ($_type == 'country')
                {
                  $_temp_country = ! empty($_addr['short_name']) ? $_addr['short_name'] : '';
                }
                
                if ($_type == 'locality' || $_type == 'postal_town')
                {
                  $_temp_latlng = $_location_data['results'][0]['geometry']['location'];
                  $_temp_lat = $_temp_latlng['lat'];
                  $_temp_lng = $_temp_latlng['lng'];
                }
              }
            }
          }
        }
        
        // Add to database if location does not exist!
        if (! empty($_temp_country))
        {
          $_country = new Country($_temp_country);

          if ($_country->id > 0)
          {
            if (! empty($_temp_village) && ! empty($_temp_lat) && ! empty($_temp_lng))
            {
              $_resort = new Resort($_temp_village);
              if ($_resort->id == 0)
              {
                $_resort->name = $_temp_village;
                $_resort->countryID = $_country->id;
                $_resort->latitude = $_temp_lat;
                $_resort->longitude = $_temp_lng;
                $_resort->save();
              }
            } 

            elseif (! empty($_temp_town) && ! empty($_temp_lat) && ! empty($_temp_lng))
            {
              $_resort = new Resort($_temp_village);
              if ($_resort->id == 0)
              {
                $_resort->name = $_temp_town;
                $_resort->countryID = $_country->id;
                $_resort->latitude = $_temp_lat;
                $_resort->longitude = $_temp_lng;
                $_resort->save();
              }
            }
          }
        }
      }
      
      if (isset($_exif['IFD0']['Orientation']))
      {
        $_degrees = 0;
        
        switch ($_exif['IFD0']['Orientation'])
        {
          case 3:
            // Need to rotate 180 deg
            $_degrees = 180;
            break;
          
          case 6:
            // Need to rotate 90 deg clockwise
            $_degrees = 270;
            break;
          
          case 8:
            // Need to rotate 90 deg counter clockwise
            $_degrees = 90;
            break;
        }
        
        if ($_degrees !== 0)
        {
          // Create the canvas
          $_source = imagecreatefromjpeg($_picturepath);
          
          // Rotates the image
          $_rotate = imagerotate($_source, $_degrees, 0);
          
          // Outputs a jpg image
          imagejpeg($_rotate, $_picturepath);
        }
      }
    }
    
    // Prepare new image
    $_image = new Image();
    
    // Resize image
    $_manipulator = new ImageManipulator($_picturepath);
    if ($_manipulator->getWidth() > IMG_MAX_WIDTH || $_manipulator->getHeight() > IMG_MAX_HEIGHT)
    {
      $_manipulator->resample(IMG_MAX_WIDTH, IMG_MAX_HEIGHT, true);
      $_manipulator->save($_picturepath);
    }
    
    // Save real width and height
    $_image->width = $_manipulator->getWidth();
    $_image->height = $_manipulator->getHeight();
    
    // Create thumbnail with & without aspect ratio
    $_manipulator->resample(THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, true);
    $_manipulator->save($_thumbnailpath_system);
    $_manipulator->resample(THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, false);
    $_manipulator->save($_thumbnailpath_custom);
    
    // Store image info
    $_image->altitudeID = $_altitude->id;
    $_image->dateTaken = $_date_taken;
    $_image->title = $_picturename;
    $_image->filePath = $_hashname;
    $_image->fileType = $_ext;
    $_image->latitude = (! empty($_latitude) && ! empty($_longitude)) ? $_latitude : 0;
    $_image->longitude = (! empty($_latitude) && ! empty($_longitude)) ? $_longitude : 0;
    $_image->countryID = $_country->id;
    $_image->resortID = $_resort->id;
    $_image->status = 1;
    $_image->user = $_user;
    
    // Add to db
    $_save = $_image->append();
    
    // Delete image if the image didn't save properly
    if (! $_image->exists())
    {
      @unlink($_picturepath);
      @unlink($_thumbnailpath_system);
      @unlink($_thumbnailpath_custom);
      throw new RuntimeException('An error occurred adding your picture.');
    }
    
    if ($_save !== true)
    {
      throw new RuntimeException($_save);
    }
    
    $api->data = array(
        'success' => "Your picture(s) have successfully uploaded",
        'location' => '/' . $_user->username . '/drafts'
    );
    
    $_drafts ++;
  }
} 

catch (RuntimeException $_e)
{
  $api->data = array(
      'error' => $_e->getMessage()
  );
}