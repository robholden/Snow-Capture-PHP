<?php

/*
 * Site setup
 */
class SnowCapture {
  /**
   * 
   * @var Common
   */
  public $common;
  
  /**
   * 
   * @var db
   */
  public $db;
  
  /**
   * 
   * @var Functions
   */
  public $functions;
  
  /**
   * 
   * @var Security
   */
  public $security;
  
  /**
   * 
   * @var Session
   */
  public $session;
  
  /**
   * 
   * @var Validation
   */
  public $validate;
  
  /**
   * 
   * @var SiteVars
   */
  public $vars;
  
  /**
   * 
   * @var User
   */
  public $user;

  public function __construct($db)
  {
    $this->db = $db;
  }
  
  public function setup() 
  { 
    $this->common = new Common();
    $this->functions = new Functions();
    $this->security = new Security();
    $this->validate = new Validation();
    $this->vars = new SiteVars();
    $this->session = new Session();
    
    $_sess = new Session();
    $_sess->logIn();    
    
    $this->session = $_sess;
    $this->user = (! $_sess->user) ? new User() : $_sess->user;
    
    $this->vars->pageType = empty($_GET['type']) ? '' : strtolower(htmlentities($_GET['type']));
  }
}

class SiteVars {
  public $hasFilter = false;
  public $pageType = '';
}