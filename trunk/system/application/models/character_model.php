<?php

/**
 * Description of character_model
 *
 * @author aquilax
 */

require 'BASE_Model.php';

class Character_model extends BASE_Model{
  
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
}
?>
