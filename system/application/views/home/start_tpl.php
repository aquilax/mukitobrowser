<?php
  print_r($characters);
  if($characters){
    echo '<table>';
    foreach($characters as $character){
      echo '<tr>';
      echo '<td>'.$character['name'].'</td>';
      echo '<td>'.$character['classname'].'</td>';
      echo '<td>'.$character['classname'].'</td>';
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo sprintf(lang('You don\'t have any characters. Go and % one'), anchor('user/create_character', 'create'));
  }
?>
