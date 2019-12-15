<?php

/**
 * Parent object to control database classes
 * 
 * @author Robert
 */
class DataObject
{
  /**
   *
   * @var integer
   */
  public $id = 0;




  /**
   * Returns whether the object is set
   * 
   * @return boolean
   */
  public function exists()
  {
    return ($this->id > 0);
  }




  /**
   * Loads Audit object from db
   * 
   * @param int $id          
   */
  public function get($id)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Assign data to obj
    $sql->get($id, $this);
    
    return $this;
  }




  /**
   * Creates or updates a user
   * 
   * @return <boolean, string>
   */
  public function save()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Execute query
    if (! $sql->save($this))
    {
      return false;
    }
    
    // Success - rebind object
    $this->get($this->id);
    return true;
  }
}

class DbAudit extends DataObject
{
  public $date;
  public $description;
  public $from;
  public $imageId;
  public $ipAddress;
  public $userId;
  public $to;
}
