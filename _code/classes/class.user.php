<?php

/**
 * Object that represents a user
 *
 * @author Robert Holden
 */
class User
{

  /**
   *
   * @var string
   */
  private $iv = DEFAULT_IV;

  /**
   *
   * @var string
   */
  private $passcode = '';

  /**
   *
   * @var string
   */
  private $password = '';

  /**
   *
   * @var string
   */
  private $salt = '';

  /**
   *
   * @var SnowCapture
   */
  private $sc;

  /**
   *
   * @var integer
   */
  public $active = 0;

  /**
   *
   * @var boolean
   */
  public $canTimeout = false;

  /**
   *
   * @var integer
   */
  public $attempts = 0;

  /**
   *
   * @var integer
   */
  public $displayID = 0;

  /**
   *
   * @var string
   */
  public $displayName = '';

  /**
   *
   * @var string
   */
  public $email = '';

  /**
   *
   * @var integer
   */
  public $id = 0;

  /**
   *
   * @var boolean
   */
  public $imageTerms = false;

  /**
   *
   * @var string
   */
  public $lastAttemptedDate = '';

  /**
   *
   * @var integer
   */
  public $likes = 0;

  /**
   *
   * @var UserLimits
   */
  public $limits;

  /**
   *
   * @var string
   */
  public $name = '';

  /**
   *
   * @var UserNotifications
   */
  public $notifications;

  /**
   *
   * @var UserOptions
   */
  public $options;

  /**
   *
   * @var string
   */
  public $picture = PROFILE_PLACEHOLDER;

  /**
   *
   * @var string
   */
  public $pictureCover = PROFILE_COVER_PLACEHOLDER;

  /**
   *
   * @var integer
   */
  public $privates = 0;

  /**
   *
   * @var integer
   */
  public $drafts = 0;

  /**
   *
   * @var integer
   */
  public $processing = 0;

  /**
   *
   * @var integer
   */
  public $status = 0;

  /**
   *
   * @var string
   */
  public $username = '';




  /**
   * Construct
   *
   * @param string $username
   */
  public function __construct($username = NULL)
  {
    global $sc;
    $this->sc = $sc;

    $this->limits = new UserLimits();
    $this->notifications = new UserNotifications();
    $this->options = new UserOptions();

    $this->lastAttemptedDate = date('Y-m-d H:i:s');
    if (! empty($username))
    {
      $this->load($username);
    }
  }




  /**
   * Destruct
   */
  public function __destruct()
  {}




  /**
   * Request a resort
   *
   * @param
   *          $resort
   * @return boolean
   *
   */
  public function addResortRequest($resort)
  {
    // Ensure user exists and resort is a valid length
    if (! $this->exists() || strlen($resort) == 0 || strlen($resort) > 50)
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare insert
    $sql->insert('sc_resort_requests');

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('resort', $resort);

    // Execute query
    return $sql->execute();
  }




  /**
   * Creates or updates a user
   *
   * @return <boolean, string>
   */
  public function append()
  {
    // Validate vars
    $output = $this->validate();

    if ($output !== true)
    {
      return $output;
    }

    // Reset output
    $output = false;

    // Prepare vars
    $_email = $this->sc->security->encrypt(strtolower($this->email), DEFAULT_IV);
    $_canTimeout = $this->canTimeout ? 1 : 0;
    $_imageTerms = $this->imageTerms ? 1 : 0;

    // Declare helper
    $sql = new SQLHelper();

    // Prepare insert/update
    $sql->insertUpdate('sc_users', ! $this->exists());

    // Prepare default values
    $sql->values('display_username', $this->displayName);
    $sql->values('name', $this->name);
    $sql->values('email', $_email);
    $sql->values('can_timeout', $_canTimeout);
    $sql->values('image_terms', $_imageTerms);
    $sql->values('last_attempted_date', $this->lastAttemptedDate);
    $sql->values('attempts', $this->attempts);
    $sql->values('status', $this->status);

    // Specific values for insert
    if (! $this->exists())
    {
      $sql->values('username', strtolower($this->username));
      $sql->values('password', '');
      $sql->values('passcode', '');
      $sql->values('picture', 0);
      $sql->values('picture_cover', 0);
    }

    // Prepare where for update (ignored for insert)
    $sql->whereParam('id', '=', $this->id);
    $sql->whereParam('status', '>', IMAGE_DELETED, 'AND');

    // Execute query
    $output = $sql->execute() ? true : 'Could not ' . ($this->exists() ? 'update' : 'register');

    if ($output === true)
    {
      $this->load($this->username);
    }

    // Return response
    return $output;
  }




  /**
   * Creates or updates a user's limits
   *
   * @return boolean
   */
  public function appendLimits($new = false)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare update/insert
    $sql->insertUpdate('sc_user_limits', $new);

    // Reset values on new
    if ($new)
    {
      $this->limits = new UserLimits();
    }

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('drafts', $this->limits->drafts);
    $sql->values('uploads', $this->limits->uploads);

    // Prepare where
    $sql->whereParam('user_id', '=', $this->id);

    // Execute query
    return $sql->execute();
  }




  /**
   * Creates or updates a user's options
   *
   * @return boolean
   */
  public function appendOptions($new = false)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    $enable_emails = $this->options->enableEmails ? 1 : 0;
    $send_likes = $this->options->sendLikes ? 1 : 0;
    $send_processing = $this->options->sendProcessing ? 1 : 0;
    $upload_geo = $this->options->uploadGeo ? 1 : 0;

    // Declare helper
    $sql = new SQLHelper();

    // Prepare update/insert
    $sql->insertUpdate('sc_user_options', $new);

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('enable_emails', $enable_emails);
    $sql->values('send_likes', $send_likes);
    $sql->values('send_processing', $send_processing);
    $sql->values('upload_geo', $upload_geo);

    // Prepare where
    $sql->whereParam('user_id', '=', $this->id);

    // Execute query
    return $sql->execute();
  }




  /**
   * Creates or updates a user's passcode
   *
   * @param string $passcode
   * @return boolean
   */
  public function appendPasscode($passcode)
  {
    // Ensure user exists and password is correct length
    if (! $this->exists() || strlen($passcode) == 0)
    {
      return false;
    }

    $session = new Session();
    $enc_pass = $session->saltData($this, $passcode);

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->update('sc_users');
    $sql->values('passcode', $enc_pass);
    $sql->whereParam('id', '=', $this->id);

    // Execute query
    return $sql->execute();
  }




  /**
   * Creates or updates a user's password
   *
   * @param string $password
   * @param string $create_salt
   * @return boolean
   */
  public function appendPassword($password, $create_salt = false)
  {
    // Ensure user exists and password is correct length
    if (! $this->exists() || strlen($password) == 0)
    {
      return false;
    }

    // Prepare vars
    $salt = md5(uniqid('auth', true));
    $enc_pass = hash('sha512', $password . $salt);

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query to add password to user
    $sql->update('sc_users');
    $sql->values('password', $enc_pass);
    $sql->whereParam('id', '=', $this->id);

    // Execute query
    // If success, insert/update salt
    if (! $sql->execute())
    {
      return false;
    }

    // Prepare query to add salt
    $sql->insertUpdate('sc_user_salts', $create_salt);
    $sql->values('salt', $salt);
    $sql->values('user_id', $this->id);
    $sql->whereParam('user_id', '=', $this->id); // Just for update

    // Add audit log if they're changing
    if (! $create_salt)
    {
      $log = new Audit();
      $log->userId = $this->id;
      $log->description = 'PASSWORD CHANGE';
      $log->save();
    }

    // Execute query
    return $sql->execute();
  }




  /**
   * Updates user's profile picture
   *
   * @param Image $image
   * @return boolean
   */
  public function appendPicture($image, $cover)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Delcare vars
    $c = $cover ? '_cover' : '';

    // Prepare query
    $sql->update('sc_users');
    $sql->values('picture' . $c, $image->id);
    $sql->whereParam('id', '=', $this->id);

    // Execute query
    return $sql->execute();
  }




  /**
   * Blocks a user for user
   *
   * @param User $blocked_user
   * @return boolean
   */
  public function blockUser($blocked_user, $unblock = false)
  {
    // Ensure user exists
    if (! $this->exists() || ! $blocked_user->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    if ($unblock)
    {
      // Prepar query
      $sql->delete('sc_blocked_users');
      $sql->whereParam('user_id', $this->id);
      $sql->whereParam('blocked_user_id', $blocked_user->id);
    }

    else
    {
      // Prepare query
      $sql->insert('sc_blocked_users');
      $sql->values('user_id', $this->id);
      $sql->values('blocked_user_id', $blocked_user->id);
    }

    // Execute query
    return $sql->execute();
  }




  /**
   * Returns whether this user can access uploader
   *
   * @return boolean
   */
  public function canAccessUpload()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    $count = (new Image())->countImagesForUser($this, IMAGE_DRAFT);
    return $this->canUpload() ? true : ($count > 0);
  }




  /**
   * Returns whether this user can upload
   *
   * @return boolean
   */
  public function canUpload()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    return $this->countUploads() < $this->limits->uploads;
  }




  /**
   * Clears out expired confirmations from database
   *
   * @return boolean
   */
  public function canConfirm()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $check_date = date('Y-m-d H:i:s', strtotime(PASSWORD_RESET_MINUTES));

    // Prepare query
    $sql->select('*');
    $sql->from('sc_user_confirmations');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('date', '>', $check_date, 'AND');

    // Execute query
    $sql->execute();

    // Check if there are rows
    return ! $sql->hasRows();
  }




  /**
   * Clears out expired forgotten passwords from database
   *
   * @return boolean
   */
  public function canForgetPassword()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $check_date = date('Y-m-d H:i:s', strtotime(PASSWORD_RESET_MINUTES));

    // Clear out old confirms
    // Prepare query
    $sql->delete('sc_password_resets');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('date', '<', $check_date, 'AND');

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Count resets
    // Prepare query
    $sql->select('*');
    $sql->from('sc_password_resets');
    $sql->whereParam('user_id', '=', $this->id);

    // Execute query
    $sql->execute();

    // Check for rows
    return ! $sql->hasRows();
  }




  /**
   * Determines if the system can send an email to this user
   *
   * @return boolean
   */
  public function canSendAnEmail()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $check_date = date('Y-m-d H:i:s', strtotime('-1 minutes'));

    // Prepare query
    $sql->select('*');
    $sql->from('sc_email_log');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('date', '>', $check_date, 'AND');

    // Execute query
    $sql->execute();

    // Check for rows
    return ! $sql->hasRows();
  }




  /**
   * Clears out ALL confirmations from database
   *
   * @return boolean
   */
  public function clearConfirmations()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->delete('sc_user_confirmations');
    $sql->whereParam('user_id', '=', $this->id);

    // Execute query
    return $sql->execute();
  }




  /**
   * Clears out ALL forgotten passwords from database
   *
   * @return boolean
   */
  public function clearForgottenPasswords()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->delete('sc_password_resets');
    $sql->whereParam('user_id', '=', $this->id);

    // Execute query
    return $sql->execute();
  }




  /**
   * Sets up confirmation token, and sends email to user to confirm email
   *
   * @return boolean
   */
  public function confirmEmail()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Ensure user can confirm
    if (! $this->canConfirm())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $now = date('Y-m-d H:i:s');
    $token = hash('sha256', $this->username . $now);

    // Prepare insert
    $sql->insert('sc_user_confirmations');

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('token', $token);

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Send email link
    $emailer = new Email('Confirm Email');
    $emailer->user = $this;
    return $emailer->sendConfirmEmail($this->sc->common->siteURL() . '/confirmation/' . $token);
  }




  /**
   * Clears out expired forgotten passwords from database
   *
   * @return boolean
   */
  public function confirmUser($token)
  {
    $output = new User();

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query to cofirm token
    $sql->select('user_id');
    $sql->from('sc_user_confirmations');
    $sql->whereParam('token', '=', $token);

    // Execute query
    if (! $sql->execute())
    {
      return $output;
    }

    // Fetch user info
    $_assoc = $sql->fetchAssoc();
    $_user = new User($_assoc['user_id']);

    if ($_user->exists())
    {
      $_user->status = 2;
      $output = ($_user->append() === true) ? $_user : $output;
    }

    return $output;
  }




  /**
   * Counts the number uploads for this user
   *
   * @return number
   */
  public function countUploads()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    return ($this->active + $this->privates + $this->processing + $this->drafts);
  }




  /**
   * Creates new session for this user
   *
   * @param boolean $remembered
   */
  public function createSession($remembered, $guid)
  {
    $session = new Session();
    $session->createSession($this, $remembered, $guid);
  }




  /**
   * Creates new session for this user
   *
   * @param string $guid
   */
  public function createToken($guid)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return '';
    }

    $session = new Session();
    return $session->createToken($this, $guid);
  }




  /**
   * Used for child objects
   *
   * @return db
   *
   */
  public function db()
  {
    return $this->sc->db;
  }




  /**
   * Delete this user and all data associated with it
   *
   * @return boolean
   */
  public function delete()
  {
    // Ensure user exists
    if ($this->exists() && $this->status != LEVEL_DELETED)
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $id = $this->id;

    // Remove likes
    $sql->delete('sc_image_likes');
    $sql->where('image_id IN (SELECT id FROM sc_images WHERE user_id = ' . $id . ')');
    $sql->whereParam('user_id', '=', $id, 'OR');
    if (! $sql->execute())
    {
      return false;
    }

    // Remove salts
    $sql->delete('sc_user_salts');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove sessions
    $sql->delete('sc_user_sessions');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove preferences
    $sql->delete('sc_user_options');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove password resets
    $sql->delete('sc_password_resets');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove resort request
    $sql->delete('sc_resort_requests');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove banned users
    $sql->delete('sc_banned_users');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove reported users
    $sql->delete('sc_reported_users');
    $sql->where('reported_image_id IN (SELECT id FROM sc_images WHERE user_id = ' . $id . ')');
    $sql->whereParam('user_id', '=', $id, 'OR');
    $sql->whereParam('reported_user_id', '=', $id, 'OR');
    if (! $sql->execute())
    {
      return false;
    }

    // Remove notifications
    $sql->delete('sc_notifications');
    $sql->where('image_id IN (SELECT id FROM sc_images WHERE user_id = ' . $id . ')');
    $sql->whereParam('user_from_id', '=', $id, 'OR');
    $sql->whereParam('user_to_id', '=', $id, 'OR');
    if (! $sql->execute())
    {
      return false;
    }

    // Remove confirmations
    $sql->delete('sc_user_confirmations');
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove images
    $sql->update('sc_images');
    $sql->values('status', IMAGE_DELETED);
    $sql->whereParam('user_id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // Remove user
    $sql->update('sc_users');
    $sql->values('status', LEVEL_DELETED);
    $sql->whereParam('id', '=', $id);
    if (! $sql->execute())
    {
      return false;
    }

    // If we got here then success!
    return true;
  }




  /**
   * Returns whether the object is set
   *
   * @return boolean
   */
  public function exists()
  {
    return ($this->id > 0) ? true : false;
  }




  /**
   * Sets up forgotten password request for this user, an email is also sent
   *
   * @return boolean
   */
  public function forgottenPassword()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    // Generate token
    $now = date('Y-m-d H:i:s');
    $token = hash('sha256', $this->username . $now);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $check_date = date('Y-m-d H:i:s', strtotime(PASSWORD_RESET_MINUTES));

    // Prepare query
    // Check IP is not spamming
    // Gets how many requests in last 10 mins
    // If more than 10, add to naughty list
    $sql->select('*');
    $sql->from('sc_password_resets');
    $sql->whereParam('ip_address', '=', $ip_address);
    $sql->whereParam('date', '=', $check_date, 'AND');

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // If found, add user to naughty list!
    if ($sql->countRows() > PASSWORD_RESET_ATTEMPTS)
    {
      $this->sc->security->addToNaughtyList(3);
      return false;
    }

    // Clear old resets
    if (! $this->canForgetPassword())
    {
      return false;
    }

    // Prepare insert
    $sql->insert('sc_password_resets');

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('token', $token);
    $sql->values('ip_address', $ip_address);

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Send email link
    $emailer = new Email('Forgotten Password');
    $emailer->user = $this;
    return $emailer->sendForgotPassword($this->sc->common->siteURL() . '/forgot-password/' . $token);
  }




  /**
   * Gets user by their reset token
   *
   * @param string $token
   */
  public function getByResetToken($token)
  {
    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->prepareUser();
    $sql->where('user.id = (SELECT user_id FROM sc_password_resets WHERE token = "' . $sql->sanitise($token) . '")');
    $sql->limit(1);

    // Execute query
    $sql->execute();

    // Are there rows?
    if (! $sql->hasRows())
    {
      return false;
    }

    // Load user
    $this_user = $sql->fetchArray();
    $this->assign($this_user, $this);

    return true;
  }




  /**
   * Returns rating for user from image
   *
   * @param Image $image
   * @return integer
   */
  public function getImageRating($image)
  {
    // Ensure user & image exists
    if (! $this->exists() || ! $image->exists())
    {
      return 0;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('
       IFNULL(
        (SELECT ROUND((SUM(value) / COUNT(id)))
         FROM sc_image_ratings
         WHERE image_id = ' . $image->id . '
         AND user_id = ' . $this->id . '
         Limit 1)
      , 0) AS rating
    ');

    // Execute query
    $sql->execute();

    // Fetch user info
    if ($_assoc = $sql->fetchAssoc())
    {
      return $_assoc['rating'];
    }

    return 0;
  }




  /**
   * Gets next draft image for this user
   *
   * @return <boolean, array>
   */
  public function getDraftImage()
  {
    if (! $this->exists())
    {
      return false;
    }

    $image = new Image();
    $output = $image->getDraftForUser($this);

    return $image;
  }




  /**
   * Returns whether this user has blocked specified user
   *
   * @param int $id
   * @return boolean
   */
  public function hasBlockedUser($id)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('*');
    $sql->from('sc_blocked_users');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('blocked_user_id', '=', $id, 'AND');

    // Execute query
    $sql->execute();

    // Are there rows?
    return $sql->hasRows();
  }




  /**
   * Returns whether this user has reported specified image
   *
   * @param int $id
   * @return boolean
   */
  public function hasReportedImage($id)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('*');
    $sql->from('sc_reported_users');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('reported_image_id', '=', $id, 'AND');
    $sql->whereParam('status', '>', 0, 'AND');

    // Execute query
    $sql->execute();

    // Are there rows?
    return $sql->hasRows();
  }




  /**
   * Returns whether this user has reported specified user
   *
   * @param int $id
   * @return boolean
   */
  public function hasReportedUser($id)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('*');
    $sql->from('sc_reported_users');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('reported_user_id', '=', $id, 'AND');
    $sql->whereParam('status', '>', 0, 'AND');

    // Execute query
    $sql->execute();

    // Are there rows?
    return $sql->hasRows();
  }




  /**
   * Returns whether this user is an admin
   *
   * @return boolean
   */
  public function isAdmin()
  {
    return ($this->exists()) ? ($this->status == LEVEL_ADMIN) ? true : false : false;
  }




  /**
   * Returns whether this user has been disabled
   *
   * @return boolean
   */
  public function isDisabled()
  {
    return ($this->status == LEVEL_DISABLED);
  }




  /**
   * Returns if the user is locked out of their account
   *
   * @return boolean
   *
   */
  public function isLockedOut()
  {
    $now = date('Y-m-d H:i:s');
    $then = $this->lastAttemptedDate;
    $diff = floor((abs(strtotime($now)) - strtotime($then)) / 3600);
    return ($this->attempts >= MAX_LOGIN_ATTEMPTS && $diff < LOCKED_HOURS) ? true : false;
  }




  /**
   * Returns whether user has a session active within the last 5 minutes
   */
  public function isOnline()
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $_date = date('Y-m-d H:i:s', strtotime("-" . ONLINE_MINUTES . " minutes"));

    // Prepare query
    $sql->select('*');
    $sql->from('sc_user_sessions');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('last_active', '>', $_date, 'AND');
    $sql->whereParam('status', '=', 2, 'AND');

    // Execute query
    $sql->execute();

    // Are there rows?
    return $sql->hasRows();
  }




  /**
   * Gets the iv for this user
   *
   * @return string
   */
  public function iv()
  {
    $output = DEFAULT_IV;

    if ($this->exists())
    {
      $output = $this->iv;
    }

    return $output;
  }




  /**
   * Returns this user's passcode (encrypted)
   *
   * @return string
   */
  public function passcode()
  {
    return $this->passcode;
  }




  /**
   * Returns this user's password (encrypted)
   *
   * @return string
   */
  public function password()
  {
    return $this->password;
  }




  /**
   * Rates an image for user
   *
   * @param Image $image
   * @param integer $rating
   * @return <boolean, string>
   */
  public function rateImage($image, $rating)
  {
    // Ensure user exists
    if (! $this->exists() || ! $image->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare vars
    $_rating = ! is_numeric($rating) ? 0 : $rating;

    // Prepare query to remove previous ratings
    $sql->delete('sc_image_ratings');
    $sql->whereParam('user_id', '=', $this->id);
    $sql->whereParam('image_id', '=', $image->id, 'AND');

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Send/remove notification to user
    $_notif = new Notification();
    $_notif->image = $image;
    $_notif->userFrom = $this;
    $_notif->userTo = $image->user;
    $_notif->type = LOG_RATED;
    $_notif->find();

    if ($rating == 0)
    {
      $_notif->delete();
    }

    elseif ($_notif->exists())
    {
      $_notif->restore();
    }

    else
    {
      $_notif->append();
    }

    // If new rating is 0, don't add.
    if ($rating == 0)
    {
      return true;
    }

    // Prepare query to add new rating
    $sql->insert('sc_image_ratings');
    $sql->values('user_id', $this->id);
    $sql->values('image_id', $image->id);
    $sql->values('value', $rating);

    // Execute query
    return $sql->execute();
  }




  /**
   * Reports image for user
   *
   * @param Image $reported_image
   * @param string $comment
   * @param int $type
   * @return string|boolean
   */
  public function reportImage($reported_image, $comment, $type)
  {
    // Ensure user exists
    if (! $this->exists() || ! $reported_image->exists())
    {
      return 'Cannot find image';
    }

    // Ensure comment is no more than 500 chars
    if (strlen($comment) > 500)
    {
      return 'Max 500 characters';
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare insert
    $sql->insert('sc_reported_users');

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('reported_user_id', 0);
    $sql->values('reported_image_id', $reported_image->id);
    $sql->values('comment', htmlentities($comment));
    $sql->values('type', (is_numeric($type) ? $type : 0));
    $sql->values('status', 1);

    // Execute query
    return $sql->execute();
  }




  /**
   * Reports user for user
   *
   * @param User $reported_user
   * @param string $comment
   * @param int $type
   * @return string|boolean
   */
  public function reportUser($reported_user, $comment, $type)
  {
    // Ensure user exists
    if (! $this->exists() || ! $reported_user->exists())
    {
      return 'Cannot find user';
    }

    // Ensure comment is no more than 500 chars
    if (strlen($comment) > 500)
    {
      return 'Max 500 characters';
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare insert
    $sql->insert('sc_reported_users');

    // Prepare values
    $sql->values('user_id', $this->id);
    $sql->values('reported_user_id', $reported_user->id);
    $sql->values('reported_image_id', 0);
    $sql->values('comment', htmlentities($comment));
    $sql->values('type', (is_numeric($type) ? $type : 0));
    $sql->values('status', 1);

    // Execute query
    return $sql->execute();
  }




  /**
   * Resets users password
   *
   * @param , $password
   * @param ) $email
   * @return boolean
   *
   */
  public function resetPassword($password, $email = false)
  {
    // Ensure user exists
    if (! $this->exists())
    {
      return false;
    }

    // Change password
    if (! $this->appendPassword($password))
    {
      return false;
    }

    // Clear forgotten passwords
    $this->clearForgottenPasswords();

    // Send follow up email
    if ($email)
    {
      $emailer = new Email('Password Reset');
      $emailer->user = $this;
      $emailer->send();
    }

    return true;
  }




  /**
   * Gets the salt for this user
   *
   * @return string
   */
  public function salt()
  {
    return $this->exists() ? $this->salt : false;
  }




  /**
   * Likes/unlikes user to/from this image
   *
   * @param User $user
   * @return <boolean, string>
   */
  public function toggleLike($image)
  {
    // Ensure user & image exists
    if (! $this->exists() || ! $image->exists())
    {
      return false;
    }

    // Prepare helper
    $sql = new SQLHelper();

    // Is the image liked?
    $_isLiked = $image->isLike($this);

    if ($_isLiked)
    {
      // Prepare query
      $sql->delete('sc_image_likes');
      $sql->whereParam('user_id', '=', $this->id);
      $sql->whereParam('image_id', '=', $image->id, 'AND');
    }

    else
    {
      // Prepare query
      $sql->insert('sc_image_likes');
      $sql->values('user_id', $this->id);
      $sql->values('image_id', $image->id);
    }

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }

    // Send/remove notification to user
    $_notif = new Notification();
    $_notif->image = $image;
    $_notif->userFrom = $this;
    $_notif->userTo = $image->user;
    $_notif->type = LOG_LIKED;
    $_notif->find();

    if ($_isLiked)
    {
      $_notif->delete();
    }

    elseif ($_notif->exists())
    {
      $_notif->restore();
    }

    else
    {
      $_notif->append();
    }

    // Return liked or un-liked
    return $_isLiked ? 'Picture Un-Liked' : 'Picture Liked';
  }




  /**
   * Un-Reports image for user
   *
   * @param Image $reported_user
   * @return boolean
   */
  public function unReportImage($reported_image)
  {
    // Ensure user & image exists
    if (! $this->exists() || ! $reported_image->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->update('sc_reported_users');
    $sql->values('user_id', $this->id);
    $sql->values('reported_image_id', $reported_image->id);
    $sql->values('status', 0);

    // Execute query
    return $sql->execute();
  }




  /**
   * Un-Reports user for user
   *
   * @param User $reported_user
   * @return boolean
   */
  public function unReportUser($reported_user)
  {
    // Ensure user & user exists
    if (! $this->exists() || ! $reported_user->exists())
    {
      return false;
    }

    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->update('sc_reported_users');
    $sql->values('user_id', $this->id);
    $sql->values('reported_user_id', $reported_user->id);
    $sql->values('status', 0);

    // Execute query
    return $sql->execute();
  }




  /**
   * Maps a datarow to a User object
   *
   * @param array $read
   * @param User $write
   * @return <boolean, User>
   */
  protected function assign($read, $write = null)
  {
    // Ensure there's data
    if (empty($read))
    {
      return false;
    }

    // Init obj
    $_write = is_null($write) ? new User() : $write;

    // Write data to obj
    $_write->active = $read['active'];
    $_write->attempts = $read['attempts'];
    $_write->canTimeout = $read['can_timeout'] == 1;
    $_write->displayID = $this->sc->security->encrypt($read['id']);
    $_write->displayName = $read['display_username'];
    $_write->email = $this->sc->security->decrypt($read['email'], DEFAULT_IV);
    $_write->id = $read['id'];
    $_write->imageTerms = $read['image_terms'] == 1;
    $_write->lastAttemptedDate = $read['last_attempted_date'];
    $_write->likes = $read['likes'];
    $_write->name = $read['name'];
    $_write->passcode = $read['passcode'];
    $_write->password = $read['password'];
    $_write->salt = $read['salt'];
    $_write->iv = md5($read['username'] . DEFAULT_IV, true);

    $filepath = UPLOAD_DIR . THUMBNAIL_PATH_CUSTOM . $read['picture'] . '.jpg';
    $_write->picture = empty($read['picture']) ? $_write->picture : (! file_exists(WEB_ROOT . $filepath) ? $this->picture : $filepath);

    $filepath = UPLOAD_DIR . $read['picture_cover'] . '.jpeg';
    $_write->pictureCover = empty($read['picture_cover']) ? $_write->pictureCover : (! file_exists(WEB_ROOT . $filepath) ? $this->pictureCover : $filepath);

    $_write->drafts = $read['drafts'];
    $_write->privates = $read['privates'];
    $_write->processing = $read['processing'];
    $_write->status = $read['status'];
    $_write->username = $read['username'];

    $_write->notifications->count = $read['notif_count'];
    $_write->notifications->new = $read['notif_count_new'];
    $_write->notifications->hasNew = $read['notif_count_new'] > 0;

    $_write->limits->drafts = $read['draft_limit'];
    $_write->limits->uploads = $read['upload_limit'];

    $_write->options->enableEmails = $read['enable_emails'] == 1;
    $_write->options->sendLikes = $read['send_likes'] == 1;
    $_write->options->sendProcessing = $read['send_processing'] == 1;
    $_write->options->uploadGeo = $read['upload_geo'] == 1;

    return $_write;
  }




  /**
   * Loads User object from db
   *
   * @param string $username
   */
  protected function load($username)
  {
    // Declare helper
    $sql = new SQLHelper();

    // Prepare select/from
    $sql->prepareUser();

    // Prepare where
    // Look against an id
    if (is_numeric($username))
    {
      $sql->whereParam('user.id', '=', $username);
    }

    // Look against username & email
    else
    {
      // Email
      $_email = $this->sc->security->encrypt(strtolower($username), DEFAULT_IV);
      $sql->whereParam('user.email', '=', $_email);

      // Username
      $sql->whereParam('user.username', '=', strtolower($username), 'OR');
    }

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
   * Validates current user
   *
   * @return <boolean, string>
   */
  protected function validate()
  {
    $output = array();

    // Display Name
    if (strtolower($this->displayName) != strtolower($this->username))
    {
      $output = 'Display Name';
    }

    // Email
    $temp_email = $this->exists() ? $this->sc->security->decrypt($this->email, DEFAULT_IV) : $this->email;
    if (strlen($temp_email) == 0 || strlen($temp_email) > 255 || ! filter_var($temp_email, FILTER_VALIDATE_EMAIL))
    {
      $output = $temp_email;
    }

    // Image Terms
    if (! is_bool($this->imageTerms))
    {
      $output = 'Image T&C\'s';
    }

    // Name
    if (strlen($this->name) == 0 || strlen($this->name) > 255 || preg_match("/[^-a-z0-9_ ]/i", $this->name))
    {
      $output = 'Name';
    }

    // Status
    if (! is_numeric($this->status))
    {
      $output = 'Status';
    }

    // Username
    if (strlen($this->username) == 0 || strlen($this->username) > 50 || preg_match("/[^-a-z0-9_]/i", $this->username) || is_numeric($this->username))
    {
      $output = 'Username';
    }

    return empty($output) ? true : 'Invalid ' . $output;
  }
}
