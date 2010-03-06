<?php
/**
 * Description of map
 *
 * @author aquilax
 */
require APPPATH.'libraries/IN_Controller.php';

class Map extends IN_Controller{

  function  __construct(){
    parent::__construct();
    if ($this->char->get('state') != 1){
      redirect('game');
    }
    $this->load->model('map_model');
  }

  function index(){
    $this->data['js'][] = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js';
    $this->data['js'][] = site_url('js/map.js?1');
    $this->data['js'][] = site_url('js/jquery.boxy.js?1');
    $this->data['title'] = lang('Exploring');
    $x = $this->char->get('xpos');
    $y = $this->char->get('ypos');
    $mid = $this->char->get('map_id');
    $this->data['map'] = $this->map_model->getMap($x, $y, $mid);
    $this->render();
  }
}
?>
