<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BASE_Model
 *
 * @author aquilax
 */
class BASE_Model extends Model{

  protected $data = array();

  function get($key, $default = FALSE){
    if (isset($this->data[$key])){
      return $this->data[$key];
    } else {
      return FALSE;
    }
  }

  function set($key, $val){
    $this->data[$key] = $val;
  }
}
?>
