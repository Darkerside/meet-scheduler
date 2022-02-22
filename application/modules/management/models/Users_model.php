<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Users_model extends CI_Model
{
  private $table = 'users';

  public function getAll($filter) {
    if ($filter == '') {
      $this->db->select('users.*, roles.role_name, divisions.division_name')
      ->join('roles', 'roles.id = users.role_id', 'left')
      ->join('divisions', 'divisions.id = users.division_id', 'left')
      ->order_by('id', 'ASC')
      ->from('users');
      $query = $this->db->get();
      return $query->result();
    } else {
      $this->db->select('users.*, roles.role_name, divisions.division_name')
      ->join('roles', 'roles.id = users.role_id', 'left')
      ->join('divisions', 'divisions.id = users.division_id', 'left')
      ->where($filter)
      ->order_by('id', 'ASC')
      ->from('users');
      $query = $this->db->get();
      return $query->result();
    }
  }

  public function add($data)
  {
    return $this->db->insert($this->table, $data);
  }

  public function login()
  {
    $user = htmlspecialchars($this->input->post('username'));
    $pass = htmlspecialchars($this->input->post('password'));
    $data = [
      'username' => $user,
      'password' => password_hash($pass, PASSWORD_DEFAULT),
      'is_active' => 1,
      'created_at' => time()
    ];
    return $this->db->insert($this->table, $data);
  }

  public function getById($id) {
    $this->db->select('users.*, roles.role_name');
    $this->db->where('users.id', $id);
    $this->db->join('roles', 'roles.id = users.role_id', 'left');
    $this->db->from('users');
    $query = $this->db->get();
    return $query->row();
  }

  public function updateById($id, $data) {
    $this->db->select('users.*');
    $this->db->where('users.id', $id);
    $this->db->from('users');
    return $this->db->update('users', $data);
  }

  public function deleteById($id)
  {
    $this->db->where('id', $id);
    return $this->db->delete('users');
  }
}
