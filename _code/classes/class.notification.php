<?php

/**
 *
 * @author Robert Holden
 */
class Notification
{
  /**
   *
   * @var SnowCapture
   */
  private $sc;
  
  /**
   *
   * @var integer
   */
  public $id = 0;
  
  /**
   *
   * @var Image
   */
  public $image = false;
  
  /**
   *
   * @var User
   */
  public $userFrom = false;
  
  /**
   *
   * @var User
   */
  public $userTo = false;
  
  /**
   *
   * @var string
   */
  public $comment = '';
  
  /**
   *
   * @var integer
   */
  public $type = 0;
  
  /**
   *
   * @var string
   */
  public $date;
  
  /**
   *
   * @var string
   */
  public $time;
  
  /**
   *
   * @var integer
   */
  public $status = NOTIFICATION_CREATED;
  
  /**
   *
   * @var boolean
   */
  public $deleted = false;




  /**
   *
   * @param integer $id          
   */
  public function __construct($id = null)
  {
    global $sc;
    $this->sc = $sc;
    
    $this->date = date('Y-m-d');
    $this->time = date('H:i:s');
    
    $this->load($id);
  }




  /**
   * Creates or updates an image
   * 
   * @return <boolean, string>
   */
  public function append()
  {
    // Make sure user to and from aren't the same
    if ($this->userFrom->id == $this->userTo->id)
    {
      return true;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare insert/update
    $sql->insertUpdate('sc_notifications', ! $this->exists());
    
    // Prepare default values
    $sql->values('user_from_id', $this->userFrom->id);
    $sql->values('user_to_id', $this->userTo->id);
    $sql->values('image_id', (! $this->image ? 0 : $this->image->id));
    $sql->values('comment', $this->comment);
    $sql->values('type', $this->type);
    $sql->values('date', $this->date);
    $sql->values('time', $this->time);
    $sql->values('status', $this->status);
    $sql->values('deleted', ($this->deleted ? 1 : 0));
    
    // Prepare where for update (ignored for insert)
    $sql->whereParam('id', '=', $this->id);
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Send email?
    if (! $this->exists())
    {
      $this->sendEmail();
    }
    
    // Reload
    $this->load($sql->lastID());
    
    return true;
  }




  /**
   * Deletes current notification
   * 
   * @return boolean
   */
  public function delete()
  {
    // Ensure notificaiton exists
    if (! $this->exists())
    {
      return false;
    }
    
    // Set status to deleted
    $this->deleted = true;
    return $this->append();
  }




  /**
   * Restores current notification
   * 
   * @return boolean
   */
  public function restore()
  {
    // Ensure notificaiton exists
    if (! $this->exists())
    {
      return false;
    }
    
    // Set status to deleted
    $this->deleted = false;
    return $this->append();
  }




  /**
   * Finds notification based on properties
   */
  public function find()
  {
    // Delcare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_notifications');
    $sql->whereParam('user_to_id', '=', $this->userTo->id);
    $sql->whereParam('user_from_id', '=', $this->userFrom->id, 'AND');
    $sql->whereParam('image_id', '=', (! $this->image ? 0 : $this->image->id), 'AND');
    $sql->whereParam('type', '=', $this->type, 'AND');
    
    // Execute query
    $sql->execute();
    
    // Ensure there are rows
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Assign to this
    $this->assign($sql->fetchAssoc(), $this);
  }




  /**
   * Return if this type should be grouped when displaying
   * @RETURN BOOLEAN
   */
  public function group()
  {
    return ($this->type == LOG_LIKED || $this->type == LOG_RATED);
  }




  /**
   * Search notifications
   * 
   * @param FilterNotification $filter          
   * @return FilterNotification
   */
  public function search($filter)
  {
    // Store in local var
    $_filter = $filter;
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->select('notif.*');
    
    // Prepare from
    $sql->from('sc_notifications notif');
    
    // Now the where clause...
    // Track whether we need a separator
    $_sep = false;
    
    // Let's start with the arrays
    // Status'
    if (! empty($_filter->status))
    {
      $sql->where('( notif.status IN (' . implode(',', $_filter->status) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Now on to the individual ones
    // User From
    if (is_object($_filter->userFrom))
    {
      $sql->whereParam('notif.user_from_id', '=', $_filter->userFrom->id, ($_sep ? 'AND' : ''));
    }
    
    // User To
    if (is_object($_filter->userTo))
    {
      $sql->whereParam('notif.user_to_id', '=', $_filter->userTo->id, ($_sep ? 'AND' : ''));
    }
    
    // Image
    if (is_object($_filter->image))
    {
      $sql->whereParam('notif.image_id', '=', $_filter->image->id, ($_sep ? 'AND' : ''));
    }
    
    // Date limit
    if (is_numeric($_filter->days))
    {
      $date = date('Y-m-d H:i:s', strtotime("-" . $_filter->days . " days"));
      $sql->whereParam('notif.date', '>', $date, 'AND');
    }
    
    // Deleted
    if (! $_filter->deleted)
    {
      $sql->whereParam('notif.deleted', '=', 0, 'AND');
    }
    
    // Limit
    if ($_filter->limit !== false)
    {
      $sql->limit($_filter->limit);
    }    

    // Paging
    elseif ($_filter->page > 0)
    {
      $_start = ($_filter->page * ROW_LIMIT) - ROW_LIMIT;
      $sql->limit($_start . "," . ROW_LIMIT);
    }
    
    // Prepare order (ignoring filter options)
    $sql->order('status ASC');
    $sql->order('date DESC');
    $sql->order('time DESC');
    
    // Total rows
    $_filter->total = $sql->executeCount();
    
    // Execute query
    if (! $sql->execute())
    {
      return $_filter;
    }
    
    // Load
    while ($notif = $sql->fetchArray())
    {
      $_notif = $this->assign($notif);
      array_push($_filter->results, $_notif);
    }
    
    // Return notification data
    return $_filter;
  }




  /**
   * Returns whether the object is set
   * 
   * @return boolean
   */
  public function exists()
  {
    return ($this->id > 0) ? true : false;
  }




  /**
   * Returns if this notification has been read
   * 
   * @return boolean
   */
  public function hasRead()
  {
    return ($this->status == NOTIFICATION_VIEWED);
  }




  /**
   * Updates users notifications to viewed
   * 
   * @param User $user          
   * @param boolean $all          
   */
  public function viewed($user, $all = false)
  {
    // Ensure notification & user exists
    if ((! $this->exists() && ! $all) || ! $user->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->update('sc_notifications');
    $sql->values('status', NOTIFICATION_VIEWED);
    $sql->whereParam('user_to_id', '=', $user->id);
    
    // All or just this one?
    if (! $all)
    {
      $sql->whereParam('id', '=', $this->id, 'AND');
    }
    
    // Execute query
    return $sql->execute();
  }




  /**
   * Maps a datarow to an Image object
   * 
   * @param array $read          
   * @param Notification $write
   *          OPTIONAL
   * @return <boolean, Notification>
   */
  protected function assign($read, $write = null)
  {
    // Ensure there's data
    if (empty($read))
    {
      return false;
    }
    
    $_write = ($write == null) ? new Notification() : $write;
    $_write->id = $read['id'];
    $_write->image = ($read['image_id'] > 0) ? new Image($read['image_id']) : new Image();
    $_write->userTo = ($read['user_to_id'] > 0) ? new User($read['user_to_id']) : new User();
    $_write->userFrom = new User($read['user_from_id']);
    $_write->comment = $read['comment'];
    $_write->date = $read['date'];
    $_write->time = $read['time'];
    $_write->type = $read['type'];
    $_write->status = $read['status'];
    $_write->deleted = $read['deleted'];
    
    return $_write;
  }




  /**
   * Initialises Image object
   * 
   * @param integer $id          
   * @return boolean
   */
  protected function load($id)
  {
    if (! is_numeric($id))
    {
      return false;
    }
    
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('notif.*');
    $sql->from('sc_notifications notif');
    $sql->whereParam('notif.id', '=', $id);
    
    // Prepare limit
    $sql->limit(1);
    
    // Execute query
    $sql->execute();
    
    // Make sure there is data
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Assign data to obj
    $this->assign($sql->fetchArray(), $this);
  }




  /**
   * Send email of notification to user
   */
  protected function sendEmail()
  {
    // Don't send to themself
    if ($this->userFrom->id == $this->userTo->id)
    {
      return false;
    }
    
    // Ensure they hae enabled emails
    if (! $this->userTo->options->enableEmails)
    {
      return false;
    }
    
    // Ensure they are not online
    if ($this->userTo->isOnline())
    {
      return false;
    }
    
    // Send email
    switch ($this->type)
    {
      case LOG_LIKED:
        if ($this->userTo->options->sendLikes)
        {
          $emailer = new Email('Liked');
          $emailer->user = $this->userTo;
          $emailer->sendImage($this->image->id, $this->userFrom);
        }
        break;
      
      case LOG_ACCEPTED:
        if ($this->userTo->options->sendProcessing)
        {
          $emailer = new Email('Image Accepted');
          $emailer->user = $this->userTo;
          $emailer->sendImage($this->image->id);
        }
        break;
      
      case LOG_REJECTED:
        if ($this->userTo->options->sendProcessing)
        {
          $emailer = new Email('Image Rejected');
          $emailer->user = $this->userTo;
          $emailer->sendImage($this->image->id);
        }
        break;
    }
  }
}