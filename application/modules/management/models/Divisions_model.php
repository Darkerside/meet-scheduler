<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Divisions_model extends CI_Model
{
  private $table = 'divisions';

  public function getAll($filter) {
    if ($filter == '') {
      $this->db->select('divisions.*')
      ->order_by('id', 'ASC')
      ->from('divisions');
      $query = $this->db->get();
      return $query->result();
    } else {
      $this->db->select('divisions.*')
      ->where($filter)
      ->order_by('id', 'ASC')
      ->from('divisions');
      $query = $this->db->get();
      return $query->result();
    }
  }

  public function add($data)
  {
    return $this->db->insert($this->table, $data);
  }

  public function getById($id) {
    $this->db->select('divisions.*')
    ->where('divisions.id', $id)
    ->from('divisions');
    $query = $this->db->get();
    return $query->row();
  }

  public function updateById($id, $data) {
    $this->db->select('divisions.*');
    $this->db->where('divisions.id', $id);
    $this->db->from('divisions');
    return $this->db->update('divisions', $data);
  }

  public function deleteById($id)
  {
    $this->db->where('id', $id);
    return $this->db->delete('divisions');
  }
}
