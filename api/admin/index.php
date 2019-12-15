<?php

/**
 * 
 * 
 * ADMIN
 * 
 * 
 **/

// API init file
require_once '../api.init.php';

// Only allow post call
$api->ensurePost();

// Ensure user is admin
$api->ensureWebAccess(LEVEL_ADMIN);

// Lets do this
// Point to correct method, where the api data property will be set :)
// For logical methods, separate into method/*.php file
switch ($api->method)
{
	case 'user_delete_report':
		
		// Ensure id is set
		$_id = $api->checkPost('id', true);
		
		// Remove report
		if($api->user()->deleteReport($_id))
		{
			$api->data = array('success' => 'Report removed successfully');
		}

		else
		{
			$api->data = array('error' => METHOD_ERROR);
		}
		
		break;
		
	case 'user_enable_disable':
		
		// Ensure id is set
		$_id = $api->checkPost('id', true);
		$_comment = $api->checkPost('comment', false);
		
		// Disable/enable user
		if($api->user()->toggleUser((new Security)->decrypt($_id), (! $_comment ? LEVEL_USER_CONFIRMED : LEVEL_DISABLED), $_comment))
		{
			$api->data = array('success' => 'User updated successfully');
		}

		else
		{
			$api->data = array('error' => METHOD_ERROR);
		}
	
		break;
		
	case 'image_moderate':
		
		// Ensure id is set
		$_id = $api->checkPost('id', true);
		$_image = new Image((new Security)->decrypt($_id));
		$_comment = $api->checkPost('comment', false);
		
		// Make sure image & user exist
		if (! $_image->exists() || ! $_image->user->exists())
		{
			$api->data = array('error' => "Something didn't exist");
			$api->leave();
		}
		
		// Send/remove notification to user
		$_notif = new Notification();
		$_notif->image = $_image;
		$_notif->userFrom = $api->user();
		$_notif->userTo = $_image->user;
		$_notif->type = (! $_comment) ? LOG_ACCEPTED : LOG_REJECTED;
		$_notif->append();
		
		// Accept/reject image
		$_resp = (! $_comment) ? $_image->publish() : $_image->delete($api->user(), $_comment);
		
		if ($_resp)
		{
			$api->data = array('success' => 'Picture ' . (! $_comment ? 'added' : 'removed') . ' successfully');
		}

		else
		{
			$api->data = array('error' => METHOD_ERROR);
		}
		
		break;
		
	case 'image_spotlight':
    
	  // Ensure id is set
	  $_id = $api->checkPost('id', true);
	  $_image = new Image((new Security)->decrypt($_id));
	  
	  // Make sure image & user exist
	  if (! $_image->exists() || ! $_image->user->exists())
	  {
	    $api->data = array('error' => "Something didn't exist");
	    $api->leave();
	  }

	  // Spotlight image
	  $_resp = $api->user()->spotlightImage($_image);
	  
	  if ($_resp)
	  {
	    $api->data = array('success' => 'Picture added to spotlight successfully');
	  }
	  
	  else
	  {
	    $api->data = array('error' => METHOD_ERROR);
	  }
	  
	  break;
		
	case 'ip_ban':
		
		// Get ips 
		$_ips = $api->checkPost('ip');
		$_ips = ! $_ips ? array() : explode(', ', $_ips);
		
		// Make sure there aren't any duplicate
		array_unique($_ips);
		
		// Get current banned ips
		$_curr_ips = (new Security)->banIps();
		
		// Add new
		foreach ($_ips as $key => $_ip)
		{
			if (! in_array($_ip, $_curr_ips))
			{
				(new Security)->banIp($_ip);
			}
		}
		
		// Remove old
		foreach ($_curr_ips as $key => $_ip)
		{
			if (! in_array($_ip, $_ips))
			{
				(new Security)->unbanIp($_ip);
			}
		}
		
		// Send output
		$api->data = array('success' => "Ips updated successfully");
		
		break;
		
	case 'resort_add':
		
		// Ensure we have correct post values
		$_name = $api->checkPost('resort', true);
		$_country = $api->checkPost('country_id', true);
		$_country = (new Country($_country))->id;
		$_lat = $api->checkPost('latitude', true);
		$_lon = $api->checkPost('longitude', true);
		
		if (! (new Validation)->resortExists($_name))
		{
		  $_resort = new Resort();
		  $_resort->name = $_name;
		  $_resort->countryID = $_country;
		  $_resort->latitude = $_lat;
		  $_resort->longitude = $_lon;
		  $_resort->save();
		  
			if ($_resort->id > 0)
			{
				$api->data = array('success' => 'Resort added successfully');
			}

			else
			{
				$api->data = array('error' => METHOD_ERROR);
			}
		}
		
		break;
		
	case 'resort_request_delete':
		
		// Ensure id is set
		$_id = $api->checkPost('id', true);
		
		if ($api->user()->deleteResortRequest($_id))
		{
			$api->data = array('success' => 'Request deleted successfully');
		}

		else
		{
			$api->data = array('error' => METHOD_ERROR);
		}
		
		break;
		
	case 'sitemap':
		(new Common)->createSitemap();
		break;
	
	default:
		$api->data = array('error' => 'Method not found');
		break;
}

// Send data
$api->leave();