<?php
require_once 'Sitemap.php';

/**
 * Common object for generic site operations.
 * 
 * @author Robert Holden
 */
class Common
{
  
  /**
   *
   * @var Browser
   */
  private $browser;
  
  /**
   *
   * @var SnowCapture
   */
  private $sc;
  
  /**
   *
   * @var string
   */
  private $siteURL;
  
  /**
   *
   * @var int
   */
  private $userLevel = false;
  
  /**
   *
   * @var boolean
   */
  public $isMobile = false;




  /**
   * Construct
   */
  public function __construct()
  {
    global $sc;
    $this->sc = $sc;
    
    // Detect mobile
    if (class_exists('Browser'))
    {
      $browser = new Browser();
      $this->isMobile = ($browser->isMobile() && ! $browser->isTablet());
      
      // Get browser
      $this->browser = $browser->getBrowser();
    }
    
    // Set site URL base on site mode
    $this->siteURL = "";
    switch (DEVELOPMENT)
    {
      case 1:
        $this->siteURL = SITE_URL_LOCAL;
        break;
      
      case 2:
        $this->siteURL = SITE_URL_BETA;
        break;
      
      default:
        $this->siteURL = SITE_URL_LIVE;
        break;
    }
  }




  /**
   * Destruct
   */
  public function __destruct()
  {}




  /**
   * Returns site's URL
   * 
   * @return string
   */
  public function siteURL()
  {
    return $this->siteURL;
  }




  /**
   * Returns current full url
   */
  public function currentURL()
  {
    $host = $this->siteURL();
    $slug = $_SERVER["REQUEST_URI"];
    return $host . $slug;
  }




  /**
   * Creates site map!
   */
  public function createSitemap()
  {
    $sitemap = new Sitemap($this->siteURL());
    $sitemap->setPath(WEB_ROOT . 'content/');
    $sitemap->addItem('/', '1.0', 'daily', 'Today');
    $sitemap->addItem('/policies/terms', '0.6', 'yearly', '25-10-2015');
    $sitemap->addItem('/policies/privacy', '0.6', 'yearly', '25-10-2015');
    $sitemap->addItem('/policies/cookies', '0.6', 'yearly', '25-10-2015');
    $sitemap->addItem('/about-us', '0.8', 'monthly');
    $sitemap->addItem('/stay-updated', '0.8', 'weekly');
    $sitemap->addItem('/about-us', '0.8', 'monthly');
    $sitemap->addItem('/how-it-works', '0.8', 'monthly');
    $sitemap->addItem('/forgot-password', '0.8', 'monthly');
    $sitemap->addItem('/sign-in', '0.8', 'monthly');
    $sitemap->addItem('/sign-up', '0.8', 'monthly');
    $sitemap->addItem('/search', '0.8', 'daily');
    
    // Get all images
    $sql = new SQLHelper();
    $sql->prepareImage();
    $sql->whereParam('image.status', '=', IMAGE_PUBLISHED);
    $sql->order('image.date_published ASC');
    
    if ($sql->execute())
    {
      while ($image = $sql->fetchArray())
      {
        $sitemap->addItem('/capture/' . $this->sc->security->encrypt($image['id']), '0.8', 'daily');
      }
    }
    
    $sitemap->createSitemapIndex($this->siteURL() . '/content/');
  }




  /**
   * Redirects user to page not found
   */
  public function goNoWhere()
  {
    $this->goToURL('/errors/notfound');
  }




  /**
   * Redirects user to homepage
   */
  public function goHome()
  {
    $this->goToURL('/');
  }




  /**
   * Redirects user to specified url
   * 
   * @param string $url          
   */
  public function goToURL($url)
  {
    header('Location: ' . $url);
    exit();
  }




  /**
   * Returns previous url (only if it came from site)
   * 
   * @return string
   */
  public function previousURL()
  {
    return isset($_SERVER['HTTP_REFERER']) ? (! strpos($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_URI'])) ? $_SERVER['HTTP_REFERER'] : '/' : '/';
  }




  /**
   * Makes sure session is POST
   */
  public function ensurePost($redirect = true)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
      if ($redirect)
      {
        $this->goNoWhere();
      }
      
      return false;
    } 

    else
    {
      return true;
    }
  }




  /**
   * Returns Browser
   * 
   * @return int
   */
  public function browser()
  {
    return $this->browser;
  }




  /**
   * Returns if a filter property has been set
   * 
   * @return boolean
   */
  public function filterSet()
  {
    return (! empty($_GET['taken']) || ! empty($_GET['location']) || ! empty($_GET['activity']) || ! empty($_GET['altitude']) || ! empty($_GET['country']) || ! empty($_GET['resort']) || ! empty($_GET['month']) || ! empty($_GET['year']) || ! empty($_GET['tag']));
  }




  /**
   * Returns current page
   * 
   * @return integer
   */
  public function page()
  {
    return ! empty($_GET['page']) ? is_numeric($_GET['page']) ? $_GET['page'] : 1 : 1;
  }




  /**
   * Returns current user from url, or false if not set
   * 
   * @return <boolean, User>
   */
  public function user()
  {
    return ! empty($_GET['user']) ? new User($_GET['user']) : new User();
  }




  /**
   * Returns current message from url, or false if not set
   * 
   * @return <boolean, Message>
   */
  public function message()
  {
    return ! empty($_GET['message']) ? new Message($this->sc->security->decrypt($_GET['message'])) : false;
  }




  /**
   * Returns current image from url, or false if not set
   * 
   * @return Image
   */
  public function image()
  {
    return ! empty($_GET['image']) ? new Image($this->sc->security->decrypt($_GET['image'])) : false;
  }




  /**
   * Returns current keyword from url, or false if not set
   * 
   * @return <boolean, string>
   */
  public function keyword()
  {
    return ! empty($_GET['q']) ? strip_tags($_GET['q']) : false;
  }




  /**
   * Returns array of activities from url, or false if not set
   * Seperated by '|'
   * 
   * @return <boolean, array>
   */
  public function activity()
  {
    $activity = false;
    
    if (! empty($_GET['activity']) && is_string($_GET['activity']))
    {
      $activity = explode('|', $_GET['activity']);
    }
    
    return $activity;
  }




  /**
   * Returns array of altitudes from url, or false if not set
   * Seperated by '|'
   * 
   * @return <boolean, array>
   */
  public function altitude()
  {
    $altitude = false;
    
    if (! empty($_GET['altitude']) && is_string($_GET['altitude']))
    {
      $altitude = explode('|', $_GET['altitude']);
    }
    
    return $altitude;
  }




  /**
   * Returns array of locations from url, or false if not set
   * Seperated by '|'
   * 
   * @return <boolean, array>
   */
  public function location()
  {
    $location = false;
    
    if (! empty($_GET['location']) && is_string($_GET['location']))
    {
      $location = explode('|', $_GET['location']);
    }
    
    return $location;
  }




  /**
   * Returns array of tags from url, or false if not set
   * Seperated by '|'
   * 
   * @return <boolean, array>
   */
  public function tag()
  {
    $tag = false;
    
    if (! empty($_GET['tag']) && is_string($_GET['tag']))
    {
      $tag = explode('|', $_GET['tag']);
    }
    
    return $tag;
  }




  /**
   * Returns array of dates taken from url, or false if not set
   * Seperated by '|'
   * 
   * @return <boolean, array>
   */
  public function taken()
  {
    $taken = false;
    
    if (! empty($_GET['taken']) && is_string($_GET['taken']))
    {
      $taken = explode('|', $_GET['taken']);
    }
    
    return $taken;
  }




  /**
   * Returns sort
   * 
   * @return string
   */
  public function sort()
  {
    return ! empty($_GET['sort']) ? strip_tags($_GET['sort']) : false;
  }




  /**
   * String of all ids already used
   * 
   * @return array
   */
  public function randomed()
  {
    $ids = false;
    
    if (isset($_POST['randomed']))
    {
      $_temp = array();
      foreach ((explode('|', $_POST['randomed'])) as $_id)
      {
        if (is_numeric($_id))
        {
          array_push($_temp, $this->sc->security->decrypt($_id));
        }
      }
      
      $ids = empty($_temp) ? false : $_temp;
    }
    
    return $ids;
  }




  /**
   * Returns filter string
   * Choosing array produces array of filter properties
   * Choosing sting produces url of filter properties
   * 
   * @param string $array          
   * @return <string, array>
   */
  public function filterString($array = false)
  {
    $url = '';
    $values = array();
    
    if ($this->filterSet())
    {
      if ($this->keyword() !== false)
      {
        $values['q'] = $this->keyword();
      }
      
      if ($this->activity() !== false)
      {
        $values['activity'] = implode('|', $this->activity());
      }
      
      if ($this->altitude() !== false)
      {
        $values['altitude'] = implode('|', $this->altitude());
      }
      
      if ($this->taken() !== false)
      {
        $values['taken'] = implode('|', $this->taken());
      }
      
      if ($this->location() !== false)
      {
        $values['location'] = implode('|', $this->location());
      }
      
      if ($this->tag() !== false)
      {
        $values['tag'] = implode('|', $this->tag());
      }
      
      if (! empty($values))
      {
        $url .= '?' . http_build_query($values, '', '&');
      }
    }
    
    return $array ? $values : $url;
  }




  /**
   * Opens session
   */
  public function sessionOpen()
  {
    // session_start();
    // session_regenerate_id(true);
  }




  /**
   * Closes session
   */
  public function sessionClose()
  {
    // session_write_close();
  }




  /**
   * Gets/set previous page
   * 
   * @param
   *          string
   */
  public function hasPreviousPage()
  {
    if (! isset($_SERVER['HTTP_REFERER']))
    {
      return false;
    }
    
    $_prevpage = $_SERVER['HTTP_REFERER'];
    if (! preg_match('/snowcapture/i', $_prevpage) && ! preg_match('/localhost/i', $_prevpage))
    {
      return false;
    }
    
    if (preg_match('/search/i', $_prevpage))
    {
      return 'Search';
    }
    
    if (preg_match('/\/live/i', $_prevpage) && preg_match('/\/map/i', $_prevpage))
    {
      return 'Live Feed';
    }
    
    return 'Back';
  }




  /**
   * Sets form token
   * 
   * @return string
   */
  public function generateFormToken($type)
  {
    // Gerenate token
    $token = md5(uniqid(rand(), true));
    
    // Open session
    $this->sessionOpen();
    
    // Assign to session, for later use
    $_SESSION['FORM_TOKEN_' . $type] = $token;
    
    // Close session
    $this->sessionClose();
    
    // Return value
    return $token;
  }




  /**
   * Returns form token, false if empty or not set
   * 
   * @return <string, boolean>
   */
  public function getFormToken($type)
  {
    return isset($_SESSION['FORM_TOKEN_' . $type]) ? $_SESSION['FORM_TOKEN_' . $type] : false;
  }




  /**
   * Returns form token, false if empty or not set
   * 
   * @return <string, boolean>
   */
  public function formToken()
  {
    return $this->formToken;
  }




  /**
   * Formats a specified string date (string -> date)
   * 
   * @param string $date          
   * @return string
   */
  public function formatDate($date)
  {
    if ($this->sc->validate->validateDate($date))
    {
      $date = date("F jS, Y", strtotime($date));
    }
    
    return $date;
  }




  /**
   * Unformats specified date (date -> string)
   * 
   * @param string $date          
   * @return string
   */
  public function unformatDate($date)
  {
    if ($this->sc->validate->validateDate($date))
    {
      $date = date("Y-m-d", strtotime($date));
    }
    
    return $date;
  }




  /**
   * Returns whether timeago plugin should be used
   * 
   * @return boolean
   */
  public function timeAgo($date)
  {
    $now = date('Y-m-d H:i:s');
    $diff = floor((abs(strtotime($now)) - strtotime($date)) / 3600);
    $output = $diff < 24;
    
    return $output;
  }




  /**
   * Convert date to time e.g.
   * 4h
   * 
   * @param string $date          
   * @return string
   */
  public function dateToTime($date)
  {
    if (empty($date))
    {
      return "No date provided";
    }
    
    $periods = array(
        "s",
        "m",
        "h",
        "day",
        "week",
        "month",
        "year",
        "decade"
    );
    
    $lengths = array(
        "60",
        "60",
        "24",
        "7",
        "4.35",
        "12",
        "10"
    );
    
    $now = time();
    $unix_date = strtotime($date);
    
    // check validity of date
    if (empty($unix_date))
    {
      return "Bad date";
    }
    
    // is it future date or past date
    if ($now > $unix_date)
    {
      $difference = $now - $unix_date;
    } else
    {
      $difference = $unix_date - $now;
    }
    
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j ++)
    {
      $difference /= $lengths[$j];
    }
    
    $difference = strtoupper(round($difference));
    $calendar = '';
    $timer = '';
    
    $this_year = date('Y');
    $is_this_year = date('Y', $unix_date) == $this_year;
    
    $return_date = 0;
    if ($periods[$j] == 's')
    {
      $return_date = $timer . ' Just Now';
    } 

    elseif ($periods[$j] == 'm' || $periods[$j] == 'h')
    {
      $return_date = $timer . $difference . $periods[$j];
    }     

    // elseif (($periods[$j] == 'day' || $periods[$j] == 'week' || $periods[$j]
    // == 'month') && $is_this_year)
    // {
    // $return_date = $calendar . ' ' . strtoupper(date('d M', $unix_date));
    // }
    
    else
    {
      $return_date = $calendar . ' ' . strtoupper(date('d.m.Y', $unix_date));
    }
    
    // return $calendar . ' ' . date('d.m.Y', $unix_date);
    return $calendar . '' . $return_date;
  }




  /**
   * Converts string to Markdown
   * 
   * @param string $html          
   * @return <string, boolean>
   */
  public function convertToMarkdown($html = '')
  {
    
    // Set purifier objects
    if (! class_exists('Markdown'))
    {
      return false;
    }
    
    $markdown = new Markdown();
    $html = $markdown->transform($html);
    
    $html = str_replace('<code>', '<code class="prettyprint">', $html);
    $html = preg_replace('/\-{3,}/', '<hr />', $html);
    $html = preg_replace('/\_{3,}/', '<hr />', $html);
    $html = preg_replace('/\*{3,}/', '<hr />', $html);
    
    return $html;
  }




  /**
   * Converts DMS ( Degrees / minutes / seconds ) to decimal format longitude /
   * latitude
   * 
   * @param float $degrees          
   * @param float $minutes          
   * @param float $seconds          
   * @param string $ref          
   * @return float
   */
  public function DMStoDEC($degrees, $minutes, $seconds, $ref)
  {
    switch (strtolower($ref))
    {
      case 'n':
        $ref = '';
        break;
      
      case 's':
        $ref = '-';
        break;
      
      case 'e':
        $ref = '';
        break;
      
      case 'w':
        $ref = '-';
        break;
    }
    return $ref . ($degrees + ((($minutes * 60) + ($seconds)) / 3600));
  }
}

?>