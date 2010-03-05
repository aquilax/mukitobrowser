<?php
$config = array(
  'user/login' => array(
    array(
      'field' => 'username',
      'label' => 'User Name',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'password',
      'label' => 'Password',
      'rules' => 'trim|required|md5'
    )
  ),
  'user/register' => array(
    array(
      'field' => 'username',
      'label' => 'Username',
      'rules' => 'trim|required|callback__username_check'
    ),
    array(
      'field' => 'password1',
      'label' => 'Password',
      'rules' => 'trim|required|md5'
    ),
    array(
      'field' => 'password2',
      'label' => 'Verify Password',
      'rules' => 'trim|required|matches[password1]'
    ),
    array(
      'field' => 'email1',
      'label' => 'Email Address',
      'rules' => 'trim|required|valid_email'
    ),
  ),
  'user/create_character' => array(
    array(
      'field' => 'class_id',
      'label' => 'Class',
      'rules' => 'trim|required|is_natural_no_zero'
    ),
    array(
      'field' => 'name',
      'label' => 'Character name',
      'rules' => 'trim|required|callback__charname_check'
    )
  ),
)
?>