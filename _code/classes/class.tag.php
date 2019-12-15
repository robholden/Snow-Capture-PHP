<?php

class Tag implements IFilter
{
  /**
   *
   * @var SnowCapture
   */
  private $sc;

  /**
   *
   * @var string
   */
  public $value = '';




  public function __construct()
  {
    global $sc;
    $this->sc = $sc;
  }




  /**
   * Add tags to an image
   *
   * @param image $image
   * @param array $tags
   * @return <array,bool,string>
   */
  public function addToImage($image, $tags)
  {
    // Ensure image is valid
    if (! $image->exists())
    {
      return false;
    }

    // Validate tags (if any)
    $_tags = $this->validate($tags);
    if (! $_tags)
    {
      return 'badword';
    }

    // Declare helper
    $sql = new SQLHelper();

    // Clear tags
    // Prepare query
    $sql->delete('sc_image_tags');
    $sql->whereParam('image_id', '=', $image->id);

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Add new tags
    foreach ($tags as $value)
    {
      if (! in_array($value, $image->tags))
      {
        // Prepare query
        $sql->insert('sc_image_tags');
        $sql->values('image_id', $image->id);
        $sql->values('value', $value);

        // Execute query. Break loop on fail
        if (! $sql->execute())
        {
          break;
          return false;
        }
      }
    }

    return true;
  }




  /**
   * {@inheritDoc}
   *
   * @see Filter::current()
   */
  public function current()
  {
    // Validate get value
    if (empty($_GET['tag']))
    {
      return false;
    }

    return explode('|', $_GET['tag']);
  }




  /**
   * Returns tags against image id
   *
   * @param int $id
   * @return <array, string>
   */
  public function getByImage($id)
  {
    // Ensure image is valid
    if (! is_numeric($id))
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('*');
    $sql->from('sc_image_tags');
    $sql->whereParam('image_id', '=', $id);

    // Execute query
    $sql->execute();

    // Make sure are rows
    if (! $sql->hasRows())
    {
      return array();
    }

    // Store
    $_tags = array();

    // Load
    while ($tag = $sql->fetchArray())
    {
      array_push($_tags, $tag['value']);
    }

    // Return
    return $_tags;
  }




  /**
   * Gets list of tags starting with letters
   *
   * @param string $letters
   * @return array
   */
  public function getStartingWith($letters = '')
  {
    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('distinct value');
    $sql->from('sc_image_tags');
    $sql->where('value LIKE "' . $sql->sanitise($letters) . '%"');
    $sql->order('value ASC');

    // Execute query
    $sql->execute();

    // Make sure are rows
    if (! $sql->hasRows())
    {
      return array();
    }

    // Store
    $_tags = array();

    // Load
    while ($tag = $sql->fetchArray())
    {
      array_push($_tags, ucwords($tag['value']));
    }

    // Return
    return $_tags;
  }




  /**
   * Get top tags
   *
   * @param number $count
   */
  public function getTop($count = 10)
  {
    // Validate count
    if (! is_numeric($count))
    {
      return array();
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare select
    $sql->select('tag.value');

    // Prepare from
    $sql->from('sc_images image LEFT OUTER JOIN');
    $sql->from('sc_users user ON user.id = image.user_id RIGHT OUTER JOIN');
    $sql->from('sc_image_tags tag ON tag.image_id = image.id');

    // Prepare where
    $sql->whereParam('image.status', '=', IMAGE_PUBLISHED);

    // Prepare group by
    $sql->groupBy('tag.value');

    // Prepare order
    $sql->order('RAND()');

    // Prepare limit
    $sql->limit('0, 10');

    // Execute query
    $sql->execute();

    // Is there data?
    if (! $sql->hasRows())
    {
      return array();
    }

    // Return data
    $_tags = array();
    while ($_tag = $sql->fetchArray())
    {
      array_push($_tags, $_tag['value']);
    }

    // Return tags
    return $_tags;
  }




  /**
   * Truncates tag to valid site/format
   *
   * @param array $array
   * @return <boolean, array>
   */
  protected function validate($array)
  {
    // Return true if empty
    if (empty($array))
    {
      return true;
    }

    $new_tags = array();
    $badword = false;

    $array = array_slice($array, 0, 11);

    foreach ($array as $key => $string)
    {
      if (! $this->sc->validate->isClean($string))
      {
        $badword = true;
      }

      else
      {
        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        $string = strtolower($string);

        if (strlen($string) > 50)
        {
          $string = substr($string, 50);
        }

        array_push($new_tags, $string);
      }
    }

    return $badword ? false : $new_tags;
  }
}





