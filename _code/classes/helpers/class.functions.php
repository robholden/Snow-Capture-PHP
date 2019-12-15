<?php

/**
 * Object to store general functions
 * 
 * @author Robert Holden
 */
class Functions
{




  /**
   * Maps a datarow to an object
   * 
   * @param array $read          
   * @param object $write          
   * @return <boolean, object>
   */
  public function assign($arr, $obj)
  {
    foreach ($arr as $key => $value)
    {
      if (! is_numeric($key))
      {
        $prop = preg_replace_callback('/(_)(\w{1})/', function ($m)
        {
          if (sizeof($m) > 2)
          {
            return strtoupper($m[2]);
          }
        }, $key);
        
        $obj->$prop = $value;
      }
    }
    
    return $obj;
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
   * Generates random string
   * 
   * @param integer $length          
   * @return string
   */
  public function generateRandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i ++)
    {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
  }
}


