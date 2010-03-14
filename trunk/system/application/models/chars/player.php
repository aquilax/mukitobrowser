<?php
/**
 * Description of Player
 *
 * @author aquilax
 */

class PlayerFactory{

  //Player factory
  function createPlayer($data){
    switch($data['class_id']){
      case 1:{
        require_once('dk.php');
        return new dk($data);
        break;
      }
      case 2:{
        require_once('dw.php');
        return new dw($data);
        break;
      }
      case 3:{
        require_once('fe.php');
        return new fe($data);
        break;
      }
      case 4:{
        require_once('mg.php');
        return new mg($data);
        break;
      }
      case 5:{
        //Failsafe to dk
        require_once('sm.php');
        return new dk($data);
        break;
      }
      case 255:{
        require_once('monster.php');
        return new Monster($data);
        break;
      }
    }
  }
}

//Light player
abstract class Player{
  abstract protected function damage_max();
  abstract protected function damage_min();
  abstract protected function attack_success_rate();
  abstract protected function defense_power();
  abstract protected function defense_success_rate();
  abstract protected function attack_speed();
  abstract protected function skill_damage();
  abstract protected function max_hp();
  abstract protected function max_mp();
  abstract protected function sp_on_level();

  var $data = array();
  var $update = array();
  var $expOnFirstLevel = 100;

  function __construct($data){
    $this->data = $data;
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

  //common for all classes
  function experience($level){
    $exp = $this->expOnFirstLevel;
    for($i=0;$i<=$level;$i++){
      $exp = $exp*2;
    }
    return $exp;
  }

  function max_sp(){
    return $this->get('strength') + $this->get('energy') + ($this->get('level') * 10);
  }
}

?>
