<?php
/**
 * Description of dk
 *
 * @author aquilax
 */
class mg extends Char{

  function damage_max(){
    //Maximum Damage: S/4+E/8+WeaponMaxDamage
    return ceil($this->get('strength') / 4 + $this->get('energy') / 8 + $this->get('damage_max'));
  }

  function damage_min(){
    //Minimum Damage: S/6+E/12+WeaponMinDamage
    return ceil($this->get('strength') / 6 + $this->get('energy') / 12 + $this->get('damage_min'));
  }

  function attack_success_rate(){
    //Attack Success rate: Level*5+A*1.5+S/4
    return ceil($this->get('level') * 5 + $this->get('agility') * 1.5 + $this->get('strength')/4);
  }

  function defense_power(){
    //Base Defensive Power: A/5
    return ceil($this->get('agility')/5);
  }

  function defense_success_rate(){
    //Base Defense Success Rate: A/3
    return ceil($this->get('agility') / 3);
  }

  function attack_speed(){
    //Base Attack Speed:
    //Melee: A/15
    //Wizard: A/20
    return ceil($this->get('agility') / 15);
  }

  function skill_damage(){
    //Base Skill Damage: 200% (constant)
    return 200;
  }
}
?>
