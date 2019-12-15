<?php

/**
 * Object that represents a session
 * 
 * @author Robert Holden
 */
class Session
{
  /**
   *
   * @var Common
   */
  private $sc;
  
  /**
   *
   * @var string
   */
  public $browser = '';
  
  /**
   *
   * @var integer
   */
  public $displayID = 0;
  
  /**
   *
   * @var string
   */
  public $expirationDate = '';
  
  /**
   *
   * @var integer
   */
  public $id = 0;
  
  /**
   *
   * @var string
   */
  public $ipAddress = '';
  
  /**
   *
   * @var string
   */
  public $lastActive = '';
  
  /**
   *
   * @var integer
   */
  public $status = 0;
  
  /**
   *
   * @var string
   */
  public $uniqueID = '';
  
  /**
   *
   * @var User
   */
  public $user = null;
  
  /**
   *
   * @var string
   */
  public $value = '';




  /**
   * Construct
   * 
   * @param number $id          
   */
  function __construct($id = NULL)
  {
    global $sc;
    $this->sc = $sc;
    
    if (! empty($id))
    {
      $this->load($id);
    }
  }




  /**
   * Destruct
   */
  function __destruct()
  {}




  /**
   * Creates or updates a session
   * 
   * @return <boolean, string>
   */
  function append()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare insert/update
    $sql->insertUpdate('sc_user_sessions', ! ($this->exists() || ! $this->isUnique()));
    
    // Prepare vars
    $sql->values('value', $this->value);
    $sql->values('ip_address', $this->ipAddress);
    $sql->values('browser', $this->browser);
    $sql->values('expiration_date', $this->expirationDate);
    $sql->values('last_active', $this->lastActive);
    $sql->values('status', $this->status);
    
    // Insert only vars
    if (! $this->exists())
    {
      $sql->values('unique_id', $this->uniqueID);
      $sql->values('user_id', $this->user->id);
    }
    
    // Prepare where (ignored for insert)
    if ($this->exists())
    {
      $sql->whereParam('id', '=', $this->id);
    }
    
    else
    {
      $sql->whereParam('unique_id', '=', $this->uniqueID);
    }
    
    // Execute query
    if (! $sql->execute())
    {
      return 'Could not save';
    }
    
    // Reload
    $this->load($sql->lastID());
    
    // Success :)
    return true;
  }




  /**
   * Create a session for specified user
   * 
   * @param User $user          
   * @param boolean $remembered          
   * @return boolean
   */
  function createSession($user, $remembered, $guid = "")
  {
    $_auth = $this->generateToken();
    $_user = $this->login_encrypt($user->username);
    $_uniqueid = $this->login_encrypt($guid);
    
    $_sess = hash('sha512', $user->username . $_SERVER['HTTP_USER_AGENT'] . $guid . AUTH);
    
    // See if user checked 'remember me'
    $time = $remembered ? time() + (2592000 * 60) : 0;
    
    if (! (setcookie('snow_id', $_user, $time, '/', "", false, true) && setcookie('snow_sess', $_sess, $time, '/', "", false, true) && setcookie('snow_token', $_uniqueid, $time, '/', "", false, true) && setcookie('auth_token', $_auth, $time, '/', "", false, true)))
    {
      return false;
    }
  
    // Add session to db
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $now = date('Y-m-d H:i:s');
    
    $session = new Session();
    $session->expirationDate = $remembered ? date('Y-m-d H:i:s', strtotime("+5 years")) : date('Y-m-d H:i:s', strtotime("+1 days"));
    $session->value = $_auth;
    $session->status = 2;
    $session->uniqueID = $_sess;
    $session->lastActive = $now;
    $session->ipAddress = $ip_address;
    $session->browser = $this->sc->common->browser();
    $session->user = $user;
    
    return $session->append() ? true : false;
  }




  /**
   * Create a session for specified user
   * 
   * @param User $user          
   * @return string
   */
  function createToken($user, $guid = "")
  {
    $_auth = $this->generateToken();
    $_sess = hash('sha512', $user->username . $_SERVER['HTTP_USER_AGENT'] . $guid . AUTH);
    
    // Add session to db
    $ip_address = '___API___';
    $now = date('Y-m-d H:i:s');
    
    $session = new Session();
    $session->expirationDate = date('Y-m-d H:i:s', strtotime("+5 years"));
    $session->value = $_auth;
    $session->status = 2;
    $session->uniqueID = $_sess;
    $session->lastActive = $now;
    $session->ipAddress = $ip_address;
    $session->browser = $this->sc->common->browser();
    $session->user = $user;
    
    return $session->append() ? $_auth : '';
  }




  /**
   * Delete session by id
   * 
   * @param integer $id          
   * @return boolean
   */
  function delete($id = 0)
  {
    // If id 0 and session exists, set id to this
    if ($this->exists() && $id == 0)
    {
      $id = $this->id;
    }
    
    // Declare helper
    $sql = new SQLHelper();
  
    // Prepare query
    $sql->update('sc_user_sessions');
    $sql->values('last_active', date('Y-m-d H:i:s'));
    $sql->values('status', 0);
    $sql->whereParam('id', '=', $id);
    
    // Execute query
    return $sql->execute();
  }




  /**
   * Returns whether the object is set
   * 
   * @return boolean
   */
  public function exists()
  {
    return ($this->status > 0 && $this->id > 0) ? true : false;
  }




  /**
   * Generates random token
   * 
   * @return string
   */
  function generateToken()
  {
    $now = date('Y-m-d H:i:s');
    return hash('sha256', (new Functions)->generateRandomString(50) . $now);
  }




  /**
   * Gets all session by user id
   * 
   * @param integer $id          
   * @return <array, boolean>
   */
  function getSessions()
  {
    // Ensure session exists    
    if (! $this->exists())
    {
      return false;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_user_sessions');
    $sql->whereParam('user_id', '=', $this->user->id);
    $sql->whereParam('status', '=', 2, 'AND');
    $sql->whereParam('expiration_date', '>', date('Y-m-d H:i:s'), 'AND');
    $sql->order('last_active DESC');
    
    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Store
    $_sessions = array();
    
    // Load
    while ($session = $sql->fetchArray())
    {
      array_push($_sessions, $this->assign($session));
    }
    
    // Return
    return $_sessions;
  }




  /**
   * Gets all session tokens by user id
   * 
   * @param integer $id          
   * @return <array, boolean>
   */
  function getSessionTokens($id)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_user_sessions');
    $sql->whereParam('user_id', '=', $id);
    $sql->whereParam('expiration_date', '>', date('Y-m-d H:i:s'), 'AND');
    $sql->whereParam('status', '=', 2, 'AND');
    
    // Execute query
    $sql->execute();
    
    // Make sure are rows
    if (! $sql->hasRows())
    {
      return array();
    }
    
    // Store
    $_tokens = array();
    
    // Load
    while ($session = $sql->fetchArray())
    {
      array_push($_tokens, ucwords($session['value']));
    }
    
    // Return
    return $_tokens;
  }



  /**
   * Set session last active date to now
   * 
   * @return boolean
   */
  public function isActive()
  {
    if (! $this->exists())
    {
      return false;
    }
    
    $this->lastActive = date('Y-m-d H:i:s');
    return ($this->append() === true);
  }
  

  /**
   * Returns whether user is an admin
   * 
   * @return boolean
   */
  public function isAdmin()
  {
    if (! $this->exists())
    {
      return false;
    }
    
    return $this->user->isAdmin();;
  }




  /**
   * Returns if a user is logged in
   * 
   * @return boolean
   */
  public function isUser()
  {
    if ($this->exists())
    {
      return $this->user->exists();
    }
    
    return false;
  }




  /**
   * Returns if user is not logged in
   * 
   * @return boolean
   */
  public function isGuest()
  {
    if ($this->exists())
    {
      return ! $this->user->exists();
    }
    
    return true;
  }




  /**
   * Returns whether this user is timed out
   * 
   * @return boolean
   */
  public function isTimedOut()
  {
    if (! $this->exists() || ! $this->user->canTimeout)
    {
      return false;
    }

    if ($this->status == 1) 
    {
      return true;
    }
    
    $cutoff = strtotime(date('Y-m-d H:i:s', strtotime("-" . TIMEOUT_MINUTES . " minutes")));
    if (strtotime($this->lastActive) < $cutoff)
    {
      $this->timeOut();
      return true;
    }
    
    return false;
  }




  /**
   * Returns whether this browser is unique
   * 
   * @return boolean
   */
  function isUnique()
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_user_sessions');
    $sql->whereParam('unique_id', '=', $this->uniqueID);
    $sql->whereParam('status', '=', 2, 'AND');
    
    // Execute query
    $sql->execute();
    
    // Make sure are rows
    return ! $sql->hasRows();
  }




  /**
   * Returns whether a session is valid
   * 
   * @return <boolean, User>
   */
  function logIn()
  {
    // Global Check For Session/Cookie
    if (! (isset($_COOKIE['snow_id'], $_COOKIE['snow_sess'], $_COOKIE['auth_token'], $_SERVER['HTTP_USER_AGENT']) && LOGIN_ENABLED))
    {
      return false;
    }
  
    // Get the user-agent string of the user.
    $_username = $this->login_decrypt($_COOKIE['snow_id']);
    $_uniqueid = $this->login_decrypt($_COOKIE['snow_token']);
    $_sess = hash('sha512', $_username . $_SERVER['HTTP_USER_AGENT'] . $_uniqueid . AUTH);
      
    // Make sure tokens match
    if ($_sess != $_COOKIE['snow_sess'])
    {
      return false;
    }

    // Get user from db
    $_user = new User($_username);
        
    // Ensure user exists
    if (! $_user->exists())
    {
      return false;
    }
        
    // Reload for admin
    if ($_user->isAdmin())
    {
      $_user = new Admin($_username);
    }
    
    // Check sessions
    $_auth = $_COOKIE['auth_token'];
          
    // Get session from auth
    $this->load($_auth);
    
    // Make sure it exists
    if (! $this->exists())
    {
      return false;
    }
    
    // Make sure session user and logged user are the same
    if ($this->user->id != $_user->id) 
    {
      return false;
    }
            
    // Update session
    return ($this->append() != false);
  }




  /**
   * Decrypt ecrypted string designed for login sessions
   * 
   * @param string $stuff          
   * @return string
   */
  function login_decrypt($stuff)
  {
    $key = hash('md5', $_SERVER['HTTP_USER_AGENT'] . AUTH);
    return openssl_decrypt($stuff, ENC_METHOD, $key, 0, DEFAULT_IV);
  }




  /**
   * Ecrypt string designed for login sessions
   * 
   * @param string $stuff          
   * @return string
   */
  function login_encrypt($stuff)
  {
    $key = hash('md5', $_SERVER['HTTP_USER_AGENT'] . AUTH);
    return openssl_encrypt($stuff, ENC_METHOD, $key, 0, DEFAULT_IV);
  }




  /**
   * End session by user id
   * 
   * @param integer $id          
   */
  function logout()
  {
    if (! $this->exists())
    {
      return false;
    }
    
    // Set status to 0
      $this->status = 0;
      $this->append();
     
    // Clear cookies
    if (isset($_COOKIE['snow_id']))
    {
      setcookie('snow_id', '', time() - 3600);
      unset($_COOKIE['snow_id']);
    }
    
    if (isset($_COOKIE['snow_sess']))
    {
      setcookie('snow_sess', '', time() - 3600);
      unset($_COOKIE['snow_sess']);
    }
    
    if (isset($_COOKIE['snow_token']))
    {
      setcookie('snow_token', '', time() - 3600);
      unset($_COOKIE['snow_token']);
    }
    
    if (isset($_COOKIE['auth_token']))
    {
      setcookie('auth_token', '', time() - 3600);
      unset($_COOKIE['auth_token']);
    }
  }




  /**
   * Returns current users privacy level
   * 
   * @param integer $level          
   * @param string $redirect          
   * @return boolean
   */
  public function privacyCheck($level, $redirect = true)
  {
    $output = false;
    
    if ($this->isUser())
    {
      $output = ($this->user->status >= $level) ? true : false;
    }
    
    if (! $output && $redirect)
    {
      $this->goToLogin();
    }
    
    return $output;
  }




  /**
   * Redirect to login
   */
  public function goToLogin()
  {
    if ($this->exists())
    {
      $this->sc->common->goNoWhere();
    } 

    else
    {
      $url = "$_SERVER[REQUEST_URI]";
      $goto = '/sign-in?url=' . $url;
      $this->sc->common->goToURL($goto);
    }
  }




  /**
   * Encrypt string with user's salt
   * 
   * @param User $user          
   * @param string $data          
   * @return string
   */
  function saltData($user, $data)
  {
    $data = hash('sha512', $data . $user->salt());
    return $data;
  }




  /**
   * Timeouts user
   * 
   * @return boolean
   */
  function timeOut()
  {
    if (! $this->exists())
    {
      return false;
    }
    $this->status = 1;
    return ($this->append() === true);
  }




  /**
   * Un-timeouts user
   * 
   * @return boolean
   */
  function unTimeout()
  {
    if (! $this->exists())
    {
      return false;
    }
    
    $this->status = 2;
    $this->lastActive = date('Y-m-d H:i:s');

    return ($this->append() === true);
  }




  /**
   * Maps a datarow to a Session object
   * 
   * @param array $this_session          
   * @param Session $session          
   * @return <boolean, Session>
   */
  protected function assign($this_session, $session = null)
  {
    $output = false;
    
    if (! empty($this_session))
    {
      $session = ($session == null) ? new Session() : $session;
      
      $session->browser = empty($this_session['browser']) ? 'unknown' : $this_session['browser'];
      $session->displayID = $this->sc->security->encrypt($this_session['id']);
      $session->expirationDate = $this_session['expiration_date'];
      $session->id = $this_session['id'];
      $session->ipAddress = $this_session['ip_address'];
      $session->lastActive = $this_session['last_active'];
      $session->status = $this_session['status'];
      $session->uniqueID = $this_session['unique_id'];
      $session->user = new User($this_session['user_id']);
      
      // Update to admin?
      if ($session->user->isAdmin())
      {
        $session->user = new Admin($session->user->id);
      }
      
      $session->value = $this_session['value'];
      
      $output = $session;
    }
    
    return $output;
  }




  /**
   * Initialises Session object
   * 
   * @param integer $id          
   * @return boolean
   */
  protected function load($id)
  {
    if (empty($id))
    {
      return false;
    }
    
    if (! is_numeric($id) && ! is_string($id))
    {
      return false;
    }
    
    $get_session = is_numeric($id) ? "SELECT  * FROM sc_user_sessions WHERE status > 0 AND id = $id LIMIT 1" : "SELECT  * FROM sc_user_sessions WHERE status > 0 AND value = '$id' LIMIT 1";
    
    if ($session = $this->sc->db->query($get_session))
    {
      if ($this->sc->db->hasRows($session))
      {
        // Populate vars
        $this_session = $this->sc->db->fetchArray($session);
        $this->assign($this_session, $this);
      }
    }
  }
}