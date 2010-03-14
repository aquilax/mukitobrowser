<?php
/**
 * Description of fight_model
 *
 * @author aquilax
 */

require_once 'BASE_Model.php';
require_once 'chars/player.php';


//Light Party
class Party extends PlayerFactory{
  var $list = array();

  function add($data){
    $this->list[$data['id']] = $this->createPlayer($data);
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
  var $PK_LEVEL_DEFAULT = 3;

  function load($fid){
    $this->db->where('id', $fid);
    $this->db->where('character_id', $this->char->get('id'));
    $query = $this->db->get('fight', 1);
    if ($query->num_rows() == 1){
      $this->data = $query->row_array();

      //Load Player's party
      $this->players = new Party();
      $this->players->add($this->char->getData());

      //Load Monster's party
      $this->monsters = new Party();
      $monsters = $this->getMonstersForFight($fid);
      foreach($monsters as $monster){
        $monster['class_id'] = 255;
        $this->monsters->add($monster);
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
    $dmin = $c1->damage_min();
    $dmax = $c1->damage_max();
    $damage = mt_rand($dmin, $dmax);
    if ($damage == $dmax){
      $damage *= 2;
      $this->log[] = sprintf('%s strikes excelent hit', $c1->get('name'));
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
          $char = $this->players->getId($cid);
          $monster = $this->monsters->getId($mid);
          $damage = $this->damage($char, $monster);
          
          $take =  $damage - $monster->get('defense');
          if ($take >= 1){
            $hp = $monster->get('hp') - $take;
            $this->log[] = sprintf('%s attacks and takes %d HP', $char->get('name'), $take);
          } else {
            $hp = $monster->get('hp');
            $this->log[] = sprintf('%s attacks but takes no HP', $char->get('name'));
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

      $char = $this->players->getId($cid);
      foreach ($this->monsters->list as $monster){
        if ($monster->get('state') == $this->dead_state){
          //Death monsters don't bite;
          continue;
        }
        //FIXME: Wrong there may be be more than one players in the party
        $damage = $this->damage($monster, $char);
        $take =  $damage - $char->get('defense');
        if ($take >= 1){
          $hp = $char->get('hp') - $take;
          $this->log[] = sprintf('%s attacks and takes %d HP',$monster->get('name'), $take);
        } else {
          $hp = $char->get('hp');
          $this->log[] = sprintf('%s attacks but takes no HP', $monster->get('name'));
        }
        if ($hp < 0){
          $hp = 0;
          $char->set('state', $this->dead_state);
          $this->log[] = sprintf('%s is dead!', $char->get('name'));
          break;
        }
        $char->set('hp', $hp);
      }
      //Update character(s)
      $this->doUpdate($char, 'characters');
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

  function getDrops($monster, $n){
    $this->db->where('min_level' >= $monster->get('level'));
    $this->db->where('max_level' <= $monster->get('level'));
    $this->db->where_in('map_id', array(-1, $this->char-get('map_id')));
    $this->db->join('item i', 'item_id = i.id');
    $r = mt_rand(1, 10000);
    $this->db->order_by(('rate-'.$r), 'ASC', FALSE);
    $query = $this->db->get('drop_rate', $n);
    return $query->result_array();
  }

  function _getExpOnPlayerDie($mlevel){
    $minexp = $this->calcExp($mlevel-1);
    $maxexp = $this->calcExp($mlevel);
    $subexp = 0;
    $clevel = $this->char->get('level');
    $cexp = $this->char->get('experience');

    if( $clevel == $this->PK_LEVEL_DEFAULT-1 ) {
      $subexp = $cexp-((($maxexp-$minexp)*2)/100);
    } else if( $clevel == $this->PK_LEVEL_DEFAULT ){
      $subexp = $cexp-((($maxexp-$minexp)*4)/100);
    } else if( $clevel == $this->PK_LEVEL_DEFAULT+1){
      $subexp = $cexp-((($maxexp-$minexp)*6)/100);
    } else if( $clevel == $this->PK_LEVEL_DEFAULT+2 ){
      $subexp = $cexp - ((($maxexp-$minexp)*8)/100);
    } else if( $clevel >= $this->PK_LEVEL_DEFAULT+3 ){
      $subexp = $cexp-((($maxexp-$minexp)*10)/100);
    } else {
      $subexp = $cexp-((($maxexp-$minexp)*2)/100);
    }
    if( $subexp < $minexp ) {
      $subexp = $minexp;
    }
  }

  function _getExp($char, $monster){
    $exp = 0;
    $maxexp = 0;
    $level = (($monster->get('level')+10) * $monster->get('level'))/4;

    if (($monster->get('level')+10) < $char->get('level')){
      $level = $level * ($monster->get('level')+10) / $char->get('level');
    }
    if ($level > 0){
      $maxexp = ceil($level/2);
    } else {
      $level = 0;
    }
    if ($maxexp < 1){
      $exp = $level;
    } else {
      $exp = $level+(mt_rand(0, $maxexp));
    }
    return ceil(($monster->get('damage_max')*$exp)/$monster->get('hp_max'));
  }

  function _getMoney($exp, $monster){
    $rate = $monster->get('money_rate');
    if ($rate == 0) $rate = 1;
    if (mt_rand(1, $rate) < 10){
      return $exp;
    } else {
      return 0;
    }
  }

  function victory(){
    $exp = 0;
    $money = 0;
    foreach ($this->monsters->list as $monster){
      $gexp = $this->_getExp($this->char, $monster);
      $exp += $gexp;
      $money += $this->_getMoney($gexp, $monster);
    }
    $this->log[] = sprintf(lang('Got %d experience'), $exp);
    if ($money){
      $this->log[] = sprintf(lang('Got %d zen'), $money);
    }
    $ol = $this->char->get('level');
    $this->char->set('experience', $this->char->get('experience') + $exp);
    $this->char->set('money', $this->char->get('money') + $money);
    $this->char->set('state', 1);
    $this->char->update('characters');
    $nl = $this->char->get('level');
    if ($ol < $nl){
      $this->log[] = sprintf(lang('Level up to level %d'), $nl);
    }
  }
}
?>
