<?php
/**
 * Description of home
 *
 * @author aquilax
 */

require APPPATH.'libraries/OUT_Controller.php';

class Home extends OUT_Controller{

  function index(){
    $this->render('out');
  }

  function start(){
    $this->data['characters'] = $this->user_model->getCharactes($this->uid);
    $this->render('out');
  }
}
?>
