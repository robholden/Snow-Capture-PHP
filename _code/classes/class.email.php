<?php

/**
 * Object to send emails
 *
 * @author Robert Holden
 */
class Email
{

  /**
   *
   * @var string
   */
  private $alwaysSend;

  /**
   *
   * @var string
   */
  private $bcc;

  /**
   *
   * @var string
   */
  private $body;

  /**
   *
   * @var string
   */
  private $bulk;

  /**
   *
   * @var string
   */
  private $host = SMTP_HOST;

  /**
   *
   * @var string
   */
  private $host_email = EMAIL_FROM;

  /**
   *
   * @var string
   */
  private $host_from = EMAIL_FROM_NAME;

  /**
   *
   * @var string
   */
  private $host_pass = SMTP_PASS;

  /**
   *
   * @var string
   */
  private $host_user = SMTP_USER;

  /**
   *
   * @var integer
   */
  private $host_port = SMTP_PORT;

  /**
   *
   * @var boolean
   */
  private $html;

  /**
   *
   * @var string
   */
  private $name;

  /**
   *
   * @var boolean
   */
  private $review;
  /**
   *
   * @var SnowCapture
   */
  private $sc;

  /**
   *
   * @var string
   */
  private $subject;

  /**
   *
   * @var string
   */
  private $unsubscribe;

  /**
   *
   * @var integer
   */
  public $id = 0;

  /**
   *
   * @var User
   */
  public $user = null;




  /**
   * $id represents email template id from database
   *
   * @param string $id
   */
  function __construct($id = NULL)
  {
    global $sc;
    $this->sc = $sc;

    $this->bulk = file_get_contents(WEB_ROOT . 'things/header_footer.txt', FILE_USE_INCLUDE_PATH);
    $this->unsubscribe = file_get_contents(WEB_ROOT . 'things/unsubscribe.txt', FILE_USE_INCLUDE_PATH);
    $this->review = false;

    if (! empty($id))
    {
      $this->load($id);
    }
  }




  /**
   * Returns whether the object is set
   *
   * @return boolean
   */
  public function exists()
  {
    return ($this->id > 0);
  }




  /**
   * Sends email
   *
   * @return boolean
   */
  function send()
  {
    // Ensure email exists
    if (! $this->exists())
    {
      return false;
    }

    // Don't send while local
    // if (DEVELOPMENT == 1)
    // {
    //   return true;
    // }

    // Ensure user has enabled emails or hasn't recently received one. Don't
    // want to spam them!
    if (! $this->alwaysSend && $this->user != null)
    {
      if (! $this->user->options->enableEmails || ! $this->user->canSendAnEmail())
      {
        return true;
      }
    }

    // Start mail
    $mail = new PHPMailer();

    // $mail->SMTPDebug = 1;
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = $this->host; // Specify main and backup SMTP servers
    $mail->SMTPAuth = !empty($this->host_user) || !empty($this->host_pass); // Enable SMTP authentication
    $mail->Username = $this->host_user; // SMTP username
    $mail->Password = $this->host_pass; // SMTP password
    $mail->Port = $this->host_port;

    $mail->From = $this->host_email;
    $mail->FromName = $this->host_from;

    if ($this->review)
    {
      $mail->AddAddress(REVIEW_EMAIL, SITE_NAME);
    }

    else
    {
      $mail->AddAddress($this->user->email, $this->user->name);
      $this->body = str_replace('##NAME##', $this->user->name, $this->body);
    }

    if (! empty($this->bcc))
    {
      $mail->AddAddress($this->bcc);
    }

    $this->body = str_replace('##SITEURL##', $this->sc->common->siteURL(), $this->body);
    $this->body .= $this->alwaysSend ? '' : $this->unsubscribe;

    $mail->Subject = $this->subject;
    $mail->Body = str_replace('##BODY##', $this->body, $this->bulk);
    $mail->AltBody = '';
    $mail->IsHTML($this->html);

    // Send email
    $_resp = $mail->Send();

    // If success log email
    if ($_resp)
    {
      // Declare helper
      $sql = new SQLHelper();

      // Prepare insert
      $sql->insert('sc_email_log');

      // Prepare values
      $sql->values('user_id', (is_null($this->user) ? 0000 : $this->user->id));
      $sql->values('email_id', $this->id);
      $sql->values('email', ($this->review ? REVIEW_EMAIL : $this->user->email));
      $sql->values('subject', $this->subject);
      $sql->values('body', $this->body);

      // Execute query
      $sql->execute();
    }

    return $_resp;
  }




  /**
   * Sends email to a user's which has been disabled
   *
   * @param string $message
   * @return boolean
   */
  function sendAccountDisabled($message)
  {
    $this->body = str_replace('##MESSAGE##', $message, $this->body);

    return $this->send();
  }




  /**
   * Sends email to a user's with confirm email link
   *
   * @param string $url
   * @return boolean
   */
  function sendConfirmEmail($url)
  {
    $this->body = str_replace('##CONFIRMURL##', $url, $this->body);

    return $this->send();
  }




  /**
   * Sends email to a user's with forgot password link
   *
   * @param string $url
   * @return boolean
   */
  function sendForgotPassword($url)
  {
    $this->body = str_replace('##RESETURL##', $url, $this->body);

    return $this->send();
  }




  /**
   * Sends email to a user's with a image related email
   *
   * @param string $url
   * @return boolean
   */
  function sendImage($id, $from = false)
  {
    $image = new Image($id);
    if (! $image->exists())
    {
      return true;
    }

    if ($from !== false)
    {
      if ($from->exists())
      {
        $this->body = str_replace('##USERNAME##', $from->displayName, $this->body);
      }
    }

    $this->body = str_replace('##TITLE##', $image->title, $this->body);
    $this->body = str_replace('##LINK##', $this->sc->common->siteURL() . '/capture/' . $image->displayID, $this->body);

    return $this->send();
  }




  /**
   * Sends email to a reviewer about an image to review
   *
   * @param string $url
   * @return boolean
   */
  function sendReviewImage($url)
  {
    $this->review = true;
    $this->alwaysSend = true;
    $this->body = str_replace('##IMAGEURL##', $url, $this->body);

    return $this->send();
  }




  /**
   * Sends email to a user's with a link
   *
   * @param string $url
   * @return boolean
   */
  function sendWithLink($url)
  {
    $this->body = str_replace('##LINK##', $url, $this->body);

    return $this->send();
  }




  /**
   * Sends test email
   *
   * @return boolean
   */
  function testEmail($email)
  {
    if (empty($email))
    {
      return false;
    }

    $mail = new PHPMailer();

    $mail->SMTPDebug = 1;
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = $this->host; // Specify main and backup SMTP
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = $this->host_user; // SMTP username
    $mail->Password = $this->host_pass; // SMTP password

    $mail->From = $this->host_email;
    $mail->FromName = $this->host_from;
    $mail->AddAddress($email, 'TEST NAME');
    $mail->Subject = "TESTING";
    $mail->Body = 'JUST TESTING :)';
    $mail->AltBody = 'JUST TESTING :)';

    $mail->Send();
  }




  /**
   * Initialises object
   *
   * @param integer $id
   */
  protected function load($id)
  {
    // Declare helper
    $sql = new SQLHelper();

    // Prepare query
    $sql->select('*');
    $sql->from('sc_email_templates');

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

    // Is there rows?
    if ($sql->hasRows())
    {
      $this_email = $sql->fetchArray();
      $this->alwaysSend = $this_email['always_send'];
      $this->id = $this_email['id'];
      $this->bcc = $this_email['bcc'];
      $this->body = $this_email['body'];
      $this->html = $this_email['html'] == 1 ? true : false;
      $this->name = $this_email['name'];
      $this->subject = $this_email['subject'];
    }
  }

  // _Destruct
  function __destruct()
  {}
}

?>