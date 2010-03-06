<?php
/**
 * Description of npc
 *
 * @author aquilax
 */
class Npc extends PAGE_Controller{

  function index(){
    $this->load->model('npc_model');
    $this->data['npc'] = $this->npc_model->load();
    if ($this->data['npc']){
      $this->render();
    } else {
      redirect('map');
    }
  }
}
?>
