<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Configuration_model extends CI_Model
{

  public function getAll() {
    $this->db->select('config.*')
    ->order_by('id', 'ASC')
    ->from('config');
    $query = $this->db->get();
    return $query->result();
  }

  public function updateById($id, $data) {
    $this->db->select('config.*');
    $this->db->where('config.id', $id);
    $this->db->from('config');
    return $this->db->update('config', $data);
  }

  public function get($array) {
    $this->db->select('config.*');
    $this->db->where($array);
    $this->db->from('config');
    $query = $this->db->get();
    return $query->row();
  }
}
