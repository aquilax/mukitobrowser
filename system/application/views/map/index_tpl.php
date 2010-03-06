<?php
  echo '<table id="world">';
  $oy = 1;
  foreach($map as $y => $row){
    echo '<tr>';
    $ox = 1;
    foreach($row as $x => $ar){
      $b_class='t_'.$ar['t'];
      $o_class='t_'.$ar['o'];
      echo '<td id="tl_'.$ox.'_'.$oy.'" class="'.$b_class.'"><div class="'.$o_class.'" id="i_'.$ox.'_'.$oy.'"></div></td>';
      $ox++;
    }
    $oy++;
    echo '<tr>';
  }
  echo '</table>';
  echo '<span id="loading" style="display:none">...</span>';
?>