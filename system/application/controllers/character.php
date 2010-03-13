<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of character
 *
 * @author aquilax
 */
require APPPATH.'libraries/IN_Controller.php';

class Character extends IN_Controller{

  function  __construct() {
    parent::__construct();
  }

  function points(){
    if ($this->char->get('points') < 1){
      redirect('game');
    }
    if ($_POST && $_POST['distribute']){
      $this->char->distributePoints($_POST);
    }
    $points = $this->char->get('points');
    $this->data['points'] = $points;
    $this->data['stats'] = $this->char->distribution();
    $this->load->helper('form');
    $this->render();
  }
}
?>
