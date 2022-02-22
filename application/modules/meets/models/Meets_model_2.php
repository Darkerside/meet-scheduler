<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Meets_model extends CI_Model
{

  //validasi form, method ini akan mengembailkan data berupa rules validasi form       
  public function rules()
  {
    return [
      [
        'field' => 'noteBody',
        'label' => 'Notes',
        'rules' => 'trim|required',
        'errors' => [
          'required' => 'Field %s tidak boleh kosong',
        ],
      ],
      [
        'field' => 'noteTitle',
        'label' => 'Title',
        'rules' => 'trim|required',
        'errors' => [
          'required' => 'Field %s tidak boleh kosong',
        ],
      ],
    ];
  }

  // public function getAll() {
  //   $this->db->select('notes.*, users.username');
  //   $this->db->join('users', 'users.id = notes.user_id', 'left');
  //   $this->db->order_by('id', 'ASC');
  //   $this->db->from('notes');
  //   $query = $this->db->get();
  //   return $query->result();
  // }

  public function getAll($filter) {
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

  public function getAllFromUser($id) {
    $this->db->select('notes.*, users.username');
    $this->db->where('notes.id', $id);
    $this->db->join('users', 'users.id = notes.user_id', 'left');
    $this->db->order_by('id', 'ASC');
    $this->db->from('notes');
    $query = $this->db->get();
    return $query->result();
  }

  public function getById($id) {
    $this->db->select('meets.*')
    ->where('meets.id', $id)
    ->from('meets');
    $query = $this->db->get();
    return $query->row();
  }

  public function getByUserId($id) {
    date_default_timezone_set('Asia/Jakarta');
    $array = array('meets.timedate > ' => date('Y-m-d H:i:s'), 'meets.users RLIKE ' => '{"id": '.$id.'}');

    $this->db->select('meets.*')
    ->where($array)
    ->from('meets');
    $query = $this->db->get();
    return $query->result();
  }

  public function updateById($id) {
    $title = htmlspecialchars($this->input->post('noteTitle'));
    $notes = htmlspecialchars($this->input->post('noteBody'));
    $data = [
      'note_title' => $title,
      'note_body' => $notes,
    ];
    $this->db->select('notes.*');
    $this->db->where('notes.id', $id);
    $this->db->from('notes');
    return $this->db->update('notes', $data);
  }

  public function add($data)
  {
    $this->db->insert('meets', $data);
    return $this->db->insert_id();
  }

  public function deleteById($id)
  {
    $this->db->where('id', $id);
    return $this->db->delete('notes');
  }
}
