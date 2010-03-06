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
}
?>
