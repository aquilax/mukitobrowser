<?php
/**
 * Description of AQX_Controller
 *
 * @author aquilax
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require('BASE_Controller.php');

class PAGE_Controller extends BASE_Controller {

  protected $class_name;
  protected $action;
  protected $param;
  protected $logged;
  protected $uid;
  protected $admin;

  public function __construct() {
    parent::__construct();
    $this->load_defaults_page();
    $this->output->enable_profiler(TRUE);
  }

  protected function load_defaults_page() {
    $this->data['title'] = 'Page Title';
    $this->data['heading'] = 'Page Heading';
    $this->data['content'] = '';
    $this->data['js'] = array();

    $sql = "SET time_zone = '+02:00'";
    $this->db->query($sql);

    $this->data['logged'] = $this->logged;
    $this->admin = $this->user_model->isAdmin();
    $this->data['admin'] = $this->admin;
    $this->data['uid'] = $this->user_model->getId();
    $this->data['path'] = array();

    $this->data['path'][''] = lang('Home');

    $this->class_name = strtolower(get_class($this));
    $this->action = $this->uri->segment(2, 'index');
  }

  protected function render($template='main') {
    if (file_exists(APPPATH . 'views/' . $this->class_name . '/' . $this->action . '_tpl.php')) {
      $this->data['content'] .= $this->load->view($this->class_name . '/' . $this->action . '_tpl.php', $this->data, true);
    }
    $this->load->view("layouts/".$template."_tpl.php", $this->data);
  }
}
?>