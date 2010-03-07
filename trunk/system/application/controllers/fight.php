<?php
/**
 * Description of fight
 *
 * @author aquilax
 */

require APPPATH.'libraries/IN_Controller.php';

class Fight extends IN_Controller{

  var $fid = 0;

  function __construct() {
    parent::__construct();
    if ($this->char->get('state') != 2){
      redirect('game');
    }
    $this->fid = $this->char->get('fight_id');
  }

  function index(){
    $this->load->model('fight_model');
    if (!$this->fight_model->load($this->fid)){
      redirect('game');
    }
    $this->fight_model->doFight($_POST);
    $this->data['players'] = $this->fight_model->players;
    $this->data['monsters'] = $this->fight_model->monsters;
    $this->render();
  }
}
?>
