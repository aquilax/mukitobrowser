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
    $this->load->helper('form');
    $this->fight_model->doFight($_POST);
    if ($this->fight_model->run){
      redirect('game');
    }
    $this->data['players'] = $this->fight_model->players;
    $this->data['monsters'] = $this->fight_model->monsters;
    $mlist = array();
    foreach($this->fight_model->monsters->list as $k => $m){
      if($m->get('state') != $this->fight_model->dead_state){
        $mlist[$m->get('id')] = ($k).' '.$m->get('name');
      }
    }
    $this->data['log'] = $this->fight_model->log;
    $this->data['mlist'] = $mlist;
    $this->data['alist'] = array(0 => lang('Pass'), 1 => lang('Attack'));
    $this->render();
  }

  function victory(){
    $this->load->model('fight_model');
    if (!$this->fight_model->load($this->fid)){
      redirect('game');
    }
    $state = $this->fight_model->get('state');
    if ($state != 2){
      redirect('fight');
    }
    $this->data['fight'] = $this->fight_model->getData();
    $this->data['monsters'] = $this->fight_model->monsters;
    $this->fight_model->victory();
    $this->data['log'] = $this->fight_model->log;
    $this->render();
  }
}
?>
