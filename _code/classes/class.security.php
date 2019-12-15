<?php

/**
 * Object to provide security functions
 * 
 * @author Robert Holden
 */
class Security
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
   * Adds current IP to "Naughty List"
   * (I don't know why)
   * 
   * @param integer $output          
   * @return string
   */  
  public function addToNaughtyList($output)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare vars
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    
    // Prepare query
    $sql->insert('sc_attempts');
    $sql->values('ip', $ip_address);
    $sql->values('url', $url);
    $sql->values('status', $output);
    
    // Execute query
    //$sql->execute();
    
    // Apply bans
    $this->banIps();
    
    return 'Insufficient Permissions';
  }
 



  /**
   * Bans a specific IP Address
   * 
   * @param string $ip          
   * @return boolean
   */ 
  public function banIp($ip)
  {   
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_banned_ips');
    $sql->whereParam('ip', '=', $ip);
    
    // Execute query
    if(! $sql->execute())
    {
      return false;
    }
    
    // Add if it doesn't exists
    if (! $sql->hasRows())
    {
      $sql->insertUpdate('sc_banned_ips');
      $sql->values('ip', $ip);
      
      // Execute query
      if(! $sql->execute())
      {
        return false;
      }
    }
      
    // Prepare query
    $sql->delete('sc_attempts');
    $sql->whereParam('ip', '=', $ip);

    // Execute query
    if(! $sql->execute())
    {
      return false;
    }

    // Write the contents to the file,
    // using the FILE_APPEND flag to append the content to the end of the file
    // and the LOCK_EX flag to prevent anyone else writing to the file at the
    // same time
    $dir = WEB_ROOT . '.htaccess';
    if (! file_put_contents($dir, PHP_EOL . 'deny from ' . $ip, FILE_APPEND | LOCK_EX))
    {
      return false;
    }
    
    return true;
  }




  /**
   * Checks and updates banned ip users from attempts table
   */
  public function banIps()
  {
    // Delcare helper
    $sql = new SQLHelper();
    
    // Store db results
    $_zeroArr = array();
    $_onerr = array();
    $_twoArr = array();
    $_toBan = array();
    
    for ($i = 0; $i < 2; $i++)
    {
      // Prepare query
      $sql->select('DISTINCT *');
      $sql->select('COUNT(*) as rows');
      $sql->from('sc_attempts');
      $sql->whereParam('status', '=', $i);
      $sql->whereParam('ip', '<>', '', 'AND');
      
      // Execute query
      if (! $sql->execute())
      {
        return false;
      }
      
      // Store to arr
      $_attempts = ($i == 0 ? MAJOR_ATTEMPTS : ($i == 1 ? MEDIUM_ATTEMPTS : MINOR_ATTEMPTS));
      while ($ip = $sql->fetchArray())
      {
        if ($ip['rows'] >= $_attempts)
        {
          array_push($_toBan, $ip['ip']);
        }
      }
    }
    
    foreach ($_toBan as $ip)
    {
      $this->banIp($ip);
    }
  }




  /**
   * Returns all banned IP Addressed from the database
   * 
   * @return array
   */
  public function bannedIps()
  {
    $output = array();
    $query = "SELECT * FROM sc_banned_ips";
    
    $ips = $this->sc->db->query($query);
    if ($ips)
    {
      while ($ip = $this->sc->db->fetchArray($ips))
      {
        array_push($output, $ip['ip']);
      }
    }
    
    return $output;
  }




  /**
   * Counts all attempts from the database
   * 
   * @param integer $output          
   * @return number
   */
  public function countAttempts($output)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->select('*');
    $sql->from('sc_attempts');
    $sql->whereParam('status', '=', $output);
    
    // Execute query
    $sql->execute();
    
    // Return row total
    return $sql->countRows();
  }




  /**
   * Returns "decrypted" version of a number
   * 
   * @param integer $id          
   * @param boolean $string          
   * @return number
   */
  public function decrypt($id, $iv = false)
  {
    if ($iv !== false)
    {
      return openssl_decrypt($id, ENC_METHOD, ENC_TOKEN, 0, $iv);
    } 

    else
    {
      return $id - (11 * 22 * 33);
    }
  }




  /**
   * Returns "encrpyted" version of a number
   * Used to disguise image id's mostly
   * 
   * @param integer $id          
   * @param boolean $iv          
   * @return number
   */
  public function encrypt($id, $iv = false)
  {
    if ($iv !== false)
    {
      return openssl_encrypt($id, ENC_METHOD, ENC_TOKEN, 0, $iv);
    } 

    else
    {
      return $id + (11 * 22 * 33);
    }
  }




  /**
   * Unbans a specific IP Address
   * 
   * @param string $ip          
   * @return boolean
   */
  public function unbanIp($ip)
  {
    // Declare helper
    $sql = new SQLHelper();
    
    // Prepare query
    $sql->delete('sc_banned_ips');
    $sql->whereParam('ip', '=', $ip);

    // Execute query
    if (! $sql->execute())
    {
      return false;
    }
    
    // Remove from htaccess
    $dir = WEB_ROOT . '.htaccess';
    $contents = file_get_contents($dir);
    $contents = str_replace('deny from ' . $ip, '', $contents);
    if(! file_put_contents($dir, $contents))
    {
      return false;
    }
    
    return true;
  }
}