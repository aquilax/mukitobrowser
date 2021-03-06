<?php
/**
 * Description of dk
 *
 * @author aquilax
 */
class dk extends Player{

  function damage_max(){
    //Maximum Damage: S/4 + WeaponMaxDamage
    return ceil($this->get('strength') / 4 + $this->get('damage_max'));
  }

  function damage_min(){
    //Minimum Damage: S/6 + WeaponMinDamage
    return ceil($this->get('strength') / 6 + $this->get('damage_min'));
  }

  function attack_success_rate(){
    //Attack Success rate: Level*5+A*1.5+S/4
    return ceil($this->get('level') * 5 + $this->get('agility') * 1.5 + $this->get('strength')/4);
  }

  function defense_power(){
    //Base Defensive Power: A/3
    return ceil($this->get('agility')/3);
  }

  function defense_success_rate(){
    //Base Defense Success Rate: A/3
    return ceil($this->get('agility') / 3);
  }

  function attack_speed(){
    //Base Attack Speed: A/15
    return ceil($this->get('agility') / 15);
  }

  function skill_damage(){
    //Basic Skill Damage: 200%+(E/10)%
    return ceil(200+($this->get('energy') / 10));
  }

  function max_hp(){
    return 110 + $this->get('level') * 2 + $this->get('vitality') * 3;
  }

  function max_mp(){
    return ceil(20 + $this->get('level') / 2 + $this->get('energy'));
  }

  function sp_on_level(){
    return 5;
  }
}
?>
