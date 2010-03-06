<?php
/**
 * Description of map_model
 *
 * @author aquilax
 */
class Map_model extends Model{

  function makeMap($ary){
    $res = array();
    foreach ($ary as $row){
      $res[$row['y']][$row['x']] = array('t' => $row['t'], 'o' => $row['o']);
    }
    return $res;
  }

  function getMap($x, $y, $mid, $spreadx = 9, $spready = 9){
    $halfx = floor($spreadx / 2);
    $halfy = floor($spready / 2);
    $this->db->select('*, (o-1000) AS o');
    $this->db->where_in('x', range($x-$halfx, $x+$halfx));
    $this->db->where_in('y', range($y-$halfy, $y+$halfy));
    $this->db->order_by('y');
    $this->db->order_by('x');
    $query = $this->db->get('map_'.$mid);
    return $this->makeMap($query->result_array());
  }
}
?>
