<?php

/**
 * Object that represents an image
 * 
 * @author Robert Holden
 */
class Image
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
  public $activity = '';
  
  /**
   *
   * @var integer
   */
  public $activityID = 0;
  
  /**
   *
   * @var string
   */
  public $altitude = '';
  
  /**
   *
   * @var integer
   */
  public $altitudeID = 0;
  
  /**
   *
   * @var string
   */
  public $country = '';
  
  /**
   *
   * @var integer
   */
  public $countryID = 0;
  
  /**
   *
   * @var string
   */
  public $dateCreated = '';
  
  /**
   *
   * @var string
   */
  public $datePublished;
  
  /**
   *
   * @var string
   */
  public $dateTaken = '';
  
  /**
   *
   * @var string
   */
  public $description = '';
  
  /**
   *
   * @var integer
   */
  public $displayID = 0;
  
  /**
   *
   * @var string
   */
  public $filePath = '';
  
  /**
   *
   * @var string
   */
  public $fileType = '';
  
  /**
   *
   * @var boolean
   */
  public $hasGeo = false;
  
  /**
   *
   * @var boolean
   */
  public $hasRealGeo = false;
  
  /**
   *
   * @var integer
   */
  public $height = 0;
  
  /**
   *
   * @var integer
   */
  public $id = 0;
  
  /**
   *
   * @var string
   */
  public $location = 'Unkown';
  
  /**
   *
   * @var integer
   */
  public $likes = 0;
  
  /**
   *
   * @var float
   */
  public $latitude = 0;
  
  /**
   *
   * @var float
   */
  public $longitude = 0;
  
  /**
   *
   * @var float
   */
  public $rating = 0;
  
  /**
   *
   * @var string
   */
  public $resort = '';
  
  /**
   *
   * @var integer
   */
  public $resortID = 0;
  
  /**
   *
   * @var boolean
   */
  public $showCover = false;
  
  /**
   *
   * @var boolean
   */
  public $showMap = true;
  
  /**
   *
   * @var integer
   */
  public $status = 0;
  
  /**
   *
   * @var array
   */
  public $tags = array();
  
  /**
   *
   * @var string
   */
  public $thumbnails = array();
  
  /**
   *
   * @var string
   */
  public $title = '';
  
  /**
   *
   * @var integer
   */
  public $width = 0;
  
  /**
   *
   * @var User
   */
  public $user = null;
  
  /**
   *
   * @var string
   */
  public $username = '';




  /**
   * Construct
   * 
   * @param string $id          
   */
  public function __construct($id = NULL)
  {
    global $sc;
    $this->sc = $sc;

    $this->dateTaken = date("d-m-Y");
    
    if (! empty($id))
    {
      $this->load($id);
    }
  }




  /**
   * Destruct
   */
  public function __destruct()
  {}




  /**
   * Add tags to an image
   * 
   * @param array $tags          
   * @return <boolean, string>
   */
  public function addTags($tags = array())
  {
    // Ensure image exists
    if (! $this->exists())
    {
      return false;
    }
    
    // Add tags
    return (new Tag())->addToImage($this, $tags);
  }




  /**
   * Creates or updates an image
   * 
   * @return <boolean, string>
   */
  public function append()
  {
    // Validate vars
    $output = $this->validate();
    
    if ($output !== true)
    {
      return false;
    }
    
    // Reset output
    $output = false;
    
    // Prepare vars
    $_cover = $this->showCover ? 1 : 0;
    $_show = $this->showMap ? 1 : 0;
    $_ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare insert/update
    $sql->insertUpdate('sc_images', ! $this->exists());
    
    // Prepare default values
    $sql->values('activity_id', $this->activityID);
    $sql->values('altitude_id', $this->altitudeID);
    $sql->values('title', $this->title);
    $sql->values('country_id', $this->countryID);
    $sql->values('resort_id', $this->resortID);
    $sql->values('description', $this->description);
    $sql->values('show_map', $_show);
    $sql->values('show_cover', $_cover);
    $sql->values('date_taken', $this->dateTaken);
    $sql->values('status', $this->status);
    $sql->values('user_id', $this->user->id);
    if ($this->datePublished) { $sql->values('date_published', $this->datePublished); }
    
    // Specific values for insert
    if (! $this->exists())
    {
      $sql->values('ip_address', $_ip_address);
      $sql->values('file', $this->filePath);
      $sql->values('filetype', $this->fileType);
      $sql->values('latitude', $this->latitude);
      $sql->values('longitude', $this->longitude);
      $sql->values('width', $this->width);
      $sql->values('height', $this->height);
    }
    
    // Prepare where for update (ignored for insert)
    $sql->whereParam('id', '=', $this->id);
    $sql->whereParam('status', '>', LEVEL_DELETED, 'AND');
    
    // Execute query
    $output = $sql->execute() ? true : 'Could not ' . ($this->exists() ? 'update' : 'create');
    
    if ($output === true)
    {
      $this->load($sql->lastID());
    }
    
    // Return response
    return $output;
  }




  /**
   * Build filter from url options
   * 
   * @return FilterImage
   */
  public function buildFilterFromURL()
  {
    $page = $this->sc->common->page();
    $pageType = $this->sc->vars->pageType;
    
    // Prepare filter to search
    $_filter = new FilterImage();
    $_filter->keyword = $this->sc->common->keyword();
    $_filter->activity = $this->sc->common->activity();
    $_filter->altitude = $this->sc->common->altitude();
    $_filter->date = $this->sc->common->taken();
    $_filter->location = $this->sc->common->location();
    $_filter->tag = $this->sc->common->tag();
    $_filter->likes = ($pageType == 'likes');
    $_filter->page = $this->sc->common->page();
    
    $_sort = $this->sc->common->sort();
    $_filter->order = (! $_sort ? ($this->sc->vars->pageType == 'search' ? false : 'uploaded-desc') : $_sort);
    $_filter->randomed = $this->sc->common->randomed();
    
    $_filter->status = array();
    switch ($pageType)
    {
      case 'drafts':
        array_push($_filter->status, IMAGE_DRAFT);
        $_filter->user = $this->sc->user;
        break;
      
      case 'processing':
        array_push($_filter->status, IMAGE_PROCESSING);
        $_filter->user = $this->sc->user;
        break;
      
      case 'privates':
        $_filter->user = $this->sc->user;
        array_push($_filter->status, IMAGE_PRIVATE);
        break;
      
      case 'user':
      case 'choosing':
      case 'choosing_cover':
      case 'likes':
        $_filter->user = $this->sc->common->user();
        array_push($_filter->status, IMAGE_PUBLISHED);
        break;
      
      default:
        array_push($_filter->status, IMAGE_PUBLISHED);
        break;
    }
    
    return $_filter;
  }




  /**
   * Count number of "active" images for user
   * 
   * @param User $user          
   * @return number
   */
  public function countActiveImagesForUser($user)
  {
    // Ensure user exists
    if (! $user->exists())
    {
      return 0;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_images');
    $sql->whereParam('user_id', '=', $user->id);
    $sql->whereParam('status', '=', IMAGE_PUBLISHED, 'AND');
    
    // Execute query
    if (! $sql->execute())
    {
      return 0;
    }
    
    // Return count
    return $sql->countRows();
  }




  /**
   * Counts number of images by user (exluding removed images)
   * 
   * @param User $user          
   * @return number
   */
  public function countImagesForUser($user, $level = null)
  {
    // Ensure user exists
    if (! $user->exists())
    {
      return 0;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_images');
    $sql->whereParam('user_id', '=', $user->id);
    
    if (is_null($level))
    {
      $sql->whereParam('status', '>', IMAGE_ANY, 'AND');
    } 

    else 
      if (! is_int($level))
      {
        return 0;
      } 

      else
      {
        $sql->whereParam('status', '=', $level, 'AND');
      }
    
    // Execute query
    if (! $sql->execute())
    {
      return 0;
    }
    
    // Return count
    return $sql->countRows();
  }




  /**
   * Deletes current image
   * 
   * @return boolean
   */
  public function delete($user, $comment = 'No reason provided')
  {
    // Ensure image exists
    if (! $this->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query to delete
    $sql->update('sc_images');
    $sql->values('status', IMAGE_DELETED);
    $sql->whereParam('id', '=', $this->id);
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Delete physical thumbnail
    foreach ($this->thumbnails as $path)
    {
      $path = WEB_ROOT . $path;
      if (file_exists($path))
      {
        @unlink($path);
      }
    }
    
    // Delete physical image
    if (file_exists(WEB_ROOT . $this->filePath))
    {
      @unlink(WEB_ROOT . $this->filePath);
    }
    
    // Insert rejection commment
    $sql->insert('sc_image_rejections');
    $sql->values('image_id', $this->id);
    $sql->values('user_id', $user->id);
    $sql->values('reason', strip_tags($comment));
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Delete associated likes
    $sql->delete('sc_image_likes');
    $sql->whereParam('image_id', '=', $this->id);
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Delete associated ratings
    $sql->delete('sc_image_ratings');
    $sql->whereParam('image_id', '=', $this->id);
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Success :)
    return true;
  }




  /**
   * Returns whether the object is deleted
   * 
   * @return boolean
   */
  public function deleted()
  {
    return ($this->status == IMAGE_DELETED && $this->id > 0);
  }




  /**
   * Returns the reason for deletion
   * 
   * @return string
   */
  public function deletedReason()
  {
    // Ensure image is deleted
    if (! $this->deleted())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('reason');
    $sql->from('sc_image_rejections');
    $sql->whereParam('image_id', '=', $this->id);
    $sql->limit('1');
    
    // Execute query
    $sql->execute();
    
    // Does it have rows?
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Return reason
    return $sql->fetchAssoc()['reason'];
  }




  /**
   * Returns whether the object is set
   * 
   * @return boolean
   */
  public function exists()
  {
    return ($this->status > IMAGE_DELETED && $this->id > 0);
  }




  /**
   * Returns all images for user (exluding removed images)
   * 
   * @param User $user          
   * @return <array, boolean>
   */
  public function getAllForUser($user)
  {
    // Ensure user exists
    if (! $user->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->prepareImage();
    
    // Prepare query
    $sql->whereParam('image.user_id', '=', $user->id);
    $sql->whereParam('image.status', '>', IMAGE_DELETED, 'AND');
    $sql->whereParam('user.status', '>=', LEVEL_USER, 'AND');
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Store
    $_images = array();
    
    // Load
    while ($image = $sql->fetchArray())
    {
      array_push($_images, $this->assign($image));
    }
    
    // Return
    return $_images;
  }




  /**
   * Returns all "processing" images for admin
   * 
   * @param User $user          
   * @return <array, boolean>
   */
  public function getAllProcessingImages($user)
  {
    // Ensure admin exists
    if (! $user->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->prepareImage();
    
    // Prepare query
    $sql->whereParam('image.status', '=', IMAGE_PROCESSING);
    $sql->order('image.date_created ASC');
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Store
    $_images = array();
    
    // Load
    while ($image = $sql->fetchArray())
    {
      array_push($_images, $this->assign($image));
    }
    
    // Return
    return $_images;
  }




  /**
   * Returns the next "processing" image for admin
   * 
   * @param User $user          
   * @return <array, boolean>
   */
  public function getNextProcessingImage($user)
  {
    // Ensure admin exists
    if (! $user->isAdmin())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->prepareImage();
    
    // Prepare query
    $sql->whereParam('image.status', '=', IMAGE_PROCESSING);
    $sql->order('image.date_created ASC');
    $sql->limit('1');
    
    // Execute query
    $sql->execute();
    
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Store
    $_images = array();
    
    // Return
    return $this->assign($sql->fetchAssoc());
  }


  /**
   * Get images by lat long values
   *
   * @param float $lat
   * @param float $lng
   * @param string $latest
   * @return FilterImage
   */
  public function getByGeo($lat, $lng, $latest)
  {
    $_filter = new FilterImage();
    $_filter->latlng = array($lat, $lng);
    $_filter->status = array(IMAGE_PUBLISHED);
    $_filter->order = 'uploaded-desc';
    $_filter->page = $this->sc->common->page();
    $_filter->latest = $latest;
  
    $_filter = $this->search($_filter);
  
    // Return
    return $_filter;
  }


  /**
   * Get images by lat long values
   * 
   * @param float $NE  
   * @param float $SW       
   * @param string $latest         
   * @return FilterImage
   */
  public function getByRegion($NE, $SW, $latest)
  {    
    $_filter = new FilterImage();
    $_filter->region = array($NE[0], $NE[1], $SW[0], $SW[1]);
    $_filter->status = array(IMAGE_PUBLISHED);
    $_filter->order = 'uploaded-desc';
    $_filter->page = $this->sc->common->page();
    $_filter->latest = $latest;
    
    $_filter = $this->search($_filter);
    
    // Return
    return $_filter;
  }




  /**
   * Gets the latest image against a location
   * 
   * @param string $location          
   * @return <Image, array>
   */
  public function getByLocation($location, $all = false)
  {
    // Validate location
    if (empty($location))
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select image
    $sql->prepareImage();
    
    // Prepare where
    $sql->where('image.country_id IN (SELECT id from sc_countries WHERE name = "' . $sql->sanitise($location) . '")');
    $sql->where('image.resort_id IN (SELECT id from sc_resorts WHERE name = "' . $sql->sanitise($location) . '")', 'OR');
    
    // Prepare limit
    if (! $all)
    {
      $sql->limit(1);
    }
    
    // Prepare order
    $sql->order('image.id DESC');
    
    // Execute query
    $sql->execute();
    
    // Make sure there is data
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // If getting all then build into array
    if ($all)
    {
      // Store
      $_images = array();
      
      // Load
      while ($image = $sql->fetchArray())
      {
        array_push($_images, $this->assign($image));
      }
      
      // Return
      return $_images;
    }
    
    // Assign data to obj
    return $this->assign($sql->fetchAssoc());
  }




  /**
   * Gets the latest image against a tag
   * 
   * @param string $tag          
   * @return Image
   */
  public function getByTag($tag)
  {
    // Validate tag
    if (empty($tag))
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select image
    $sql->prepareImage();
    
    // Prepare where
    $sql->where('image.id IN (SELECT image_id from sc_image_tags WHERE value = "' . $sql->sanitise($tag) . '")');
    
    // Prepare limit
    $sql->limit(1);
    
    // Prepare order
    $sql->order('image.id DESC');
    
    // Execute query
    $sql->execute();
    
    // Make sure there is data
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Assign data to obj
    return $this->assign($sql->fetchAssoc());
  }



  /**
   * Publishes images
   * 
   * @return boolean
   */
  public function publish()
  {
    // Ensure image exists
    if (! $this->exists())
    {
      return array();
    }
    
    // Set status and date
    $this->status = IMAGE_PUBLISHED;
    $this->datePublished = date("Y-m-d H:i:s");
    
    return $this->append();
  }
  

  /**
   * Get images nearby for image
   * 
   * @param float $lat          
   * @param float $lon          
   * @param number $count          
   * @return boolean
   */
  public function getNearby($lat, $lon, $count = 5)
  {
    // Validate values
    if (! is_numeric($lat) || ! is_numeric($lon))
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select image
    $sql->prepareImage();
    $sql->select('
      SQRT(
        POW(69.1 * (image.latitude - ' . $lat . '), 2) + 
        POW(69.1 * (' . $lon . ' - image.longitude) * COS(image.latitude / 57.3), 2)
      ) AS distance');
    
    // Prepare where
    $sql->whereParam('image.status', '=', IMAGE_PUBLISHED);
    $sql->whereParam('user.status', '>=', LEVEL_USER, 'AND');
    
    // Prepare having
    $sql->having('distance < 25');
    $sql->having('distance > 0', 'AND');
    
    // Prepare limit
    $sql->limit("0, " . $count);
    
    // Prepare order
    $sql->order('distance');
    
    // Execute query
    $sql->execute();
    
    // Make sure there more than 2 rows
    if (! $sql->countRows() > 2)
    {
      return false;
    }
    
    // Store
    $_images = array();
    
    // Load
    while ($image = $sql->fetchArray())
    {
      array_push($_images, $this->assign($image));
    }
    
    // Return
    return $_images;
  }




  /**
   * Returns next "draft" image for user
   * 
   * @param User $user          
   * @return <array, boolean>
   */
  public function getDraftForUser($user)
  {
    // Ensure user exists
    if (! $user->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->prepareImage();
    
    // Prepare query
    $sql->whereParam('image.user_id', '=', $user->id);
    $sql->whereParam('image.status', '=', IMAGE_DRAFT, 'AND');
    
    if ($this->exists())
    {
      $sql->whereParam('image.id', '<>', $this->id, 'AND');
    }
    
    // Order by
    $sql->order('image.date_created ASC');
    
    // Limit
    $sql->limit(1);
    
    // Execute query
    $sql->execute();
    
    // Make sure there is data
    if (! $sql->hasRows())
    {
      return false;
    }
    
    // Assign data to obj
    return $this->assign($sql->fetchAssoc());
  }




  /**
   * Gets tags for this image
   */
  public function getTags()
  {
    // Ensure image exists
    if (! $this->exists())
    {
      return array();
    }
    
    $this->tags = (new Tag())->getByImage($this->id);
    return $this->tags;
  }




  /**
   * Returns random top n images
   * 
   * @param integer $count          
   * @return <array, boolean>
   */
  public function getTop($count, $spotlight = true)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare select
    $sql->prepareImage();
    $sql->from('LEFT OUTER JOIN sc_image_spotlight spotlight ON image.id = spotlight.image_id');
    
    // Prepare query
    $sql->whereParam('image.status', '=', IMAGE_PUBLISHED);
    $sql->whereParam('user.status', '>=', LEVEL_USER, 'AND');
    
    if ($spotlight)
    {
      $sql->where('image.id IN (SELECT image_id FROM sc_image_spotlight)', 'AND');
    }
    
    // Order by
    $sql->order('spotlight.datetime DESC');
    
    // Limit
    $sql->limit('0, ' . $count);
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Store
    $_images = array();
    
    // Load
    while ($image = $sql->fetchArray())
    {
      array_push($_images, $this->assign($image));
    }
    
    // Return
    return $_images;
  }




  /**
   * Returns whether a user has liked this image
   * 
   * @param User $user          
   * @return boolean
   */
  public function isLike($user)
  {
    // Ensure image && user exists
    if (! $this->exists() || ! $user->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_image_likes');
    $sql->whereParam('image_id', '=', $this->id);
    $sql->whereParam('user_id', '=', $user->id, 'AND');
    
    // Execute query
    $sql->execute();
    
    // Has rows
    return $sql->hasRows();
  }




  /**
   * Returns whether the user is the owner of this image
   * 
   * @param User $user          
   * @return boolean
   */
  public function isOwner($user)
  {
    // Ensure image && user exists
    if (! $this->exists() || ! $user->exists())
    {
      return false;
    }
    
    return ($user->username == strtolower($this->username));
  }




  public function removeGeo()
  {
    // Ensure image exists
    if (! $this->exists())
    {
      return false;
    }
    
    // Delcare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->update('sc_images');
    $sql->values('latitude', 0);
    $sql->values('longitude', 0);
    $sql->whereParam('id', '=', $this->id);
    
    // Execute query
    return $sql->execute();
  }




  /**
   * Searches images
   * 
   * @param FilterImage $filter          
   * @return FilterImage
   */
  public function search($filter)
  {
    // Store in local var
    $_filter = $filter;
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Let's start building the query
    //
    //
    // Prepare select
    // This takes care of the select & from
    $sql->prepareImage();
    
    // Now the meaty where clause...
    //
    //
    
    // Track whether we need a separator
    $_sep = false;
    
    // Let's start with the arrays
    //
    //
    
    // Activities
    if (! empty($_filter->activity))
    {
      $_values = array();
      foreach ($_filter->activity as $val)
      {
        array_push($_values, '"' . $sql->sanitise($val) . '"');
      }
      $sql->where('( activity.type IN (' . implode(',', $_values) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Altitudes
    if (! empty($_filter->altitude))
    {
      $_values = array();
      foreach ($_filter->altitude as $val)
      {
        array_push($_values, '"' . $sql->sanitise($val) . '"');
      }
      $sql->where('( altitude.height IN (' . implode(',', $_values) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Countries
    if (! empty($_filter->country))
    {
      $_values = array();
      foreach ($_filter->country as $val)
      {
        array_push($_values, '"' . $sql->sanitise($val) . '"');
      }
      $sql->where('( country.country_code IN (' . implode(',', $_values) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Resorts
    if (! empty($_filter->resort))
    {
      $_values = array();
      foreach ($_filter->resort as $val)
      {
        array_push($_values, '"' . $sql->sanitise($val) . '"');
      }
      $sql->where('( resort.name IN (' . implode(',', $_values) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Locations
    if (! empty($_filter->location))
    {
      $_values = array();
      foreach ($_filter->location as $val)
      {
        array_push($_values, '"' . $sql->sanitise($val) . '"');
      }
      $sql->where('(resort.name IN (' . implode(',', $_values) . ') OR country.name IN (' . implode(',', $_values) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Tags
    if (! empty($_filter->tag))
    {
      $_values = array();
      foreach ($_filter->tag as $val)
      {
        array_push($_values, '"' . $sql->sanitise($val) . '"');
      }
      $sql->where('( (image.id IN (SELECT image_id FROM sc_image_tags WHERE value IN (' . implode(',', $_values) . '))) )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Date taken
    if (! empty($_filter->date))
    {
      $sql->where('(', ($_sep ? 'AND' : ''));
      foreach ($_filter->date as $key => $val)
      {
        $sql->where('(image.date_taken LIKE "' . $sql->sanitise($val) . '-%")', ($key == 0 ? '' : 'OR'));
      }
      $sql->where(')');
      $_sep = true;
    }
    
    // Distance/Lat/Lng
    if (! empty($_filter->latlng))
    {
      $sql->select('
      SQRT(
        POW(69.1 * (image.latitude - ' . $_filter->latlng[0] . '), 2) +
        POW(69.1 * (' . $_filter->latlng[1] . ' - image.longitude) * COS(image.latitude / 57.3), 2)
      ) AS distance');

      $sql->having('distance < ' . $_filter->distance);
      $sql->having('distance > 0', 'AND');
    }
    
    // Region
    if (! empty($_filter->region))
    {
      $sql->whereParam('image.latitude', '<=', $_filter->region[0], ($_sep ? 'AND' : ''));
      $sql->whereParam('image.longitude', '<=', $_filter->region[1], 'AND');
      
      $sql->whereParam('image.latitude', '>=', $_filter->region[2], 'AND');
      $sql->whereParam('image.longitude', '>=', $_filter->region[3], 'AND');

      $_sep = true;
    }
    
    // Status'
    if (! empty($_filter->status))
    {
      $sql->where('( image.status IN (' . implode(',', $_filter->status) . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Now on to the individual ones
    //
    //
    
    // User, at this point it just searches against this specific user
    if (is_object($_filter->user) && ! $_filter->likes)
    {
      $sql->whereParam('image.user_id', '=', $_filter->user->id, ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Latest *for published images only
    if ($_filter->latest !== false)
    {
      $sql->whereParam('image.date_published', '>', $_filter->latest, $_sep ? 'AND' : '');
      $_sep = true;
    }
    
    // Keyword
    if ($_filter->keyword !== false)
    {
      $_val = $sql->sanitise($_filter->keyword);
      $sql->where('( 
            image.title LIKE "%' . $_val . '%" OR  resort.name LIKE "' . $_val . '" OR  country.name LIKE "' . $_val . '"
      )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Are we searching likes?
    if ($_filter->likes === true && is_object($_filter->user))
    {
      $sql->where('( image.id IN (SELECT image_id FROM sc_image_likes WHERE user_id = ' . $_filter->user->id . ') )', ($_sep ? 'AND' : ''));
      $_sep = true;
    }
    
    // Order
    switch ($_filter->order)
    {
      case 'taken-asc':
        $sql->order('image.date_taken ASC');
        break;
      
      case 'taken-desc':
        $sql->order('image.date_taken DESC');
        break;
      
      case 'uploaded-asc':
        $sql->order('image.id ASC');
        break;
      
      case 'uploaded-desc':
        $sql->order('image.id DESC');
        break;
      
      case 'rating-asc':
        $sql->order('rating ASC');
        $sql->order('(SELECT COUNT(*) FROM sc_image_ratings WHERE image_id = image.id) ASC');
        break;
      
      case 'rating-desc':
        $sql->order('rating DESC');
        $sql->order('(SELECT COUNT(*) FROM sc_image_ratings WHERE image_id = image.id) DESC');
        break;
      
      case 'likes-asc':
        $sql->order('likes ASC');
        break;
      
      case 'likes-desc':
        $sql->order('likes DESC');
        break;
      
      default:
        $sql->order('RAND()');
        if (! empty($_filter->randomed) && $_filter->page > 1)
        {
          $_filter->total += sizeof($_filter->randomed);
          $sql->where('image.id NOT IN (' . implode(',', $_filter->randomed) . ')', ($_sep ? 'AND' : ''));
          $_sep = true;
        }
        
        $_filter->limit = ROW_LIMIT;
        break;
    }
    
    // Copy query for maps
    $_msql = clone $sql;
    $_msql->whereParam('image.latitude', '>', '0', ($_sep ? 'AND' : ''));
    $_msql->whereParam('image.longitude', '>', '0', 'AND');
    
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
    
    // Total rows
    $_filter->total += $sql->executeCount();
    
    // Execute query
    if (! $sql->execute() || ! $_msql->execute())
    {
      return $_filter;
    }
    
    // Load
    while ($image = $sql->fetchArray())
    {
      $_image = $this->assign($image);
      array_push($_filter->results, $_image);
    }
    
    while ($image = $_msql->fetchArray())
    {
      $_image = $this->assign($image);
      array_push($_filter->maps, $_image);
    }
    
    // Return image data
    return $_filter;
  }




  /**
   * Maps a datarow to an Image object
   * 
   * @param array $this_image          
   * @param Image $image
   *          OPTIONAL
   * @return <boolean, Image>
   */
  protected function assign($read, $write = null)
  {
    // Ensure there's data
    if (empty($read))
    {
      return false;
    }
    
    $_write = ($write == null) ? new Image() : $write;
    $_write->activity = $read['activity'];
    $_write->activityID = $read['activity_id'];
    $_write->altitude = $read['altitude'];
    $_write->altitudeID = $read['altitude_id'];
    $_write->country = $read['country_name'];
    $_write->countryID = $read['country_id'];
    $_write->showCover = $read['show_cover'];
    $_write->dateCreated = $read['date_created'];
    $_write->datePublished = $read['date_published'];
    $_write->dateTaken = $read['date_taken'];
    $_write->description = $read['description'];
    $_write->displayID = $this->sc->security->encrypt($read['id']);
    $_write->likes = $read['likes'];
    $_write->title = strip_tags($read['title']);
    $_write->fileType = empty($read['filetype']) ? 'jpeg' : $read['filetype'];
    $_write->filePath = UPLOAD_DIR . $read['file'] . '.' . $_write->fileType;
    $_write->id = $read['id'];
    $_write->rating = $read['rating'];
    $_write->resort = $read['resort_name'];
    $_write->resortID = $read['resort_id'];
    $_write->showMap = $read['show_map'];
    $_write->status = $read['status'];
    $_write->thumbnails = array(
        'custom' => UPLOAD_DIR . THUMBNAIL_PATH_CUSTOM . $read['file'] . '.jpg',
        'system' => UPLOAD_DIR . THUMBNAIL_PATH_SYSTEM . $read['file'] . '.jpg'
    );
    $_write->user = new User($read['userid']);
    $_write->username = $_write->user->displayName;
    
    $_write->latitude = $read['image_latitude'] > 0 ? $read['image_latitude'] : (! empty($read['resort_latitude']) ? $read['resort_latitude'] : (! empty($read['country_latitude']) ? $read['country_latitude'] : 0));
    $_write->longitude = $read['image_longitude'] > 0 ? $read['image_longitude'] : (! empty($read['resort_longitude']) ? $read['resort_longitude'] : (! empty($read['country_longitude']) ? $read['country_longitude'] : 0));
    
    $_write->hasRealGeo = ($read['image_latitude'] > 0 && $read['image_longitude'] > 0);
    $_write->hasGeo = ($_write->latitude > 0 && $_write->longitude > 0);
    
    $_write->width = $read['width'];
    $_write->height = $read['height'];
    
    $_write->location = ! empty($_write->resort) ? $_write->resort : (! empty($_write->country) ? $_write->country : $_write->location);
    
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
    
    // Prepare select/from
    $sql->prepareImage();
    
    // Prepare where
    $sql->whereParam('image.id', '=', $id);
    
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
   * Validates current image
   * 
   * @return <boolean, string>
   */
  protected function validate()
  {
    $output = true;
    
    // Activity
    if (! is_numeric($this->activityID))
    {
      $output = 'activity';
    }
    
    // Altitude
    if (! is_numeric($this->altitudeID))
    {
      $output = 'altitude';
    }
    
    // Country
    if (! is_numeric($this->countryID))
    {
      $output = 'country';
    }
    
    // Description
    if (strlen($this->description) > 1500)
    {
      $output = 'description';
    }
    
    if (! $this->sc->validate->isClean($this->description) || ! $this->sc->validate->isClean($this->title))
    {
      $output = 'badword';
    }
    
    // Date
    if (! $this->sc->validate->validateDate($this->dateTaken))
    {
      $this->dateTaken = date("Y-m-d", strtotime($this->dateTaken));
      if (! $this->sc->validate->validateDate($this->dateTaken))
      {
        $output = 'date_taken';
      }
    }
    
    // File Name
    if (strlen($this->title) == 0 || strlen($this->title) > 255)
    {
      $output = 'title';
    }
    
    // Height
    if (! is_numeric($this->height))
    {
      $output = 'height';
    }
    
    // Status
    if (! is_numeric($this->status))
    {
      $output = 'status';
    }
    
    // Resort
    if (! is_numeric($this->resortID))
    {
      $output = 'resort';
    }
    
    // Width
    if (! is_numeric($this->width))
    {
      $output = 'width';
    }
    
    return $output;
  }
}

?>