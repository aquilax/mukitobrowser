<?php
/**
 * Description of game
 *
 * @author aquilax
 */

require APPPATH.'libraries/BASE_Controller.php';

class Game extends BASE_Controller{
  
  function index(){
    if (!$this->logged){
      redirect('');
    }
    $this->load->model('character_model', 'char');
    $this->char->load($this->cid);
    if ($this->char->get('state') == 1){
      redirect('map');
    }
    echo 'Hi '.$this->char->get('name');
    echo ' Dispatcher here';
  }
}
?>
