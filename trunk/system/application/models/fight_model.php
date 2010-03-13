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

  function doUpdate($player, $table){
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

  //((B - D) * L + ((A-D) + (B-D)) * (20 - L) / 2) * S / 20
  //
  //here A - min damage
  //B - max damage
  //D - mobs defence
  //L - your number of items + luck (max 7 i think)
  //S - speed
  function damage($c1, $c2){
    $damage = mt_rand($c1->get('damage_min'), $c1->get('damage_max'));
    if ($damage == $this->char->get('damage_max')){
      $damage *= 2;
      $this->log[] = sprintf('Character strikes excelent hit');
    }
    return $damage;
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
      $this->log[] = 'Player\'s turn';
      foreach($post['mid'] as $cid => $mid){
        if ($post['aid'][$cid] == 1){
          $monster = $this->monsters->getId($mid);
          $damage = $this->damage($this->char, $monster);
          
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
          $this->doUpdate($monster, 'enemy');
        } elseif ($post['aid'][$cid] == 0){
          $this->log[] = sprintf('Character passes');
        }
      }
      //monster's turn
      $this->log[] = 'Monster\'s turn';
      foreach ($this->monsters->list as $monster){
        $char = $this->players->getId($cid);
        $damage = $this->damage($monster, $this->char);
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
        $this->doUpdate($char, 'characters');
      }
      $this->set('fight_round', $this->get('fight_round')+1);
      $this->update('fight');
    } elseif(isset($post['run'])){
      $this->log[] = 'Run pressed';
      $this->doRun();
      //Shame on you
      //chance to run
    }
    if ($this->monstersDead()){
      $this->finishFight();
    }
    //check outcome of the battle
  }

  function finishFight(){
    $this->set('state', 2);
    $this->update('fight');
    $this->log[] = 'All dead';
    redirect('fight/victory');
  }

  function victory(){
    $exp = 0;
    foreach ($this->monsters->list as $monster){
      $exp += $monster->get('hp_max');
    }
    $this->char->set('experience', $this->char->get('experience') + $exp);
    $this->char->set('state', 1);
    $this->char->update('characters');
    return $exp;
  }


}
?>
