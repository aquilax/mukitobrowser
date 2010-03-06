<?php
/**
 * Description of home
 *
 * @author aquilax
 */

require APPPATH.'libraries/OUT_Controller.php';

class User extends OUT_Controller{

  function index(){
    $this->render('out');
  }

  function login(){
    if ($this->logged){
      redirect('');
    }
    $this->class_name = 'user';
    $this->action = 'login';
    $this->data['title'] = lang('Login');
    $this->load->library('form_validation');
		if ($this->form_validation->run() == FALSE){
      $this->render('out');
		} else {
      if ($this->user_model->login($_POST)){
        redirect('home');
      } else {
        $this->form_validation->_error_array[] = lang('Bad User Name or Password');
        $this->render('out');
      }
		}
  }

  function register(){
    if ($this->logged){
      redirect('');
    }
    $this->data['title'] = lang('Register');
    $this->load->library('form_validation');
    $rules['username']	= "callback__username_check";

    if ($this->form_validation->run() == FALSE){
      $this->render('out');
		} else {
      if ($this->user_model->register($_POST)){
        redirect('user/register_success');
      } else {
        $this->render('out');
      }
		}
  }

  function changepassword(){
    echo 'NOT IMPLEMENTED YET';
  }

  function logout(){
    $this->user_model->logout();
    redirect('');
  }

  function _username_check($user){
    if ($this->user_model->userTaken($user)){
      $this->form_validation->set_message('_username_check', sprintf(lang('The %s username is already registered'), $user));
      return FALSE;
    }
    return TRUE;
  }

  function _charname_check($charname){
    if ($this->user_model->charTaken($charname)){
      $this->form_validation->set_message('_charname_check', sprintf(lang('The %s character name is already taken'), $charname));
      return FALSE;
    }
    return TRUE;
  }

  function register_success(){
    $this->data['title'] = lang('Successful registration');
    $page = lang('Your account was created succesfully.');
    $page .= '<br /><br />';
    $page .= sprintf(lang('You may now go to the %s and continue playing %s!'), anchor('user/login', lang('Login Page')), $this->config->item('gamename'));
    $this->data['page'] = $page;
    $this->action = 'page';
    $this->render('out');
  }

  function create_character(){
    if (!$this->logged){
      redirect('user/login');
    }
    $this->data['title'] = lang('Create character');
    $this->load->library('form_validation');
    $this->data['classes'] = $this->user_model->getCharClasses();
    $rules['username']	= "callback__charname_check";
    if ($this->form_validation->run() == FALSE){
      $this->render('out');
		} else {
      if ($this->user_model->create_character($_POST, $this->uid)){
        redirect('user/create_success');
      } else {
        $this->render('out');
      }
		}
  }

  function create_success(){
    $this->data['title'] = lang('Charatcter created');
    $page = lang('Your character was created succesfully.');
    $page .= '<br /><br />';
    $page .= sprintf(lang('You may now continue playing %s!'), $this->gamename);
    $this->data['page'] = $page;
    $this->action = 'page';
    $this->render('out');
  }

}
?>
