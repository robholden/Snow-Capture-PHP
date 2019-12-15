<?php

class Location implements IFilter
{
  public $id = 0;
  public $latitude = '';
  public $longitude = '';
  public $name = '';




  /**
   * Returns current location in querystring
   * 
   * @see IFilter::current()
   *
   */
  public function current()
  {
    return (! empty($_GET['location']) ? $_GET['location'] : '');
  }




  /**
   * Gets current location from querystring from db
   * 
   * @param string $value          
   *
   * @return <boolean, Location>
   */
  public function get($value = false)
  {
    // Get value
    $_value = ! $value ? $this->current() : $value;
    
    // Ensure there's a location
    if ($_value == '')
    {
      return array();
    }
    
    // Is there a country?
    $_country = new Country($_value);
    if ($_country->id > 0)
    {
      return $_country;
    }
    
    // Is there a resort
    $_resort = new Resort($_value);
    if ($_resort->id > 0)
    {
      return $_resort;
    }
    
    // Didn't find anything
    return false;
  }
}