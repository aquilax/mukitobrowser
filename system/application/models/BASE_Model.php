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
  protected $update = array();

  function get($key, $default = FALSE){
    if (isset($this->data[$key])){
      return $this->data[$key];
    } else {
      return FALSE;
    }
  }

  function set($key, $val){
    $ov = $this->get($key);
    if ($ov != $val){
      $this->data[$key] = $val;
      $this->update[$key] = $val;
    }
  }

  function update($table, $key = 'id'){
    if ($this->update){
      $this->db->where($key, $this->get($key));
      $this->db->update($table, $this->update);
    }
  }
}
?>
