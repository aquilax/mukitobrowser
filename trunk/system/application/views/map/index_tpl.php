<?php
  $xc = 11;
  $yc = 8;
  echo '<table id="world">';
  $oy = 1;
  foreach($map as $y => $row){
    echo '<tr>';
    $ox = 1;
    foreach($row as $x => $ar){
      $b_class='t_'.$ar['t'];
      $o_class='t_'.$ar['o'];
      if ($oy == $yc-1 && $ox == $xc){
        echo '<td id="tl_'.$ox.'_'.$oy.'" class="'.$b_class.'"><div class="'.$o_class.'" id="i_'.$ox.'_'.$oy.'"><a class="dir" id="n" href="#">&uarr;</a></div></td>';
      } elseif($oy == $yc+1 && $ox == $xc) {
        echo '<td id="tl_'.$ox.'_'.$oy.'" class="'.$b_class.'"><div class="'.$o_class.'" id="i_'.$ox.'_'.$oy.'"><a class="dir" id="s" href="#">&darr;</a></div></td>';
      } elseif($oy == $yc && $ox == $xc-1) {
        echo '<td id="tl_'.$ox.'_'.$oy.'" class="'.$b_class.'"><div class="'.$o_class.'" id="i_'.$ox.'_'.$oy.'"><a class="dir" id="w" href="#">&larr;</a></div></td>';
      } elseif($oy == $yc && $ox == $xc+1) {
        echo '<td id="tl_'.$ox.'_'.$oy.'" class="'.$b_class.'"><div class="'.$o_class.'" id="i_'.$ox.'_'.$oy.'"><a class="dir" id="e" href="#">&rarr;</a></div></td>';
      } else {
        echo '<td id="tl_'.$ox.'_'.$oy.'" class="'.$b_class.'"><div class="'.$o_class.'" id="i_'.$ox.'_'.$oy.'"></div></td>';
      }
      $ox++;
    }
    $oy++;
    echo '<tr>';
  }
  echo '</table>';
  echo 'Coord: <span id="xc">'.$x.'.</span>x<span id="yc">'.$y.'</span><span id="loading" style="display:none">...</span>';
?>