<?php
class Roles extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('Roles_model');
  }

  public function getAll()
  {
    $rolesTable = $this->Roles_model->getAll('');
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $rolesTable
      )));
  }

  public function getAllActive()
  {
    $rolesTable = $this->Roles_model->getAll(array('is_active' => 1));
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $rolesTable
      )));
  }
}
