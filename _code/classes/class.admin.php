<?php

class Admin extends User
{




  public function deleteReport($id)
  {
    // Ensure user is admin && id is a number
    if (! $this->isAdmin() && ! is_numeric($id))
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->update('sc_reported_users');
    $sql->values('status', 0);
    $sql->whereParam('id', '=', $id);
    
    // Execute query
    return $sql->execute();
  }




  public function deleteResortRequest($id)
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->delete('sc_resort_requests');
    $sql->whereParam('id', '=', $id);
    
    // Execute query
    return $sql->execute();
  }




  public function getAllProcessingImages()
  {
    return (new Image())->getAllProcessingImages($this);
  }




  public function getAllUsers()
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare user
    $sql->prepareUser();
    
    // Prepare where
    $sql->whereParam('user.status', '<>', LEVEL_DELETED);
    $sql->whereParam('user.status', '<>', $this->id, 'AND');
    
    // Order by
    $sql->order('user.id DESC');
    
    // Store users
    $_rows = array();
    
    // Execute query
    if ($sql->execute())
    {
      while ($row = $sql->fetchArray())
      {
        array_push($_rows, $this->assign($row));
      }
    }
    
    return $_rows;
  }




  public function getAttempts()
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_attempts');
    
    // Store attempts
    $_rows = array();
    
    // Execute query
    if ($sql->execute())
    {
      while ($row = $sql->fetchArray())
      {
        $_obj = new Attempt();
        $_obj->date = $row['date'];
        $_obj->id = $row['id'];
        $_obj->ip = $row['ip'];
        $_obj->status = $row['status'];
        $_obj->url = $row['url'];
        
        array_push($_rows, $_obj);
      }
    }
    
    return $_rows;
  }




  public function getEmailLogs()
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->select('log.*');
    $sql->select('user.display_username');
    $sql->select('template.name as subject');
    
    // Prepare from
    $sql->from('sc_email_log log');
    $sql->from('LEFT OUTER JOIN sc_users user ON user.id = log.user_id');
    $sql->from('LEFT OUTER JOIN sc_email_templates template ON template.id = log.email_id');
    
    // Prepare where
    $sql->whereParam('user_id', '<>', 0);
    
    // Prepare order
    $sql->order('date DESC');
    
    // Store logs
    $_rows = array();
    
    // Execute query
    if ($sql->execute())
    {
      while ($row = $sql->fetchArray())
      {
        $_obj = new EmailLog();
        $_obj->date = $row['date'];
        $_obj->id = $row['id'];
        $_obj->subject = $row['subject'];
        $_obj->username = $row['display_username'];
        
        array_push($_rows, $_obj);
      }
    }
    
    return $_rows;
  }




  public function getNextProcessingImage()
  {
    return (new Image())->getNextProcessingImage($this);
  }




  public function getResortRequests()
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->select('request.*');
    $sql->select('user.display_username');
    
    // Prepare from
    $sql->from('sc_resort_requests request');
    $sql->from('LEFT OUTER JOIN sc_users user ON user.id = request.user_id');
    
    // Store requests
    $_rows = array();
    
    // Execute query
    if ($sql->execute())
    {
      while ($row = $sql->fetchArray())
      {
        $_obj = new ResortRequest();
        $_obj->id = $row['id'];
        $_obj->resort = $row['resort'];
        $_obj->username = $row['display_username'];
        
        array_push($_rows, $_obj);
      }
    }
    
    return $_rows;
  }




  public function getUserReports($image = false)
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->select('reported.*');
    $sql->select('reported.id as id');
    $sql->select('user.display_username');
    $sql->select('reported_item.id as reported_id');
    $sql->select('reported_item.' . (! $image ? 'display_username' : 'title') . ' as reported_title');
    
    // Prepare from
    $sql->from('sc_reported_users reported');
    $sql->from('LEFT OUTER JOIN sc_users user ON user.id = reported.user_id');
    $sql->from('LEFT OUTER JOIN ' . (! $image ? 'sc_users' : 'sc_images') . ' reported_item ON reported_item.id = reported.' . (! $image ? 'reported_user_id' : 'reported_image_id'));
    
    // Prepare where
    $sql->whereParam('reported.status', '>', 0);
    $sql->whereParam('reported.' . (! $image ? 'reported_user_id' : 'reported_image_id'), '<>', 0, 'AND');
    
    // Store reports
    $_rows = array();
    
    // Execute query
    if ($sql->execute())
    {
      while ($row = $sql->fetchArray())
      {
        $_obj = new UserReport();
        $_obj->comment = $row['comment'];
        $_obj->date = $row['date'];
        $_obj->id = $row['id'];
        $_obj->reported_id = $row['reported_id'];
        $_obj->title = $row['reported_title'];
        $_obj->type = $row['type'];
        $_obj->username = $row['display_username'];
        
        array_push($_rows, $_obj);
      }
    }
    
    return $_rows;
  }




  public function toggleUser($id, $toggle, $message = '')
  {
    // Ensure user is admin && id/toggle is a number
    if (! $this->isAdmin() && ! is_numeric($id) || ! is_numeric($toggle))
    {
      return false;
    }
    
    // Ensure user to change exists
    $_user = new User($id);
    if (! $_user->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->update('sc_users');
    $sql->values('status', $toggle);
    $sql->whereParam('id', '=', $id);
    
    // Execute query
    $_resp = $sql->execute();
    
    if ($_resp && $toggle == LEVEL_DISABLED)
    {
      $emailer = new Email('Account Disabled');
      $emailer->user = $_user;
      return $emailer->sendAccountDisabled($message);
    }
    
    else 
    {
      return $_resp;
    }
  }




  public function spotlightImage($image)
  {
    // Ensure is admin
    if (! $this->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->insert('sc_image_spotlight');
    $sql->values('image_id', $image->id);
    
    // Execute query
    return $sql->execute();
  }
}