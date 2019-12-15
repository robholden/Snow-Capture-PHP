<?php

/**
 * Object that stores country info
 * 
 * @author Robert
 */
class Country extends Location
{
  public $code = '';




  public function __construct($id = null)
  {
    if (! is_null($id))
    {
      $this->load($id);
    }
  }




  /**
   * Returns array of country from url, or false if not set
   * Seperated by '|'
   * 
   * @return <boolean, array>
   */
  public function current()
  {
    // Validate get value
    if (empty($_GET['country']) || ! is_string($_GET['country']))
    {
      return false;
    }
    
    return explode('|', $_GET['country']);
  }




  /**
   * Get all countries from db
   */
  public function getAll()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_countries');
    $sql->order('name');
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Store
    $_countries = array();
    
    // Load
    while ($country = $sql->fetchArray())
    {
      array_push($_countries, $this->assign($country));
    }
    
    // Return
    return $_countries;
  }




  /**
   * Get all countries from db
   */
  public function getFromImages()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_countries');
    $sql->where('id IN (SELECT country_id FROM sc_images WHERE status = ' . IMAGE_PUBLISHED . ')');
    $sql->order('name');
    
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Store
    $_countries = array();
    
    // Load
    while ($country = $sql->fetchArray())
    {
      array_push($_countries, $this->assign($country));
    }
    
    // Return
    return $_countries;
  }




  /**
   * Get top locations
   * 
   * @param number $count          
   * @return array<Resort>
   */
  public function getTop($count = 10)
  {
    // Validate count
    if (! is_numeric($count))
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->select('country.name');
    
    // Prepare from
    $sql->from('sc_images image RIGHT OUTER JOIN');
    $sql->from('sc_countries country ON country.id = image.country_id');
    
    // Prepare where
    $sql->whereParam('image.status', '=', IMAGE_PUBLISHED);
    
    // Prepare group by
    $sql->groupBy('country.name DESC');
    
    // Prepare order
    $sql->order('RAND()');
    
    // Prepare limit
    $sql->limit('0, 10');
    
    // Execute query
    $sql->execute();
    
    // Is there data?
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Return data
    $_locations = array();
    while ($_location = $sql->fetchArray())
    {
      array_push($_locations, $_location['name']);
    }
    
    // Return locations
    return $_locations;
  }




  /**
   * Save a country to db
   * {@inheritDoc}
   * 
   * @see Location::save()
   */
  public function save()
  {}




  /**
   * Loads country from db
   * {@inheritDoc}
   * 
   * @see Location::load()
   */
  protected function load($id)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_countries');
    
    // Is it an id or name?
    if (is_numeric($id))
    {
      $sql->whereParam('id', '=', $id);
    } 

    else
    {
      $sql->whereParam('name', '=', $id);
      $sql->whereParam('country_code', '=', $id, 'OR');
    }
    
    // Limit just incase
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
   * Maps a datarow to a country object
   * {@inheritDoc}
   * 
   * @see Location::assign()
   */
  protected function assign($read, $write = null)
  {
    // Ensure there's data
    if (empty($read))
    {
      return false;
    }
    
    // Init obj
    $_write = is_null($write) ? new Country() : $write;
    
    // Write data to obj
    $_write->id = $read['id'];
    $_write->code = $read['country_code'];
    $_write->latitude = $read['latitude'];
    $_write->longitude = $read['longitude'];
    $_write->name = utf8_encode($read['name']);
    
    return $_write;
  }
}