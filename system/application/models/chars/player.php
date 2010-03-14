<?php
/**
 * Description of Player
 *
 * @author aquilax
 */

//Light player
abstract class Player{
  abstract protected function damage_max();
  abstract protected function damage_min();
  abstract protected function attack_success_rate();
  abstract protected function defense_power();
  abstract protected function defense_success_rate();
  abstract protected function attack_speed();
  abstract protected function skill_damage();

  var $data = array();
  var $update = array();
  var $table = '';

  function __construct($data, $table){
    $this->data = $data;
    $this->table = $table;
    //Decorate player
  }

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

  function update($key = 'id'){
    if ($this->update){
      $this->db->where($key, $this->get($key));
      $this->db->update($this->table, $this->update);
    }
  }
}

?>
