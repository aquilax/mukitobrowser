<style>
  body{font-size:0.8em}
  table td{vertical-align:top}
</style>
<?php
function stats($p){
  echo '<h3 class="name">'.$p['name'].'</h3>';
  echo '<ul>';
  echo '<li class="level">'.lang('Level').': '.$p['level'].'</li>';
  echo '<li class="hp">'.lang('HP').': '.$p['hp'].'/'.$p['hp_max'].'</li>';
  echo '<li class="mp">'.lang('MP').': '.$p['mp'].'/'.$p['mp_max'].'</li>';
  echo '<li class="attack">'.lang('Attack').': '.$p['attack'].'</li>';
  echo '<li class="defense">'.lang('Defense').': '.$p['defense'].'</li>';
  echo '<li class="damage">'.lang('Damage').': '.$p['damage_min'].'/'.$p['damage_max'].'</li>';
  echo '<li class="attack">'.lang('Wind resistant').': '.$p['wind_res'].'</li>';
  echo '<li class="attack">'.lang('Poison resistant').': '.$p['poison_res'].'</li>';
  echo '<li class="attack">'.lang('Ice resistant').': '.$p['ice_res'].'</li>';
  echo '<li class="attack">'.lang('Water resistant').': '.$p['water_res'].'</li>';
  echo '<li class="attack">'.lang('Fire resistant').': '.$p['fire_res'].'</li>';
  echo '</ul>';
}
echo form_open('fight');
echo '<table border="1">';
echo '<tr>';
echo '  <td>';
echo '<h2>'.lang('Player').'</h2>';
echo '    <table border="1">';
foreach ($players->list as $p){
  $id = $p->get('id');
  echo '    <tr>';
  echo '      <td>';
  stats($p->data);
  echo '      </td>';
  echo '      <td>';
  echo lang('Enemy', 'mid'.$id);
  echo form_dropdown('mid['.$id.']', $mlist, set_value('mid['.$id.']', 0), 'id="mid'.$id.'"');
  echo '<br/>';
  foreach($alist as $k =>$v){
    echo '<label>';
    echo form_radio('aid['.$id.']', $k, $k==1);
    echo $v.' <img src="http://localhost/phpmyadmin/themes/original/img/b_edit.png"/>';
    echo '</label><br/>';
  }
//  echo lang('Action', 'aid'.$id);
//  echo form_dropdown('aid['.$id.']', $alist, 0, 'id="aid'.$id.'"');
  echo '      </td>';
  echo '    </tr>';
}
echo '    </table>';

echo '</td>';
echo '<td>';
echo form_submit('fight', lang('Fight'));
echo form_submit('run', lang('Run'));
echo '<br />';
echo implode('<br/>', $log);
echo '</td>';
echo '<td>';
echo '<h2>'.lang('Enemy').'</h2>';
echo '<table border="1">';
foreach ($monsters->list as $p){
  echo '<tr>';
  echo '<td>';
  echo '<img src="/i/m/'.$p->data['monster_id'].'.jpg" />';
  echo '</td>';
  echo '<td>';
  stats($p->data);
  echo '</td>';
  echo '</tr>';
}
echo '</table>';
echo '</td>';
echo '</tr>';
echo '</table>';
echo form_close();
//print_r($players);
//print_r($monsters);
?>
