<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Meets_model extends CI_Model
{

  public function getAll($filter)
  {
    if ($filter == '') {
      $this->db->select('meets.*');
      $this->db->order_by('id', 'ASC');
      $this->db->from('meets');
      $query = $this->db->get();
      return $query->result();
    } else {
      $this->db->select('meets.*')
        ->where($filter)
        ->order_by('id', 'ASC')
        ->from('meets');
      $query = $this->db->get();
      return $query->result();
    }
  }

  public function getById($id)
  {
    $this->db->select('meets.*, divisions.division_name, divisions.id')
      ->join('divisions', 'divisions.id = meets.division_id', 'left')
      ->where('meets.id', $id)
      ->from('meets');
    $query = $this->db->get();
    return $query->row();
  }

  public function getByUserId($id)
  {
    date_default_timezone_set('Asia/Jakarta');
    $array = array('meets.timedate > ' => date('Y-m-d H:i:s'), 'members.user_id' => $id);
    $this->db->select('meets.*, members.*, divisions.division_name')
      ->join('meets', 'meets.id = members.meet_id', 'left')
      ->join('divisions', 'divisions.id = meets.division_id', 'left')
      ->where($array)
      ->order_by('meets.timedate', 'ASC')
      ->from('members');
    $query = $this->db->get();
    return $query->result();
  }

  public function getByDivisionId($id)
  {
    date_default_timezone_set('Asia/Jakarta');
    $array = array('meets.timedate > ' => date('Y-m-d H:i:s'), 'meets.division_id' => $id);
    $this->db->select('meets.*, divisions.division_name')
      ->join('divisions', 'divisions.id = meets.division_id', 'left')
      ->where($array)
      ->order_by('meets.timedate', 'ASC')
      ->from('meets');
    $query = $this->db->get();
    return $query->result();
  }

  public function add($data)
  {
    $this->db->insert('meets', $data);
    return $this->db->insert_id();
  }

  public function update($id, $data) {
    $this->db->select('meets.*');
    $this->db->where('meets.id', $id);
    $this->db->from('meets');
    return $this->db->update('meets', $data);
  }

}
