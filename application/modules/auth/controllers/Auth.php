<?php
class Auth extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('management/Users_model');
  }

  public function index()
  {
    $this->load->view('auth/base/header');
    $this->load->view('login');
    $this->load->view('auth/base/footer');
  }

  public function login()
  {
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');

    
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    if ($this->form_validation->run()) {
      $user = $this->db->get_where('users', ['username' => $username])->row_array();
      if ($user) {
        if (password_verify($password, $user['password'])) {
          $role = $this->db->get_where('roles', ['id' => $user['role_id']])->row_array();
          $division = $this->db->get_where('divisions', ['id' => $user['division_id']])->row_array();
  
          // membuat session
          $this->session->set_userdata('id', $user['id']);
          $this->session->set_userdata('username', $user['username']);
          $this->session->set_userdata('role_id', $role['id']);
          $this->session->set_userdata('role', $role['role_name']);
          $this->session->set_userdata('division_id', $division['id']);
          $this->session->set_userdata('division', $division['division_name']);
          $this->session->set_userdata('is_login', TRUE);
  
          // redirect ke admin
          redirect(base_url('home'));
        } else {
  
          // jika password salah
          $this->session->set_flashdata('failed', 'Password salah !');
          redirect(base_url('auth'));
        }
      } else {
        $this->session->set_flashdata('failed', 'Username tidak Tersedia !');
        redirect(base_url('auth'));
      }
    } else {
      if (str_contains(validation_errors(), 'Username')) { $this->session->set_flashdata('username', 'Username harus diisi'); };
      if (str_contains(validation_errors(), 'Password')) { $this->session->set_flashdata('password', 'Password harus diisi'); };
      redirect(base_url('auth'));
    }
    
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect(base_url('auth'));
  }
}
