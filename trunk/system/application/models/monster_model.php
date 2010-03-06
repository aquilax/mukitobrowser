<?php
/**
 * Description of monster_model
 *
 * @author aquilax
 */

require_once 'BASE_Model.php';

class Monster_model extends BASE_Model{

  function load($mid){
    $this->db->where('id', $mid);
    $query = $this->db->get('monster', 1);
    if ($query->num_rows() == 1){
      $this->data = $query->row_array();
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
?>
