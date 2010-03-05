<?php
  echo validation_errors('<div class="error">', '</div>');

  echo form_open('user/register');
  echo '<table width="80%">';
  echo '<tr>';
  echo '<td>';
  echo lang('Username', 'username');
  echo '</td>';
  echo '<td>';
  echo form_input(array('name' => 'username','value' => set_value('username'), 'id' => 'username', 'class' => 'text' ));
  echo '<br />';
  echo lang('Usernames must be 30 alphanumeric characters or less.');
  echo '</td>';
  echo '</tr>';

  echo '<tr>';
  echo '<td>';
  echo lang('Password', 'password1');
  echo '</td>';
  echo '<td>';
  echo form_password(array('name' => 'password1', 'id' => 'password1', 'class' => 'text' ));
  echo '</td>';
  echo '</tr>';

  echo '<tr>';
  echo '<td>';
  echo lang('Verify Password', 'password2');
  echo '</td>';
  echo '<td>';
  echo form_password(array('name' => 'password2', 'id' => 'password2', 'class' => 'text' ));
  echo '</td>';
  echo '</tr>';

  echo '<tr>';
  echo '<td>';
  echo lang('Email Address', 'email1');
  echo '</td>';
  echo '<td>';
  echo form_input(array('name' => 'email1','value' => set_value('email1'), 'id' => 'email1', 'class' => 'text' ));
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