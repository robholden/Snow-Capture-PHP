<?php

/**
 * Object to validate data
 * 
 * @author Robert Holden
 */
class Validation
{
  
  /**
   * SnowCapture
   * 
   * @var sc
   */
  private $sc;




  public function __construct()
  {
    global $sc;
    $this->sc = $sc;
  }




  /**
   * Converts email addresses characters to HTML entities to block spam bots.
   * Credit:
   * https://core.trac.wordpress.org/browser/tags/4.2.2/src/wp-includes/formatting.php#L0
   * 
   * @param string $email_address
   *          Email address.
   * @param int $hex_encoding
   *          Optional. Set to 1 to enable hex encoding.
   * @return string Converted email address.
   */
  function antispambot($email_address, $hex_encoding = 0)
  {
    $email_no_spam_address = '';
    
    for ($i = 0, $len = strlen($email_address); $i < $len; $i ++)
    {
      $j = rand(0, 1 + $hex_encoding);
      
      if ($j == 0)
      {
        $email_no_spam_address .= '&#' . ord($email_address[$i]) . ';';
      } 
      
      elseif ($j == 1)
      {
        $email_no_spam_address .= $email_address[$i];
      } 
      
      elseif ($j == 2)
      {
        $email_no_spam_address .= '%' . zeroise(dechex(ord($email_address[$i])), 2);
      }
    }
    
    $email_no_spam_address = str_replace('@', '&#64;', $email_no_spam_address);
    
    return $email_no_spam_address;
  }




  /**
   * Returns banned usernames
   */
  public function bannedUsernames()
  {
    // Get & set banned usernames from file
    $banned_usernames = file_get_contents(WEB_ROOT . 'things/banned_usernames.txt', FILE_USE_INCLUDE_PATH);
    $banned_usernames = preg_replace('/\s+/', '', $banned_usernames);
    return explode(',', $banned_usernames);
  }




  /**
   * Returns banned words
   */
  public function bannedWords()
  {
    // Get & set banned words from file
    $banned_words = file_get_contents(WEB_ROOT . 'things/banned_words.txt', FILE_USE_INCLUDE_PATH);
    $banned_words = preg_replace('/\s+/', '', $banned_words);
    return explode(',', $banned_words);
  }




  /**
   * Returns whether a email exists
   * 
   * @param string $email          
   * @return boolean
   */
  public function emailExists($email)
  {
    // Validate email
    if (strlen($email) == 0 || strlen($email) > 255 || ! filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      return true;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare var
    $_email = $this->sc->security->encrypt(strtolower($email), DEFAULT_IV);
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_users');
    $sql->whereParam('email', '=', $_email);
    $sql->whereParam('status', '>', 0, 'AND');
    
    // Execute query
    $sql->execute();
    
    // Return if there is data
    return $sql->hasRows();
  }




  /**
   * Returns whether a string contains foul language
   * 
   * @param string $string          
   * @return boolean
   */
  public function isClean($string)
  {
    $words = explode(' ', $string);
    $banned_words = $this->bannedWords();
    
    foreach ($words as $word)
    {
      $word = strtolower($word);
      $word1 = str_replace('0', 'o', $word);
      $word1 = str_replace('1', 'i', $word1);
      $word1 = str_replace('3', 'e', $word1);
      $word1 = str_replace('4', 'h', $word1);
      $word2 = preg_replace('/\PL/u', '', $word);
      
      if (in_array($word, $banned_words) || in_array($word1, $banned_words) || in_array($word2, $banned_words))
      {
        return false;
      }
    }
    
    return true;
  }




  /**
   * Returns whether a resort exists
   * 
   * @param string $resort          
   * @return boolean
   */
  public function resortExists($resort)
  {
    // Validate resort
    if (strlen($resort) == 0 || strlen($resort) > 50)
    {
      return true;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_resorts');
    $sql->whereParam('LCASE(name)', '=', strtolower($resort));
    
    // Execute query
    $sql->execute();
    
    // Return if there is data
    return $sql->hasRows();
  }




  /**
   * Returns whether a username exists
   * 
   * @param string $username          
   * @return boolean
   */
  public function usernameExists($username)
  {
    // Validate username
    if (strlen($username) == 0 || strlen($username) > 50 || preg_match("/[^-a-z0-9_]/i", $username))
    {
      return true;
    }
    
    // Make sure it's valid
    if (in_array($username, $this->bannedUsernames()) || in_array($username, $this->bannedWords()))
    {
      return true;
    }
    
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_users');
    $sql->whereParam('LCASE(username)', '=', strtolower($username));
    $sql->whereParam('status', '>', 0, 'AND');
    
    // Execute query
    $sql->execute();
    
    // Return if there is data
    return $sql->hasRows();
  }




  /**
   * Returns whether a date is valid
   * 
   * @param string $date          
   * @return boolean
   */
  public function validateDate($date)
  {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d;
  }
}