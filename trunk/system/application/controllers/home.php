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
    $cid = $this->uri->segment(3);
    if ($cid){
      if ($this->user_model->setCharacter($cid, $this->uid)){
        redirect('game');
      }
    }
    $this->data['characters'] = $this->user_model->getCharactes($this->uid);
    $this->render('out');
  }
}
?>
