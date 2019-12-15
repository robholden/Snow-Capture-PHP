<?php

class ImageManipulator
{
  /**
   *
   * @var int
   */
  protected $width;
  
  /**
   *
   * @var int
   */
  protected $height;
  
  /**
   *
   * @var resource
   */
  protected $image;
 



  /**
   * Image manipulator constructor
   * 
   * @param string $file
   *          OPTIONAL Path to image file or image data as string
   * @return void
   */
  public function __construct($file = null)
  {
    if (null !== $file)
    {
      if (is_file($file))
      {
        $this->setImageFile($file);
      } else
      {
        $this->setImageString($file);
      }
    }
  }




  /**
   * Set image resource from file
   * 
   * @param string $file
   *          Path to image file
   * @return ImageManipulator for a fluent interface
   * @throws InvalidArgumentException
   */
  public function setImageFile($file)
  {
    if (! (is_readable($file) && is_file($file)))
    {
      throw new InvalidArgumentException("Image file $file is not readable");
    }
    
    if (is_resource($this->image))
    {
      imagedestroy($this->image);
    }
    
    list ($this->width, $this->height, $type) = getimagesize($file);
    
    switch ($type)
    {
      case IMAGETYPE_GIF:
        $this->image = imagecreatefromgif($file);
        break;
      case IMAGETYPE_JPEG:
        $this->image = imagecreatefromjpeg($file);
        break;
      case IMAGETYPE_PNG:
        $this->image = imagecreatefrompng($file);
        break;
      default:
        throw new InvalidArgumentException("Image type $type not supported");
    }
    
    return $this;
  }




  /**
   * Set image resource from string data
   * 
   * @param string $data          
   * @return ImageManipulator for a fluent interface
   * @throws RuntimeException
   */
  public function setImageString($data)
  {
    if (is_resource($this->image))
    {
      imagedestroy($this->image);
    }
    
    if (! $this->image = imagecreatefromstring($data))
    {
      throw new RuntimeException('Cannot create image from data string');
    }
    $this->width = imagesx($this->image);
    $this->height = imagesy($this->image);
    return $this;
  }




  /**
   * Resamples the current image
   * 
   * @param int $width
   *          New width
   * @param int $height
   *          New height
   * @param bool $constrainProportions
   *          Constrain current image proportions when resizing
   * @return ImageManipulator for a fluent interface
   * @throws RuntimeException
   */
  public function resample($width, $height, $constrainProportions = true)
  {
    if (! is_resource($this->image))
    {
      throw new RuntimeException('No image set');
    }
    if ($constrainProportions)
    {
      if ($this->height >= $this->width)
      {
        $width = round($height / $this->height * $this->width);
      } else
      {
        $height = round($width / $this->width * $this->height);
      }
    }
    $temp = imagecreatetruecolor($width, $height);
    imagecopyresampled($temp, $this->image, 0, 0, 0, 0, $width, $height, 
        $this->width, $this->height);
    return $this->_replace($temp);
  }




  /**
   * Enlarge canvas
   * 
   * @param int $width
   *          Canvas width
   * @param int $height
   *          Canvas height
   * @param array $rgb
   *          RGB colour values
   * @param int $xpos
   *          X-Position of image in new canvas, null for centre
   * @param int $ypos
   *          Y-Position of image in new canvas, null for centre
   * @return ImageManipulator for a fluent interface
   * @throws RuntimeException
   */
  public function enlargeCanvas($width, $height, array $rgb = array(), $xpos = null, 
      $ypos = null)
  {
    if (! is_resource($this->image))
    {
      throw new RuntimeException('No image set');
    }
    
    $width = max($width, $this->width);
    $height = max($height, $this->height);
    
    $temp = imagecreatetruecolor($width, $height);
    if (count($rgb) == 3)
    {
      $bg = imagecolorallocate($temp, $rgb[0], $rgb[1], $rgb[2]);
      imagefill($temp, 0, 0, $bg);
    }
    
    if (null === $xpos)
    {
      $xpos = round(($width - $this->width) / 2);
    }
    if (null === $ypos)
    {
      $ypos = round(($height - $this->height) / 2);
    }
    
    imagecopy($temp, $this->image, (int) $xpos, (int) $ypos, 0, 0, $this->width, 
        $this->height);
    return $this->_replace($temp);
  }




  /**
   * Crop image
   * 
   * @param int|array $x1
   *          Top left x-coordinate of crop box or array of coordinates
   * @param int $y1
   *          Top left y-coordinate of crop box
   * @param int $x2
   *          Bottom right x-coordinate of crop box
   * @param int $y2
   *          Bottom right y-coordinate of crop box
   * @return ImageManipulator for a fluent interface
   * @throws RuntimeException
   */
  public function crop($x1, $y1 = 0, $x2 = 0, $y2 = 0)
  {
    if (! is_resource($this->image))
    {
      throw new RuntimeException('No image set');
    }
    if (is_array($x1) && 4 == count($x1))
    {
      list ($x1, $y1, $x2, $y2) = $x1;
    }
    
    $x1 = max($x1, 0);
    $y1 = max($y1, 0);
    
    $x2 = min($x2, $this->width);
    $y2 = min($y2, $this->height);
    
    $width = $x2 - $x1;
    $height = $y2 - $y1;
    
    $temp = imagecreatetruecolor($width, $height);
    imagecopy($temp, $this->image, 0, 0, $x1, $y1, $width, $height);
    
    return $this->_replace($temp);
  }




  /**
   * Replace current image resource with a new one
   * 
   * @param resource $res
   *          New image resource
   * @return ImageManipulator for a fluent interface
   * @throws UnexpectedValueException
   */
  protected function _replace($res)
  {
    if (! is_resource($res))
    {
      throw new UnexpectedValueException('Invalid resource');
    }
    if (is_resource($this->image))
    {
      imagedestroy($this->image);
    }
    $this->image = $res;
    $this->width = imagesx($res);
    $this->height = imagesy($res);
    return $this;
  }




  /**
   * Save current image to file
   * 
   * @param string $title          
   * @return void
   * @throws RuntimeException
   */
  public function save($title, $type = IMAGETYPE_JPEG)
  {
    $dir = dirname($title);
    if (! is_dir($dir))
    {
      if (! mkdir($dir, 0755, true))
      {
        throw new RuntimeException('Error creating directory ' . $dir);
      }
    }
    
    try
    {
      switch ($type)
      {
        case IMAGETYPE_GIF:
          if (! imagegif($this->image, $title))
          {
            throw new RuntimeException();
          }
          break;
        case IMAGETYPE_PNG:
          if (! imagepng($this->image, $title))
          {
            throw new RuntimeException();
          }
          break;
        case IMAGETYPE_JPEG:
        default:
          if (! imagejpeg($this->image, $title, 95))
          {
            throw new RuntimeException();
          }
      }
    } catch (Exception $ex)
    {
      throw new RuntimeException('Error saving image file to ' . $title);
    }
  }




  /**
   * Returns the GD image resource
   * 
   * @return resource
   */
  public function getResource()
  {
    return $this->image;
  }




  /**
   * Get current image resource width
   * 
   * @return int
   */
  public function getWidth()
  {
    return $this->width;
  }




  /**
   *
   * @param unknown $source_image_path          
   * @param unknown $thumbnail_image_path          
   * @return boolean
   */
  function createThumbnail($source_image_path, $thumbnail_image_path)
  {
    list ($source_image_width, $source_image_height, $source_image_type) = getimagesize(
        $source_image_path);
    switch ($source_image_type)
    {
      case IMAGETYPE_GIF:
        $source_gd_image = imagecreatefromgif($source_image_path);
        break;
      case IMAGETYPE_JPEG:
        $source_gd_image = imagecreatefromjpeg($source_image_path);
        break;
      case IMAGETYPE_PNG:
        $source_gd_image = imagecreatefrompng($source_image_path);
        break;
    }
    
    if ($source_gd_image === false)
    {
      return false;
    }
    
    $source_aspect_ratio = $source_image_width / $source_image_height;
    $thumbnail_aspect_ratio = THUMBNAIL_WIDTH / THUMBNAIL_HEIGHT;
    
    if ($source_image_width <= THUMBNAIL_WIDTH &&
         $source_image_height <= THUMBNAIL_HEIGHT)
    {
      $thumbnail_image_width = $source_image_width;
      $thumbnail_image_height = $source_image_height;
    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio)
    {
      $thumbnail_image_width = (int) (THUMBNAIL_HEIGHT * $source_aspect_ratio);
      $thumbnail_image_height = THUMBNAIL_HEIGHT;
    } else
    {
      $thumbnail_image_width = THUMBNAIL_WIDTH;
      $thumbnail_image_height = (int) (THUMBNAIL_WIDTH / $source_aspect_ratio);
    }
    
    $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, 
        $thumbnail_image_height);
    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, 
        $thumbnail_image_width, $thumbnail_image_height, $source_image_width, 
        $source_image_height);
    
    $img_disp = imagecreatetruecolor(THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);
    $backcolor = imagecolorallocate($img_disp, 0, 0, 0);
    imagefill($img_disp, 0, 0, $backcolor);
    imagecopy($img_disp, $thumbnail_gd_image, 
        (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), 
        (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, 
        imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));
    
    imagejpeg($img_disp, $thumbnail_image_path, 90);
    imagedestroy($source_gd_image);
    imagedestroy($thumbnail_gd_image);
    imagedestroy($img_disp);
    
    return true;
  }




  /**
   * Get current image height
   * 
   * @return int
   */
  public function getHeight()
  {
    return $this->height;
  }
}
?>