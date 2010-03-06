<?php
  if($characters){
    echo '<table>';
    echo '<tr>';
    echo '<th>'.lang('Name').'</th>';
    echo '<th>'.lang('Class').'</th>';
    echo '<th>'.lang('Level').'</th>';
    echo '</tr>';
    foreach($characters as $character){
      echo '<tr>';
      echo '<td>'.anchor('home/start/'.$character['id'], $character['name']).'</td>';
      echo '<td>'.$character['classname'].'</td>';
      echo '<td>'.$character['level'].'</td>';
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo sprintf(lang('You don\'t have any characters. Go and % one'), anchor('user/create_character', 'create'));
  }
?>
