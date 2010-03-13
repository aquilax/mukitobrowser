<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inventory
 *
 * @author aquilax
 */
require APPPATH.'libraries/IN_Controller.php';

class Inventory extends IN_Controller{

  function  __construct() {
    parent::__construct();
    $this->load->model('inventory_model', 'inv');
  }

  function index(){
    //$this->inv->add(1);
    $this->data['data'] = $this->inv->separateByPlace($this->inv->load(array($this->inv->equipped, $this->inv->bag)));
    $this->render();
  }

  function drop(){
    $inid = $this->uri->segment(3);
    if (is_numeric($inid)){
      $this->inv->drop($inid);
    }
    redirect('inventory');
  }

  function equip(){
    $inid = $this->uri->segment(3);
    if (is_numeric($inid)){
      $this->inv->equip($inid);
    }
    redirect('inventory');
  }
}
?>
