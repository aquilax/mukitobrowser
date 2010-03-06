<?php
/**
 * Description of sign_model
 *
 * @author aquilax
 */
class Sign_model extends Model{

  function load(){
    $x = $this->char->get('xpos');
    $y = $this->char->get('ypos');
    $mid = $this->char->get('map_id');
    $this->db->select('s.*');
    $this->db->where('p.x', $x);
    $this->db->where('p.y', $y);
    $this->db->where('p.mid', $mid);
    $this->db->where('p.controller', 'sign');
    $this->db->join('sign s', 's.id = p.iid');
    $query = $this->db->get('place p', 1);
    return $query->row_array();
  }
}
?>
