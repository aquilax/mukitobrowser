<?php
/**
 * Description of fight_model
 *
 * @author aquilax
 */

require_once 'BASE_Model.php';

class Fight_model extends BASE_Model{

  function load($fid){
    $this->db->where('id', $fid);
    $this->db->where('character_id', $this->char->get('id'));
    $query = $this->db->get('fight', 1);
    if ($query->num_rows() == 1){
      $this->data = $query->row_array();
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function doFight($post){
    if (!$post){
      //Do nothing
      return;
    }
    if ($post['attack']){
      //attack monster
      //monster attacks
    } elseif($post['spell']){
      //cast spell
      //monster attacks
    } elseif($post['run']){
      //Shame on you
      //chance to run
    }
  }
}
?>
