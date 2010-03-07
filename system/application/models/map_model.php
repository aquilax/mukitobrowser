<?php
/**
 * Description of map_model
 *
 * @author aquilax
 */
class Map_model extends Model{

  var $spreadx = 21;
  var $spready = 15;

  function makeMap($ary){
    $res = array();
    foreach ($ary as $row){
      $res[$row['y']][$row['x']] = array('t' => $row['t'], 'o' => $row['o']);
    }
    return $res;
  }

  function getMap($x, $y, $mid){
    $halfx = floor($this->spreadx / 2);
    $halfy = floor($this->spready / 2);
    $this->db->select('*, (o-1000) AS o');
    $this->db->where_in('x', range($x-$halfx, $x+$halfx));
    $this->db->where_in('y', range($y-$halfy, $y+$halfy));
    $this->db->order_by('y');
    $this->db->order_by('x');
    $query = $this->db->get('map_'.$mid);
    return $this->makeMap($query->result_array());
  }

  function checkTile($x, $y, $mid){
    $this->db->select('t, (o-1000) AS o, IF(t.move+t2.move=2, 1, 0 ) as move, (t.chancetofight+t2.chancetofight)/2 AS chancetofight, pid', FALSE);
    $this->db->where('x', $x);
    $this->db->where('y', $y);
    $this->db->join('tile t', 't.id = t');
    $this->db->join('tile t2', 't2.id = o', 'LEFT');
    $query = $this->db->get('map_'.$mid);
    if ($query->num_rows()>0){
      $res = $query->row_array();
      return $res;
    }
    return array('t' => 0, 'move'=> 0, 'chancetofight'=> 0, 'pid' => 0);
  }

  function loadPlace($pid){
    $this->db->where('id', $pid);
    $res = $this->db->get('place', 1);
    return $res->row_array();
  }

  function startAFight(){
    //FIXME: multiple enemies here
    //Choose enemies
    $mid = 1;
    //ugly but should work
    $this->db->where('id', $mid);
    $query = $this->db->get('monster', 1);
    $monster = $query->row_array();
    
    $data = array(
      'character_id' => $this->char->get('id'),
      'xpos' => $this->char->get('xpos'),
      'ypos' => $this->char->get('ypos'),
      'map_id' => $this->char->get('map_id'),
      'fight_round' => '0',
      'state' => 1
    );
    $this->db->insert('fight', $data);
    $fid = $this->db->insert_id();
    //insertEnemies
    $data = array(
      'fight_id' => $fid,
      'monster_id' => $monster['id'],
      'hp' => $monster['hp_max'],
      'hp_next_regen' => time()+$monster['hp_regen_time'],
      'mp' => $monster['mp_max'],
      'state' => 1,
    );
    $this->db->insert('enemy', $data);
    return $fid;
  }


  function move($post){
    $ret = array();
    $ret['c'] = 'game/explore';

    $x = $this->char->get('xpos');
    $y = $this->char->get('ypos');

    if (isset($post["north"]) || (isset($post['dir']) && $post['dir'] == 'n')) {
      $y--;
    }
    if (isset($post["south"]) || (isset($post['dir']) && $post['dir'] == 's')) {
      $y++;
    }
    if (isset($post["east"])  || (isset($post['dir']) && $post['dir'] == 'e')) {
      $x++;
    }
    if (isset($post["west"])  || (isset($post['dir']) && $post['dir'] == 'w')) {
      $x--;
    }

    $res = $this->checkTile($x, $y, $this->char->get('map_id'));

    if (!$res || $res['move'] == 0){
      $x = $this->char->get('xpos');
      $y = $this->char->get('ypos');
    }

    $this->char->set('xpos', $x);
    $this->char->set('ypos', $y);

    if ($res && $res['pid']){
      //Place
      $place = $this->loadPlace($res['pid']);
      if ($place){
        //Travel to town
        if ($place['controller'] == 'town'){
          $townrow = $this->travelTo($place['iid'], $userrow);
          $data = array_merge($data, $townrow);
        }
        $ret['c'] = $place['controller'];
      }
    } else {
      $max = 2+(10-$res['chancetofight']);
      $chancetofight = mt_rand(1,$max);
      $fight = FALSE;
      $chancetofight = 1;
      if ($chancetofight == 1) {
        $this->char->set('state', 2);
        $fid = $this->startAFight();
        $this->char->set('fight_id', $fid);
        $ret['c'] = 'fight';
      } else {
        $this->char->set('state', 1);
      }
    }
    $ret['x'] = $x;
    $ret['y'] = $y;
    $this->char->update('characters');
    return $ret;
  }
}
?>
