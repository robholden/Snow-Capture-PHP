<?php

class Altitude
{
  
  /**
   *
   * @var string
   */
  public $displayHeight = '';
  
  /**
   *
   * @var integer
   */
  public $end = 0;
  
  /**
   *
   * @var integer
   */
  public $id = 0;
  
  /**
   *
   * @var string
   */
  public $height = '';
  
  /**
   *
   * @var integer
   */
  public $start = 0;




  /**
   * Returns all altitudes
   * 
   * @return array
   */
  public function getAll()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_altitudes');
    $sql->order('start');
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Return data
    $_altitudes = array();
    while ($_altitude = $sql->fetchArray())
    {
      array_push($_altitudes, $this->assign($_altitude));
    }
    
    // Return locations
    return $_altitudes;
  }




  /**
   * Get altitude by a height
   * 
   * @param $height integer          
   * @return Altitude
   */
  public function getByHeight($height)
  {
    // Ensure height is valid
    if (! is_numeric($height))
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_altitudes');
    $sql->whereParam('start', '<=', $height);
    $sql->whereParam('end', '>=', $height, 'AND');
    $sql->limit(1);
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Return data
    $this->assign($sql->fetchArray(), $this);
  }




  /**
   *
   * @param array $read          
   * @param Altitude $write          
   * @return <boolean, Altitude>
   */
  protected function assign($read, $write = null)
  {
    // Ensure there's data
    if (empty($read))
    {
      return false;
    }
    
    // Init obj
    $_write = is_null($write) ? new Altitude() : $write;
    
    // Write data to obj
    $_write->id = $read['id'];
    $_write->height = $read['height'];
    $_write->displayHeight = $read['display_height'];
    $_write->start = $read['start'];
    $_write->end = $read['end'];
    
    return $_write;
  }
}