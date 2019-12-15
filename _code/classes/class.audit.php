<?php

/**
 * Audit Class
 * 
 * @author Robert
 */
class Audit extends DbAudit
{




  /**
   * Load Audit
   * 
   * @param unknown $id          
   */
  public function __construct($id = NULL)
  {
    $this->ipAddress = $_SERVER['REMOTE_ADDR'];
    
    $this->get($id);
  }
}