<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inventory_model
 *
 * @author aquilax
 */

class Inventory_model extends Model{

  var $bag = 1;
  var $crate = 2;
  var $equipped = 10;

  var $cid = -1;

  function  __construct() {
    parent::__construct();
    $this->cid = $this->char->get('id');
  }

  function separateByPlace($items){
    $ret = array();
    foreach($items as $row){
      $ret[$row['item_place']][] = $row;
    }
    return $ret;
  }

  function load($places){
    $this->db->select('i.*, inv.*, it.name as itname');
    $this->db->where('character_id', $this->cid);
    $this->db->where_in('item_place', $places);
    $this->db->join('item i', 'inv.item_id = i.id');
    $this->db->join('item_type it', 'i.item_type_id = it.id');
    $this->db->order_by('inv.item_place', 'DESC');
    $this->db->order_by('i.item_type_id');
    $query = $this->db->get('inventory inv');
    return $query->result_array();
  }

  function add($iid){
    $this->db->where('id', $iid);
    $query = $this->db->get('item', 1);
    $data = $query->row_array();
    if ($data){
      $data['item_id'] = $data['id'];
      $data['item_place'] = $this->bag;
      unset($data['id']);
      unset($data['item_type_id']);
      unset($data['name']);
      unset($data['x_size']);
      unset($data['y_size']);
      unset($data['req_level']);
      unset($data['defense']);
      unset($data['classes']);
      $data['character_id'] = $this->cid;
      $this->db->insert('inventory', $data);
      return $this->db->insert_id();
    } else {
      return FALSE;
    }
  }

  function equip($inid){
    $this->db->where('id', $inid);
    $this->db->where('character_id', $this->cid);
    //drop only if the intem is in the bag
    $this->db->where('item_place', $this->bag);
    $data = array(
      'item_place' => $this->equipped
    );
    $this->db->update('inventory', $data);
  }

  function drop($inid){
    $this->db->where('id', $inid);
    //drop only if the intem is in the bag
    $this->db->where('item_place', $this->bag);
    $this->db->where('character_id', $this->cid);
    $this->db->delete('inventory');
  }
}
?>
