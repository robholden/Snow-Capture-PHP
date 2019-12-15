<?php

/**
 * Object that stores database connection information
 * 
 * @author Robert Holden
 */
class config
{
  public $hostname;
  public $username;
  public $password;
  public $database;




  /**
   * Connection parameters
   * 
   * @param string $hostname          
   * @param string $username          
   * @param string $password          
   * @param string $database          
   */
  function __construct($hostname = NULL, $username = NULL, $password = NULL, $database = NULL)
  {
    $this->hostname = ! empty($hostname) ? $hostname : "";
    $this->username = ! empty($username) ? $username : "";
    $this->password = ! empty($password) ? $password : "";
    $this->database = ! empty($database) ? $database : "";
  }




  function __destruct()
  {}
}

?>