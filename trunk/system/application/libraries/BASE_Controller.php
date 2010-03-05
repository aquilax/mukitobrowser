<?php
/**
 * Description of BASE_Controller
 *
 * @author aquilax
 */
class BASE_Controller extends Controller{
  protected $data = array();
  protected $logged;
  protected $uid;

  public function __construct() {
    parent::__construct();
    $this->load_defaults();
  }

  protected function load_defaults() {
    $this->logged = $this->user_model->logged();
    $this->uid = $this->user_model->getId();
    $this->cid = $this->user_model->getCid();
  }
}
?>
