<?php
/**
 * Description of OUT_Controller
 *
 * @author aquilax
 */
require 'PAGE_Controller.php';

class IN_Controller extends PAGE_Controller{

  public function __construct() {
    parent::__construct();

    $this->load->model('character_model', 'char');
    if (!$this->char->load($this->cid)){
      redirect('');
    }
  }
}
?>