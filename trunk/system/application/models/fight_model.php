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
    $this->list[$data['id']] = new Player($data, $table);
  }

  function getId($id){
    return $this->list[$id];
  }
}

class Fight_model extends BASE_Model{

  var $players = null;
  var $monsters = null;
  var $time = 0;
  var $log = array();
  var $run = FALSE;
  var $dead_state = 255;

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

  function doRun(){
    $this->char->set('state', 1);
    $this->char->set('fight_id', 0);
    $this->char->update('characters');
    $this->run = TRUE;
  }

  function update($player, $table){
    if ($player->update){
      $this->db->where('id', $player->get('id'));
      $this->db->update($table, $player->update);
    }
  }

  function monstersDead(){
    foreach($this->monsters->list as $monster){
      if ($monster->get('state') != $this->dead_state){
        return FALSE;
      }
    }
    return TRUE;
  }

  function doFight($post){
    //check regeneration first;
    $this->log[] = 'Regenerate players';
    $this->regenerateParty($this->players);
    $this->log[] = 'Regenerate monsters';
    $this->regenerateParty($this->monsters);

    if (!$post){
      //Do nothing?
      return;
    }
    if (isset($post['fight'])){
      $this->log[] = 'Fight pressed';
      //Character's turn
      foreach($post['mid'] as $cid => $mid){
        if ($post['aid'][$cid] == 1){
          $monster = $this->monsters->getId($mid);
          $damage = mt_rand($this->char->get('damage_min'), $this->char->get('damage_max'));
          if ($damage == $this->char->get('damage_max')){
            $damage *= 2;
            $this->log[] = sprintf('Character strikes excelent hit');
          }
          $take =  $damage - $monster->get('defense');
          if ($take >= 1){
            $hp = $monster->get('hp') - $take;
            $this->log[] = sprintf('Character attacks and takes %d HP', $take);
          } else {
            $hp = $monster->get('hp');
            $this->log[] = sprintf('Character attacks but takes no HP');
          }
          if ($hp < 0){
            $hp = 0;
            $monster->set('state', $this->dead_state);
            $this->log[] = sprintf('% is dead!', $monster->get('name').' '.$monster->get('id'));
          }
          $monster->set('hp', $hp);
          $this->update($monster, 'enemy');
        } elseif ($post['aid'][$cid] == 0){
          $this->log[] = sprintf('Character passes');
        }
      }
      //Monster's turn
      foreach ($this->monsters->list as $monster){
        $char = $this->players->getId($cid);
        $damage = mt_rand($monster->get('damage_min'), $monster->get('damage_max'));
        if ($damage == $monster->get('damage_max')){
          $damage *= 2;
          $this->log[] = sprintf('Monster strikes excelent hit');
        }
        $take =  $damage - $char->get('defense');
        if ($take >= 1){
          $hp = $char->get('hp') - $take;
          $this->log[] = sprintf('Monster attacks and takes %d HP', $take);
        } else {
          $hp = $char->get('hp');
          $this->log[] = sprintf('Monster attacks but takes no HP');
        }
        if ($hp < 0){
          $hp = 0;
          $char->set('state', $this->dead_state);
          $this->log[] = sprintf('Player is dead!');
        }
        $char->set('hp', $hp);
        $this->update($char, 'characters');
      }

      $this->log[] = 'Player\'s turn';
      //monster's turn
      $this->log[] = 'Monster\'s turn';
    } elseif(isset($post['run'])){
      $this->log[] = 'Run pressed';
      $this->doRun();
      //Shame on you
      //chance to run
    }
    if ($this->monstersDead()){
      $this->log[] = 'All dead';
      $this->char->set('state', 1);
      $this->char->update('characters');
      redirect('game');
    }
    //check outcome of the battle
  }
}
?>
