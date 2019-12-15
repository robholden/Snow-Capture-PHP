<?php

class Activity
{
  /**
   * 
   * @var integer
   */
  public $id = 0;
  
  /**
   * 
   * @var string
   */
  public $type = '';
  
  
  /**
   * Returns all activities
   * 
   * @return array
   */
  public function getAll()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_activities');
    $sql->order('type');
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Return data
    $_activities = array();
    while ($_activity = $sql->fetchArray())
    {
      array_push($_activities, $this->assign($_activity));
    }
    
    // Return locations
    return $_activities;
  }
  
  
  /**
   * @param array $read
   * @param Activity $write
   * @return <boolean, Activity>
   */
  protected function assign($read, $write = null)
  {
    // Ensure there's data
    if (empty($read))
    {
      return false;
    }
  
    // Init obj
    $_write = is_null($write) ? new Activity() : $write;
  
    // Write data to obj
    $_write->id = $read['id'];
    $_write->type = $read['type'];
  
    return $_write;
  }
}