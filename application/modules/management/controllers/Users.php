<?php
class Users extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('Users_model');
  }

  public function index()
  {
    $this->template->add_js('assets/plugins/datatables/jquery.dataTables.min.js');
    $this->template->add_js('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js');
    $this->template->add_js('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js');
    $this->template->add_js('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js');
    $this->template->add_js('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js');
    $this->template->add_js('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js');
    $this->template->add_js('assets/plugins/datatables-buttons/js/buttons.html5.min.js');
    $this->template->add_js('assets/plugins/datatables-buttons/js/buttons.print.min.js');
    $this->template->add_js('assets/plugins/datatables-buttons/js/buttons.colVis.min.js');
    $this->template->add_js('application/modules/management/assets/js/users.js');

    $this->template->add_css('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css');
    $this->template->add_css('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css');
    $this->template->add_css('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css');

    $usersTable = $this->Users_model->getAll('');
    foreach ($usersTable as &$row) {
      if ($row->is_active == 1) $row->is_active = 'Active';
      else $row->is_active = 'Non-Active';
    }
    $data = array('usersTable' => $usersTable);
    $data['page'] = "Management User";
    $data['menu'] = "Management User";
    $data['user'] = $this->session->userdata();

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'user', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function getAll()
  {
    $usersTable = $this->Users_model->getAll('');
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $usersTable
      )));
  }

  public function getAllActive()
  {
    $usersTable = $this->Users_model->getAll(array('users.is_active' => 1));
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $usersTable
      )));
  }

  public function add()
  {
    $this->load->helper(array('form', 'url'));
    
    $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]');
    $this->form_validation->set_rules('full_name', 'Full Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required');
    $this->form_validation->set_rules('roleId', 'Role Id', 'required');
    $this->form_validation->set_rules('divisionId', 'Division Id', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('retype', 'Retype Password', 'required|matches[password]');
    $this->form_validation->set_rules('isActive', 'Is Active', 'required');

    $user = htmlspecialchars($this->input->post('username'));
    $full_name = htmlspecialchars($this->input->post('full_name'));
    $pass = htmlspecialchars($this->input->post('password'));
    $email = htmlspecialchars($this->input->post('email'));
    $role = htmlspecialchars($this->input->post('roleId'));
    $division = htmlspecialchars($this->input->post('divisionId'));
    $active = htmlspecialchars($this->input->post('isActive'));
    $data = [
      'username' => $user,
      'password' => password_hash($pass, PASSWORD_DEFAULT),
      'email' => $email,
      'full_name' => $full_name,
      'role_id' => $role,
      'division_id' => $division,
      'is_active' => $active,
    ];

    if ($this->form_validation->run()) {
      $this->Users_model->add($data);
      $newTable = $this->getAll(array("users.is_active" => 1));
      $newTable = json_decode($newTable->final_output);
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'text' => 'Success',
          'data' => $newTable->data
        )));
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
          'text' => 'failed',
          'data' => validation_errors()
        )));
    }
  }

  public function update($id)
  {
    $this->load->helper(array('form', 'url'));
    
    $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]');
    $this->form_validation->set_rules('email', 'Email', 'required');
    $this->form_validation->set_rules('roleId', 'Role Id', 'required');
    $this->form_validation->set_rules('divisionId', 'Division Id', 'required');
    $this->form_validation->set_rules('isActive', 'Is Active', 'required');

    $user = htmlspecialchars($this->input->post('username'));
    $email = htmlspecialchars($this->input->post('email'));
    $full_name = htmlspecialchars($this->input->post('full_name'));
    $role = htmlspecialchars($this->input->post('roleId'));
    $division = htmlspecialchars($this->input->post('divisionId'));
    $active = htmlspecialchars($this->input->post('isActive'));
    $data = [
      'username' => $user,
      'email' => $email,
      'full_name' => $full_name,
      'role_id' => $role,
      'division_id' => $division,
      'is_active' => $active,
    ];

    if ($this->form_validation->run()) {
      $this->Users_model->updateById($id, $data);
      $newTable = $this->getAll(array("users.is_active" => 1));
      $newTable = json_decode($newTable->final_output);
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'text' => 'Success',
          'data' => $newTable->data
        )));
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
          'text' => 'failed',
          'data' => validation_errors()
        )));
    }
  }

  public function delete($id)
  {
    $data = [
      'is_active' => 0,
    ];
    $this->Users_model->updateById($id, $data);
    $newTable = $this->getAll(array("users.is_active" => 1));
    $newTable = json_decode($newTable->final_output);
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $newTable->data
      )));
  }

  public function get($id)
  {
    $data = $this->Users_model->getById($id);
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $data
      )));
  }

  public function change_password()
  {
    $this->load->helper(array('form', 'url'));
    
    $this->form_validation->set_rules('oldPassword', 'Old Password', 'required');
    $this->form_validation->set_rules('newPassword', 'New Password', 'required');
    $this->form_validation->set_rules('retypePassword', 'Retype Password', 'required|matches[newPassword]');

    $old_password = base64_decode(htmlspecialchars($this->input->post('oldPassword')));
    $new_password = base64_decode(htmlspecialchars($this->input->post('newPassword')));

    $data = [
      'password' => password_hash($new_password, PASSWORD_DEFAULT),
    ];

    if ($this->form_validation->run()) {
      $user = $this->db->get_where('users', ['id' => $this->session->userdata('id')])->row_array();
      if (password_verify($old_password, $user['password'])) {
        $this->Users_model->updateById($user['id'], $data);
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'text' => 'Success',
          'data' => 'Password change Successfully!'
        )));
      } else {
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
          'text' => 'Failed',
          'data' => 'Old Password is Wrong!'
        )));
      }
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
          'text' => 'failed',
          'data' => validation_errors()
        )));
    }
  }
}
