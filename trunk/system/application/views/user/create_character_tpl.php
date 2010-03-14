<?php
  echo validation_errors('<div class="error">', '</div>');

  echo form_open('user/create_character');
  echo '<table width="80%">';

echo '<tr>';
  echo '<td>';
  echo lang('Class', 'class_id');
  echo '</td>';
  echo '<td>';
  echo form_dropdown('class_id', $classes, 'id="class_id"');
  echo '</td>';
  echo '</tr>';

  echo '<tr>';
  echo '<td>';
  echo lang('Character name', 'name');
  echo '</td>';
  echo '<td>';
  echo form_input(array('name' => 'name','value' => set_value('name'), 'id' => 'name', 'class' => 'text' ));
  echo '</td>';
  echo '</tr>';

  echo '<tr>';
  echo '<td colspan="2">';
  echo form_submit('submit', lang('Submit'));
  echo '</td>';
  echo '</tr>';

  echo '</table>';
  echo form_close();
?>