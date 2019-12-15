<?php

/**
 *
 * @author Robert Holden
 */
class FilterNotification extends Filter
{
  /**
   *
   * @var Image
   */
  public $image = false;
  
  /**
   * 
   * @var integer
   */
  public $days = false;
  
  /**
   * 
   * @var boolean
   */
  public $deleted = false;
  
  /**
   *
   * @var User
   */
  public $userFrom = false;
  
  /**
   *
   * @var User
   */
  public $userTo = false;
  
  /**
   *
   * @var <array, int>
   */
  public $status = array();
}