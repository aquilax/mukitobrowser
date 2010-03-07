<?php
/**
 * Description of fight_model
 *
 * @author aquilax
 */

require_once 'BASE_Model.php';


//Light player
class Player{
  var $data = array();
  var $update = array();
  var $table = '';

  function __construct($data, $table) {
    $this->data = $data;
    $this->table = $table;
  }

  function get($key, $default = FALSE){
    if (isset($this->data[$key])){
      return $this->data[$key];
    } else {
      return FALSE;
    }
  }

  function set($key, $val){
    $ov = $this->get($key);
    if ($ov != $val){
      $this->data[$key] = $val;
      $this->update[$key] = $val;
    }
  }

  function update($key = 'id'){
    if ($this->update){
      $this->db->where($key, $this->get($key));
      $this->db->update($this->table, $this->update);
    }
  }
}

//Light Party
class Party{
  var $list = array();

  function add($data, $table){
    $this->list[] = new Player($data, $table);
  }
}

class Fight_model extends BASE_Model{

  var $players = null;
  var $monsters = null;
  var $time = 0;

  function load($fid){
    $this->db->where('id', $fid);
    $this->db->where('character_id', $this->char->get('id'));
    $query = $this->db->get('fight', 1);
    if ($query->num_rows() == 1){
      $this->data = $query->row_array();

      //Load Player's party
      $this->players = new Party();
      $this->players->add($this->char->getData(), 'character');

      //Load Monster's party
      $this->monsters = new Party();
      $monsters = $this->getMonstersForFight($fid);
      foreach($monsters as $monster){
        $this->monsters->add($monster, 'enemy');
      }

      //To be fair set the time at the beginning
      $this->time = time();
      
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function getMonstersForFight($fid){
    $this->db->select('*, e.id as id');
    $this->db->where('fight_id', $fid);
    $this->db->join('monster m', 'e.monster_id = m.id');
    $query = $this->db->get('enemy e');
    return $query->result_array();
  }

  function regenerateParty($party){
    //TODO: Regenerate party
  }

  function updateParty($party){
    //TODO: Update Party
  }

  function doFight($post){
    //check regeneration first;
    $this->regenerateParty($this->players);
    $this->regenerateParty($this->monsters);

    if (!$post){
      //Do nothing?
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
    //check outcome of the battle
  }
}
?>
