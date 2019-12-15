<?php

/**
 *
 * @author Robert Holden
 */
class FilterImage extends Filter
{
  /**
   *
   * @var User
   */
  public $user = false;
  
  /**
   *
   * @var <array, string>
   */
  public $country = array();
  
  /**
   *
   * @var <array, string>
   */
  public $resort = array();
  
  /**
   *
   * @var <array, string>
   */
  public $location = array();
  
  /**
   *
   * @var string
   */
  public $keyword = false;
  
  /**
   *
   * @var <array, string>
   */
  public $date = array();
  
  /**
   *
   * @var <array, string>
   */
  public $tag = array();
  
  /**
   *
   * @var <array, string>
   */
  public $activity = array();
  
  /**
   *
   * @var <array, string>
   */
  public $altitude = array();
  
  /**
   *
   * @var boolean
   */
  public $likes = false;
  
  /**
   *
   * @var <array, int>
   */
  public $randomed = false;
  
  /**
   *
   * @var <array, int>
   */
  public $status = array();
  
  /**
   *
   * @var <array, string>
   */
  public $maps = array();
  
  /**
   *
   * @var <array, float>
   */
  public $latlng = array();
  
  /**
   *
   * @var int
   */
  public $distance = 100;
  
  /**
   *
   * @var <array, float>
   */
  public $region = array();
  
  /**
   *
   * @var string
   */
  public $latest = false;
}