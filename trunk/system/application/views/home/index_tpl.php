Welcome to MuKiTo
<?php
  echo '<br/>';
  if (!$logged){
    echo anchor('user/login', 'Login');
    echo '<br/>';
    echo anchor('user/register', 'Register');
    echo '<br/>';
  } else {
    echo anchor('user/create_character', 'Create Character');
    echo '<br/>';
    echo anchor('home/start', 'Start Game');
    echo '<br/>';
    echo anchor('user/logout', 'Logout');
  }
  
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
