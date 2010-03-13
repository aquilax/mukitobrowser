<?php
/**
 * Description of char
 *
 * @author aquilax
 */
abstract class Char {

  var $data = array();

  abstract protected function damage_max();
  abstract protected function damage_min();
  abstract protected function attack_success_rate();
  abstract protected function defense_power();
  abstract protected function defense_success_rate();
  abstract protected function attack_speed();
  abstract protected function skill_damage();

  function  __construct($data) {
    $this->data = $data;
  }

  function get($key, $default = 0){
    if(isset($this->data[$key])){
      return $this->data[$key];
    } else {
      return $default;
    }
  }
}
?>
