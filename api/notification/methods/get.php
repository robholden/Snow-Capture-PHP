<?php

// Require html render class
require_once WEB_ROOT . 'content/_code/class.htmlrender.php';

// Fetching or viewing?
$_read = $api->checkPost('read');
$_read = ! $_read ? false : ($_read == 'true');

// Build filter
$_filter = new FilterNotification();
$_filter->page = 1;
$_filter->limit = 25;
$_filter->userTo = $api->user();
$_filter->status = array(NOTIFICATION_CREATED, NOTIFICATION_VIEWED);

// Get notifications
$_filter = (new Notification)->search($_filter);
$_results = $_filter->results;

// Store html
$_html = array();

// Loop through if there are any
if (empty($_results))
{
  array_push($_html, (new HTMLRender)->notification(false));
}

else 
{
  // Flag as read if not polling
  if ($_read) { (new Notification)->viewed($api->user(), true); }
    
  // If there are more than 2 notification for an image then group them!
  $_notifications = array();
  $_likes = array();
  $_rated = array();
  $_likes_count = array();
  $_rated_count = array();
  
  foreach ($_results as $_notif)
  {
    // Groups likes
    switch ($_notif->type)
    {
      case LOG_LIKED:
        if (! in_array($_notif->image->id, $_likes))
        {
          array_push($_notifications, $_notif);
          array_push($_likes, $_notif->image->id);
          $_likes_count[$_notif->image->id] = 1;
        }
        
        else
        {
          $_likes_count[$_notif->image->id] = $_likes_count[$_notif->image->id] + 1;
        }
        break;
        
      case LOG_RATED:
        if (! in_array($_notif->image->id, $_rated))
        {
          array_push($_notifications, $_notif);
          array_push($_rated, $_notif->image->id);
          $_rated_count[$_notif->image->id] = 1;
        }
        
        else
        {
          $_rated_count[$_notif->image->id] = $_rated_count[$_notif->image->id] + 1;
        }
        break;
        
      default:
        array_push($_notifications, $_notif);
        break;
    }
  }
  
  foreach ($_notifications as $_notif)
  {
    $_text = false;
    switch ($_notif->type)
    {
      case LOG_LIKED:
        $_text = (new HTMLRender)->notification($_notif, $_likes_count[$_notif->image->id]);
        break;
      
      case LOG_RATED:
        $_text = (new HTMLRender)->notification($_notif, $_rated_count[$_notif->image->id]);
        break;
      
      default:
        $_text = (new HTMLRender)->notification($_notif);
        break;
    }
    
    if ($_text !== false)
    {
      array_push($_html, $_text);
    }
  }
}

// Set output
$_wait = 5;
$api->data = array(
    'read' => $_read,
    'new_notifications' => $_read ? 0 : $api->user()->notifications->new,
    'html' => $_html,
    'timeout' => $api->session()->isTimedOut() ? 'true' : 'false',
    'wait' => $_wait
);