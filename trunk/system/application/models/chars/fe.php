<?php
/**
 * Description of dk
 *
 * @author aquilax
 */
class fe extends Player{

  function damage_max(){
    //Maximum Damage:
    //Bow's/Crossbow'sMaxDamage+A/4+S/8
    //OtherWeapon'sMaxDamage+(A+S)/4
    //TODO:Fix on weapon stats
    return ceil($this->get('damage_max') + ($this->get('agility')+$this->get('strength'))/4);
  }

  function damage_min(){
    //Minimum Damage:
    //Bow's/Crossbow'sMinDamage+A/7+S/14
    //OtherWeapon'sMinDamage+(A+S)/7
    //TODO:Fix on weapon stats
    return ceil($this->get('damage_min')+($this->get('agility')+$this->get('strength')) / 7);
  }

  function attack_success_rate(){
    //Attack Success rate: Level*5+A*1.5+S/4
    return ceil($this->get('level') * 5 + $this->get('agility') * 1.5 + $this->get('strength')/4);
  }

  function defense_power(){
    //Base Defensive Power: A/10
    return ceil($this->get('agility')/10);
  }

  function defense_success_rate(){
    //Base Defense Success Rate: A/4
    return ceil($this->get('agility') / 4);
  }

  function attack_speed(){
    //Base Attack Speed: A/50
    return ceil($this->get('agility') / 50);
  }

  function skill_damage(){
    //Basic Skill Damage: 200%+(E/10)%
    return ceil(200+($this->get('energy') / 10));
  }
}
?>
