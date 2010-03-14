<?php
/**
 * Description of monster
 *
 * @author aquilax
 */
class Monster extends Player{

  function damage_max(){
    return $this->get('damage_max');
  }

  function damage_min(){
    return $this->get('damage_min');
  }

  function attack_success_rate(){
    return 0;
  }

  function defense_power(){
    return 0;
  }

  function defense_success_rate(){
    return 0;
  }

  function attack_speed(){
    return 0;
  }

  function skill_damage(){
    return 0;
  }

  function max_hp(){
    return 0;
  }

  function max_mp(){
    return 0;
  }

  function sp_on_level(){
    return 0;
  }
}
?>