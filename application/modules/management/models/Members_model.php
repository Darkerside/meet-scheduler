<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Members_model extends CI_Model
{
  public function getAll($filter)
  {
    if ($filter == '') {
      $this->db->select('members.*');
      $this->db->order_by('id', 'ASC');
      $this->db->from('members');
      $query = $this->db->get();
      return $query->result();
    } else {
      $this->db->select('members.*, users.username, users.email, users.full_name, divisions.division_name')
        ->join('users', 'users.id = members.user_id', 'left')
        ->join('divisions', 'divisions.id = users.division_id', 'left')
        ->where($filter)
        ->order_by('members.id', 'ASC')
        ->from('members');
      $query = $this->db->get();
      return $query->result();
    }
  }

  public function add($data)
  {
    return $this->db->insert('members', $data);
  }

  public function deleteByFilter($data)
  {
    $this->db->where($data);
    return $this->db->delete('members');
  }
}
