<?php

/**
 * Object for all database communications
 * 
 * @author unknown
 */
class db
{
  private $connection;
  private $selectdb;
  private $config;
  private $charset;


 

  /**
   *
   * @param Config $config          
   */
  function __construct($config, $charset)
  {
    $this->config = $config;
    $this->charset = $charset;
  }




  /**
   * Opens connections
   * 
   * @return boolean
   */
  public function openConnection()
  {
    try
    {
      $conn = $this->connection = mysqli_connect($this->config->hostname, $this->config->username, $this->config->password);
      if ($conn)
      {
        $this->selectdb = mysqli_select_db($this->connection, $this->config->database);
        mysqli_set_charset($this->connection, $this->charset);
        return true;
      } 

      else
      {
        return false;
      }
    } catch (exception $e)
    {}
  }




  /**
   * Closes connection
   * 
   * @return exception
   */
  public function closeConnection()
  {
    try
    {
      mysqli_close($this->connection);
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Returns current connections
   * 
   * @return mysqli_connect
   */
  public function returnConnection()
  {
    return $this->connection;
  }




  /**
   * Adds slashes to specified string
   * 
   * @param string $string          
   * @return string
   */
  public function ecapeString($string)
  {
    return addslashes($string);
  }




  /**
   * Passes queries to the database
   * 
   * @param $string $query          
   * @return mysqli_query|exception
   */
  public function query($query)
  {
    try
    {
      $theQuery = mysqli_query($this->connection, $query);
      if (! $theQuery && (DEVELOPMENT == 1)) { echo $this->error() . ' _____ SQL: ' . $query; }
      
      return $theQuery;
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Returns last added id added to the database
   * 
   * @return integer|exception
   */
  public function lastID()
  {
    try
    {
      $id = $this->connection->insert_id;
      return $id;
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Pings server for response
   * 
   * @return boolean|exception
   */
  public function pingServer()
  {
    try
    {
      if (! mysqli_ping($this->connection))
      {
        return false;
      } else
      {
        return true;
      }
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Returns whether a mysqli_query has rows
   * 
   * @param mysqli_query $result          
   * @return boolean|exception
   */
  public function hasRows($result)
  {
    try
    {
      if (mysqli_num_rows($result) > 0)
      {
        return true;
      } else
      {
        return false;
      }
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Counts row of a mysqli_query
   * 
   * @param mysqli_query $result          
   * @return exception
   */
  public function countRows($result)
  {
    try
    {
      return mysqli_num_rows($result);
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Fetches associated data from mysqli_query
   * 
   * @param unknown $result          
   * @return exception
   */
  public function fetchAssoc($result)
  {
    try
    {
      return mysqli_fetch_assoc($result);
    } catch (exception $e)
    {
      return $e;
    }
  }




  /**
   * Fetches array of data from mysqli_query
   * 
   * @param unknown $result          
   * @return exception
   */
  public function fetchArray($result)
  {
    try
    {
      return mysqli_fetch_array($result);
    } catch (exception $e)
    {
      return $e;
    }
  }
  
  
  /**
   * returns last error
   */
  public function error()
  {
    return mysqli_error($this->connection);
  }




  /**
   * Escape specified string to make SQL safe
   * 
   * @param unknown $string          
   * @return exception
   */
  public function escape($string)
  {
    try
    {
      return mysqli_real_escape_string($this->connection, $string);
    } catch (exception $e)
    {
      return $e;
    }
  }
  
  
  /**
   * Sets charset
   */
  public function setCharset($charset) 
  {
    try {
      mysqli_set_charset($this->connection, $charset);
    } catch (exception $e) {}
  }




  /**
   * Destruct
   */
  function __destruct()
  {}
}

?>