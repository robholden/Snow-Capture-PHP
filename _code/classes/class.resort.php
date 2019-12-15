<?php

/**
 * Object that stores resort info
 *
 * @author Robert
 */
class Resort extends Location
{
  public $country = '';
  public $countryID = 0;




  public function __construct($id = null)
  {
    if (! is_null($id))
    {
      $this->load($id);
    }
  }




  /**
   * {@inheritDoc}
   *
   * @see Filter::current()
   */
  public function current()
  {
    // Validate get value
    if (empty($_GET['resort']) || ! is_string($_GET['resort']))
    {
      return false;
    }

    return explode('|', $_GET['resort']);
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
    $sql->select('(SELECT name FROM sc_countries WHERE id = country_id) as country');
    $sql->from('sc_resorts');

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Store
    $_resorts = array();

    // Load
    while ($resort = $sql->fetchArray())
    {
      array_push($_resorts, $this->assign($resort));
    }

    // Return
    return $_resorts;
  }




  /**
   * Get all countries from db
   */
  public function getByCountry($id)
  {
    // Ensure is int
    if (! is_numeric($id))
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('*');
    $sql->select('(SELECT name FROM sc_countries WHERE id = country_id) as country');
    $sql->from('sc_resorts');
    $sql->where('country_id IN (SELECT id FROM sc_countries WHERE id = ' . $id . ')');

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Store
    $_resorts = array();

    // Load
    while ($resort = $sql->fetchArray())
    {
      array_push($_resorts, $this->assign($resort));
    }

    // Return
    return $_resorts;
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
    $sql->select('resort.name');

    // Prepare from
    $sql->from('sc_images image RIGHT OUTER JOIN');
    $sql->from('sc_resorts resort ON resort.id = image.resort_id');

    // Prepare where
    $sql->whereParam('image.status', '=', IMAGE_PUBLISHED);

    // Prepare group by
    $sql->groupBy('resort.name');

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
   * Save a resort to db
   * {@inheritDoc}
   *
   * @see Location::save()
   */
  public function save()
  {
    // Validate
    if (! $this->validate())
    {
      return false;
    }

    // Declare sql
    $sql = new SQLHelper();

    // Prepare query
    $sql->insert('sc_resorts');
    $sql->values('name', $this->name);
    $sql->values('country_id', $this->countryID);
    $sql->values('latitude', $this->latitude);
    $sql->values('longitude', $this->longitude);

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Reassign
    $this->load($sql->lastID());
  }




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
    $sql->select('(SELECT name FROM sc_countries WHERE id = country_id) as country');
    $sql->from('sc_resorts');

    // Is it an id or name?
    if (is_numeric($id))
    {
      $sql->whereParam('id', '=', $id);
    }

    else
    {
      $sql->whereParam('name', '=', $id);
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
   * Maps a datarow to a resort object
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
    $_write = is_null($write) ? new Resort() : $write;

    // Write data to obj
    $_write->id = $read['id'];
    $_write->country = $read['country'];
    $_write->countryID = $read['country_id'];
    $_write->latitude = $read['latitude'];
    $_write->longitude = $read['longitude'];
    $_write->name = utf8_encode($read['name']);

    return $_write;
  }




  /**
   * Validates resort
   *
   * @return bool
   */
  protected function validate()
  {
    if (strlen($this->name) == 0 || strlen($this->name) > 250)
    {
      return false;
    }

    if (! is_numeric($this->countryID) || ! is_numeric($this->latitude) || ! is_numeric($this->longitude))
    {
      return false;
    }

    return true;
  }
}