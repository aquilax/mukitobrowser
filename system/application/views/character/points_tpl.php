<?php
echo form_open('character/points');
for ($i=0; $i<$points; $i++){
  echo form_label('Point '.($i+1), 'point_'.$i);
  echo form_dropdown('p_'.$i, $stats, 0, 'id="point_'.$i.'"');
  echo '<br/>';
}
echo form_submit('distribute', lang('Distribute'));
echo form_close();
?>
