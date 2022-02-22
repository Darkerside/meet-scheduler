<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Roles_model extends CI_Model
{
  public function getAll($filter)
  {
    if ($filter == '') {
      $this->db->select('roles.*')
      ->order_by('id', 'ASC')
      ->from('roles');
      $query = $this->db->get();
      return $query->result();
    } else {
      $this->db->select('roles.*')
      ->where($filter)
      ->order_by('id', 'ASC')
      ->from('roles');
      $query = $this->db->get();
      return $query->result();
    }
  }
}
