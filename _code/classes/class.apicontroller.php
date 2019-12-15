<?php

class APIController
{
  /**
   * 200: OK, 400: Bad Request, 401: Unauthorized, 403: Forbidden, 404: Not
   * Found
   * 
   * @var integer
   */
  private $session;
  private $user;
  public $code = 401;
  public $data = '';
  public $method = '';
  public $platform = '';




  public function __construct()
  {
    $_sess = new Session();
    $_sess->logIn();
    
    $this->session = $_sess;
    $this->user = $this->session->user;
  }




  /**
   * Make sure there is a get value?
   * 
   * @param unknown $value          
   * @param string $require          
   */
  public function checkGet($value, $require = false)
  {
    if (! isset($_GET[$value]))
    {
      if ($require)
      {
        $this->leave(400);
      }
      return false;
    } 

    elseif (empty($_GET[$value]))
    {
      if ($require)
      {
        $this->leave(400);
      }
      return false;
    } 

    else
    {
      return $_GET[$value];
    }
  }




  /**
   * Make sure there is a post value?
   * 
   * @param unknown $value          
   * @param string $require          
   */
  public function checkPost($value, $require = false)
  {
    if (! isset($_POST[$value]))
    {
      if ($require)
      {
        $this->leave(400);
      }
      
      return false;
    } 

    elseif (empty($_POST[$value]))
    {
      if ($require)
      {
        $this->leave(400);
      }
      
      return false;
    } 

    else
    {
      return $_POST[$value];
    }
  }




  /**
   * Makes sure session is POST
   */
  public function ensurePost()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
      $this->data = array(
          'error' => 'Request is unsafe'
      );
      $this->leave(403);
    }
  }




  /**
   * Returns api method from _GET
   * 
   * @return string
   *
   */
  public function ensureMethod()
  {
    $this->method = (! empty($_GET['method'])) ? strtolower($_GET['method']) : false;
    if (! $this->method)
    {
      $this->data = array(
          'error' => 'Stop poking around...'
      );
      $this->leave(404);
    }
  }




  /**
   *
   * @param unknown $level          
   */
  public function ensureWebAccess($level)
  {
    $_session = $this->session;
    $_user = $this->user;
    
    if (! $_session || ! $_user)
    {
      $this->leave(401);
    } 

    else 
      if (! $_session->exists())
      {
        $this->leave(401);
      } 

      else 
        if (! $_user->exists())
        {
          $this->leave(401);
        } 

        else 
          if (! $_user->status >= $level)
          {
            $this->leave(401);
          }
  }




  /**
   * Returns api platform from _GET
   * 
   * @return string
   *
   */
  public function isToken()
  {
    $this->platform = (! empty($_GET['platform'])) ? strtolower($_GET['platform']) : false;
    if (! $this->platform)
    {
      $this->data = array(
          'error' => 'Stop poking around...'
      );
      $this->leave(404);
    }
    
    if ($this->platform != PLATFORM_TOKEN && $this->platform != PLATFORM_WEB)
    {
      $this->data = array(
          'error' => 'Stop poking around...'
      );
      $this->leave(404);
    }
  }




  /**
   * Exit api
   */
  public function leave($code = 200)
  {
    $this->code = $code;
    if ($code !== 200)
    {
      $this->data = "";
    }
    $this->outputJSON();
    exit();
  }




  /**
   * Returns session
   */
  public function session()
  {
    return $this->session;
  }




  /**
   * Returns user
   * @return User
   */
  public function user()
  {
    return $this->user;
  }




  /**
   * Outputs response to json
   */
  public function outputJSON()
  {
    $http_response_code = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found'
    );
    
    // Set HTTP Response
    header('HTTP/1.1 ' . $this->code . ' ' . $http_response_code[$this->code]);
    
    // Set HTTP Response Content Type
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($this->data))
    {
      $this->data = array(
          'status' => $this->code,
          'statusText' => $http_response_code[$this->code]
      );
    }
    
    // Format data into a JSON response
    $json_response = json_encode($this->data);
    
    // Deliver formatted data
    echo $json_response;
  }




  /**
   * Returns all post data
   */
  public function requestData()
  {
    return json_decode(file_get_contents("php://input"), true);
  }
}