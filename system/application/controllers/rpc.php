<?php
/**
 * Description of rpc
 *
 * @author aquilax
 */

require APPPATH.'libraries/OUT_Controller.php';

class Rpc extends BASE_Controller{

  function Rpc(){
    parent::__construct();
    $this->output->enable_profiler(FALSE);
    if (!$this->logged){
      echo json_encode(array('r'=> 1));
      return;
    }
    $this->load->model('character_model', 'char');
    if (!$this->char->load($this->cid)){
      echo json_encode(array('r'=> 1));
      return;
    }
  }

  function move(){
    $this->output->set_header("Content-type: application/json");
    if ($this->char->get('state') != 1) {
      echo json_encode(array('r'=> site_url('fight')));
      return;
    }
    $this->load->model('map_model');
    $redirect = $this->map_model->move($_POST);
    if ($redirect['c'] == 'game/explore'){
      $mid = $this->char->get('map_id');
      $x = $this->char->get('xpos');
      $y = $this->char->get('ypos');
      $map = $this->map_model->getMap($x, $y, $mid);
      $data = array(
        'x' => $x,
        'y' => $y,
      );
      $res = array('map' => $map, 'coord' =>$data);
      echo json_encode($res);
      return;
    } else {
      echo json_encode(array('r'=> site_url($redirect['c'])));
      return;
    }
  }
}
?>
