<?php

/**
 * Object to provide sql query functions
 * 
 * @author Robert Holden
 */
class Query
{
  /**
   *
   * @var db
   */
  private $db;
  private $custom = '';
  private $select = array();
  private $insert = '';
  private $update = '';
  private $from = '';
  private $where = '';
  private $having = '';
  private $groupBy = array();
  private $order = array();
  private $limit = '';
  private $values = array(
      array(),
      array()
  );
  private $result = false;
  private $fetchArray = false;
  private $fetchAssoc = false;




  /**
   *
   * @param db $db          
   */
  public function __construct($db = '')
  {
    if (empty($db))
    {
      global $sc;
      $this->db = $sc->db;
    } 

    else
    {
      $this->db = $db;
    }
  }




  /**
   * Prepare select
   * 
   * @param string $str
   *          The query to add
   */
  public function select($str)
  {
    array_push($this->select, $str);
  }




  /**
   * Prepare insert
   * 
   * @param string $table          
   */
  public function insert($table)
  {
    $this->insert = $table;
  }




  /**
   * Prepare update
   * 
   * @param string $table          
   */
  public function update($table)
  {
    $this->update = $table;
  }




  /**
   * Prepare update
   * 
   * @param string $table          
   */
  public function insertUpdate($table, $create = false)
  {
    $this->clear();
    
    if ($create)
    {
      $this->insert = $table;
    } 

    else
    {
      $this->update = $table;
    }
  }




  /**
   * Prepare delete
   * 
   * @param string $table          
   */
  public function delete($table)
  {
    $this->delete = $table;
  }




  /**
   * Appends string to from
   * 
   * @param string $str
   *          The query to add
   * @param string $sep
   *          The separator at the START of the string
   */
  public function from($str)
  {
    $this->from .= ' ' . $str . ' ';
  }




  /**
   * Appends string to where
   * Please ensure you sanitise your variables!
   * 
   * @param string $str
   *          The query to add
   * @param string $sep
   *          The separator at the START of the string
   */
  public function where($str, $sep = '')
  {
    $this->where .= ' ' . $sep . ' ' . $str;
  }




  /**
   * Add specific where parameter clause
   * 
   * @param string $col          
   * @param int $input          
   */
  public function whereParam($col, $cond, $input, $sep = '')
  {
    $input = is_numeric($input) ? $input : "'" . $this->sanitise($input) . "'";
    $this->where .= ' ' . $sep . ' ' . $col . ' ' . $cond . ' ' . $input;
  }




  /**
   * Appends string to group by
   *
   * @param string $str
   *          The query to add
   */
  public function groupBy($str)
  {
    array_push($this->groupBy, $this->sanitise($str));
  }
  
  
  
  /**
   * Appends string to having
   * Please ensure you sanitise your variables!
   * 
   * @param string $str
   *          The query to add
   * @param string $sep
   *          The separator at the START of the string
   */
  public function having($str, $sep = '')
  {
    $this->having .= ' ' . $sep . ' ' . $str;
  }



  /**
   * Appends string to order
   * 
   * @param string $str
   *          The query to add
   */
  public function order($str)
  {
    array_push($this->order, $this->sanitise($str));
  }




  /**
   * Appends string to limit
   * 
   * @param string $str
   *          The query to add
   */
  public function limit($str)
  {
    $this->limit .= $this->sanitise($str);
  }




  /**
   * Prepare insert values
   * 
   * @param string $col          
   * @param unknown $value          
   */
  public function values($col, $value)
  {
    $value = is_numeric($value) ? $value : "'" . $this->sanitise($value) . "'";
    array_push($this->values[0], $col);
    array_push($this->values[1], $value);
  }




  /**
   * Custom query
   * 
   * @param unknown $query          
   */
  public function custom($query)
  {
    $this->custom .= $query;
  }

  
  
  public function executeCount($debug = false)
  {
    // Prepare query
    $_query = '';
    
    // Prepare select query
    if (! empty($this->select))
    {
      $_selects = $this->select;
      $_select = empty($_selects) ? '' : 'SELECT ' . implode(',', $_selects);
      $_from = empty($this->from) ? '' : ' FROM ' . $this->from;
      $_where = empty($this->where) ? '' : ' WHERE ' . $this->where;
      $_having = empty($this->having) ? '' : ' HAVING ' . $this->having;
      $_groupBy = empty($this->groupBy) ? '' : ' GROUP BY ' . implode(',', $this->groupBy);
    
      $_query = $_select . $_from . $_where . $_having . $_groupBy;
    }
    
    // Output query for debugging
    if ($debug)
    {
      echo 'Query (' . $_query . ' ) <br />';
      return 0;
    }
    
    // Execute query
    if (! empty($_query))
    {
      $_result = $this->db->query($_query);
    
      if (! $_result)
      {
        return 0;
      }
      
      return $this->db->countRows($_result);
    }
    
    return 0;
  }



  public function execute($debug = false)
  {
    // Prepare query
    $_query = '';
    
    // Prepare select query
    if (! empty($this->select))
    {
      $_select = empty($this->select) ? '' : 'SELECT ' . implode(',', $this->select);
      $_from = empty($this->from) ? '' : ' FROM ' . $this->from;
      $_where = empty($this->where) ? '' : ' WHERE ' . $this->where;
      $_having = empty($this->having) ? '' : ' HAVING ' . $this->having;
      $_groupBy = empty($this->groupBy) ? '' : ' GROUP BY ' . implode(',', $this->groupBy);
      $_order = empty($this->order) ? '' : ' ORDER BY ' . implode(',', $this->order);
      $_limit = empty($this->limit) ? '' : ' LIMIT ' . $this->limit;
      
      $_query = $_select . $_from . $_where . $_having . $_groupBy . $_order . $_limit;
    }    

    // If query empty, try insert
    elseif (! empty($this->insert) && ! empty($this->values))
    {
      // Make sure cols = values
      if (sizeof($this->values[0]) !== sizeof($this->values[1]))
      {
        return false;
      }
      
      $_insert = 'INSERT INTO ' . $this->insert;
      $_values = '(' . implode(',', $this->values[0]) . ') VALUES (' . implode(',', $this->values[1]) . ')';
      
      $_query = $_insert . $_values;
    }    

    // If query empty, try update
    elseif (! empty($this->update) && ! empty($this->values))
    {
      // Make sure cols = values
      if (sizeof($this->values[0]) !== sizeof($this->values[1]))
      {
        return false;
      }
      
      $_update = 'UPDATE ' . $this->update;
      $_set = ' SET ';
      
      // Loop through cols
      foreach ($this->values[0] as $i => $_col)
      {
        $_set .= ($i == 0 ? '' : ',') . $_col . ' = ' . $this->values[1][$i];
      }
      
      $_where = empty($this->where) ? '' : ' WHERE ' . $this->where;
      
      $_query = $_update . $_set . $_where;
    }    

    // If query empty, try delete
    elseif (! empty($this->delete))
    {
      $_delete = 'DELETE FROM ' . $this->delete;
      $_where = empty($this->where) ? '' : ' WHERE ' . $this->where;
      
      $_query = $_delete . $_where;
    }
    
    // Custom goes on the end of every statement
    $_query .= ' ' . $this->custom;
    
    // Clear query
    $this->clear();
    
    // Output query for debugging
    if ($debug)
    {
      echo 'Query (' . $_query . ' ) <br />';
      //return true;
    } 

    // Execute query
    if (! empty($_query))
    {
      $this->result = $this->db->query($_query);
      
      if ($this->result)
      {
        return true;
      }
    }
    
    return false;
  }




  public function clear()
  {
    $this->custom = '';
    $this->select = array();
    $this->insert = '';
    $this->update = '';
    $this->from = '';
    $this->where = '';
    $this->having = '';
    $this->groupBy = array();
    $this->order = array();
    $this->limit = '';
    $this->values = array(
        array(),
        array()
    );
    $this->result = false;
    $this->fetchArray = false;
    $this->fetchAssoc = false;
  }




  /**
   * Sanitises and input
   * 
   * @param unknown $str
   *          The input to sanitise
   */
  public function sanitise($str)
  {
    $str = $this->db->escape($str);
    return $str;
  }




  public function lastID()
  {
    return $this->db->lastID();
  }




  public function countRows()
  {
    if (! $this->result)
    {
      return 0;
    }
    
    return $this->db->countRows($this->result);
  }




  public function hasRows()
  {
    if (! $this->result)
    {
      return false;
    }
    
    return $this->db->hasRows($this->result);
  }




  public function fetchArray()
  {
    if (! $this->result)
    {
      return array();
    }
    
    return $this->db->fetchArray($this->result);
  }




  public function fetchAssoc()
  {
    if (! $this->result)
    {
      return array();
    }
    
    return $this->db->fetchAssoc($this->result);
  }
}