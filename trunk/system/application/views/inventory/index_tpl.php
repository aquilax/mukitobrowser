<?php
function options($row){
  $t = '<ul>';
  $t .= '<li>'.lang('Size').': '.($row['x_size']*$row['y_size']).'</li>';
  $t .= '<li>'.lang('Level').': '.$row['level'].'</li>';
  $t .= '<li>'.lang('Defense').': '.$row['defense'].'</li>';
  $t .= '<li>'.lang('Magic defense').': '.$row['magic_defense'].'</li>';
  $t .= '<li>'.lang('Damage').': '.$row['damage_min'].'/'.$row['damage_max'].'</li>';
  $t .= '<li>'.lang('Speed').': '.$row['speed'].'</li>';
  $t .= '<li>'.lang('Durability').': '.$row['durability'].'</li>';
  $t .= '<li>'.lang('Magic power').': '.$row['magic_power'].'</li>';
  $t .= '<li>'.lang('Strength').': '.$row['strength'].'</li>';
  $t .= '<li>'.lang('Agility').': '.$row['agility'].'</li>';
  $t .= '<li>'.lang('Energy').': '.$row['energy'].'</li>';
  $t .= '<li>'.lang('Vitality').': '.$row['vitality'].'</li>';
  $t .= '<li>'.lang('Command').': '.$row['command'].'</li>';
  $t .= '<li>'.lang('Price').': '.$row['zen'].'</li>';
  $t .= '<ul>';
  return $t;
}
foreach($data as $k => $iplace){
  echo '<h3>'.lang('Inventory').'</h3>';
  if ($iplace){
    echo '<table>';
    foreach($iplace as $row){
      echo '<tr>';
      echo '<td>'.$row['itname'].'</td>';
      echo '<td>'.$row['name'].'</td>';
      echo '<td>'.options($row).'</td>';
      echo '<td>'.anchor('inventory/drop/'.$row['id'], lang('drop'), 'class="confirm"').'</td>';
      echo '<td>'.anchor('inventory/equip/'.$row['id'], lang('equip')).'</td>';
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo '<p>'.lang('Your inventory is empty').'</p>';
  }
}
?>
