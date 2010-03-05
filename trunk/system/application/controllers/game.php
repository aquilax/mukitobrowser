<?php
/**
 * Description of game
 *
 * @author aquilax
 */

require APPPATH.'libraries/BASE_Controller.php';

class Game extends BASE_Controller{
  
  function index(){
    $this->load->model('character_model', 'char');
    $this->char->load($this->cid);
    echo 'Hi '.$this->char->get('name');
    echo ' Dispatcher here';
  }
}
?>
