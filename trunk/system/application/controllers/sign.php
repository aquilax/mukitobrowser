<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sign
 *
 * @author aquilax
 */
require APPPATH.'libraries/IN_Controller.php';

class Sign extends IN_Controller{

  function  __construct() {
    parent::__construct();
  }

  function index(){
    $this->load->model('sign_model');
    $this->data['sign'] = $this->sign_model->load();
    $this->render();
  }
}
?>
