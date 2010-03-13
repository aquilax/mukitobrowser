<?php

/**
 * Description of character_model
 *
 * @author aquilax
 */

require 'BASE_Model.php';

class Character_model extends BASE_Model{

  var $levelUpPoints = 5;

  function load($cid){
    $this->db->where('id', $cid);
    $query = $this->db->get('characters', 1);
    if ($query->num_rows() == 1){
      $this->data = $query->row_array();
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function levelUp(){
    $this->set('max_experience', $this->get('max_experience')*2);
    $this->set('level', $this->get('level')+1);
    $this->set('points', $this->get('points')+$this->levelUpPoints);
    $this->set('hp', $this->get('hp_max'));
    $this->set('mp', $this->get('mp_max'));
  }

  function update($table, $key = 'id'){
    //Level Up
    if (isset($this->update['experience'])){
      if ($this->update['experience'] >= $this->get('max_experience')){
        $this->levelUp();
      }
    }
    parent::update($table, $key = 'id');
  }

  function distribution(){
    return array(
      '0' => 'select',
      '1' => 'strength',
      '2' => 'agility',
      '3' => 'vitality',
      '4' => 'energy',
      '5' => 'command',
    );
  }

  function distributePoints($post){
    $points = $this->get('points', 0);
    $d = $this->distribution();
    for ($i=0; $i<$points;$i++){
      if (isset($post['p_'.$i]) && isset($d[$post['p_'.$i]]) && $post['p_'.$i] > 0){
        $this->set($d[$post['p_'.$i]], $this->get($d[$post['p_'.$i]])+1);
        $this->set('points', $this->get('points')-1);
      }
    }
    $this->update('characters');
  }
}
?>
