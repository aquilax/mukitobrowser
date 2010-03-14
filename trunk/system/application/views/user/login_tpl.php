<?php
  echo validation_errors();
  echo form_open('user/login');
?>
    <table id="login">
      <tr>
        <td><?php echo lang('User Name', 'userf')?></td>
        <td><?php echo form_input('username', set_value('username'), 'id="userf"')?></td>
      </tr>
      <tr>
        <td><?php echo lang('Password', 'passf')?></td>
        <td><?php echo form_password('password', '', 'id="passf"')?></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo form_submit('login', lang('Login'), 'id="lbtn"').' '.anchor('user/register', lang('Register here...'));?></td>
      </tr>
    </table>
<?php
  echo form_close();
?>