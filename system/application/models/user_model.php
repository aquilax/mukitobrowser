<?php
/**
 * Description of user_model
 *
 * @author aquilax
 */
class User_model extends Model{

  function login($post){
    $this->db->where('username', $post['username']);
    $this->db->where('password', $post['password']);
    $query = $this->db->get('user', 1);
    $row = $query->row_array();
    if($row){
      $data = array(
        'uid'  => $row['id'],
        'user'  => $row['username'],
        'status' => $row['status'],
      );
      $this->session->set_userdata($data);
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function register($post){
    $data = array(
      'username' => $post['username'],
      'password' => $post['password1'],
      'email' => $post['email1'],
    );
    $this->db->insert('user', $data);
    return TRUE;
  }

  function userTaken($user){
    $this->db->where('username', $user);
    $query = $this->db->get('user');
    return $query->num_rows() != 0;
  }

  function charTaken($charname){
    $this->db->where('name', $charname);
    $query = $this->db->get('character');
    return $query->num_rows() != 0;
  }

  function logged(){
    return $this->session->userdata('uid') != false;
  }

  function isAdmin(){
    return $this->session->userdata('status') == 100;
  }

  function getId(){
    $id = $this->session->userdata('uid');
    if ($id){
      return $id;
    } else {
      return -1000;
    }
  }

  function getCid(){
    $id = $this->session->userdata('cid');
    if ($id){
      return $id;
    } else {
      return 0;
    }
  }

  function logout(){
    $array_items = array('uid' => '', 'email' => '', 'status' => '');
    $this->session->unset_userdata($array_items);
  }

  function getCharClasses(){
    $this->db->where('parent_id', 0);
    $query = $this->db->get('character_class');
    return make_assoc($query->result_array(), 'id', 'name');
  }

  function create_character($post, $uid){
    //TODO: check for valid class_id
    $this->db->where('class_id', $post['class_id']);
    //This enables only the basic classes
    $this->db->where('user_id', 0);
    $query = $this->db->get('character', 1);
    $data = $query->row_array();
    if ($data){
      unset($data['id']);
      $data['name'] = $post['name'];
      $data['uid'] = $uid;
      $this->db->insert('character', $data);
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function getCharactes($uid){
    $select = array(
      'c.id',
      'c.name',
      'c.level',
      'cc.name as classname',
    );
    $this->db->select($select);
    $this->db->where('user_id', $uid);
    $this->db->join('character_class cc', 'cc.id = c.class_id');
    $this->db->order_by('created');
    $query = $this->db->get('character c');
    return $query->result_array();
  }

  function setCharacter($cid, $uid){
    $this->db->where('id', $cid);
    $this->db->where('user_id', $uid);
    $query = $this->db->get('character', 1);
    if ($query->num_rows() == 1){
      $this->session->set_userdata(array('cid' => $cid));
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
?>
