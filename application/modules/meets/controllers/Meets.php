<?php
class Meets extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('Meets_model');
    $this->load->model('management/Members_model');
    $this->load->model('management/Divisions_model');
    $this->load->model('management/Users_model');
  }

  public function index()
  {
    $meetList = $this->Meets_model->getByUserId($this->session->userdata('id'));
    $data['userMeets'] = $meetList;
    $data['divisionMeets'] = false;

    $data['page'] = "Meet List";
    $data['menu'] = "Meets";
    $data['user'] = $this->session->userdata();

    $this->template->add_js('application/modules/meets/assets/js/scripts.js');

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'index', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function division()
  {

    $meetList = $this->Meets_model->getByDivisionId($this->session->userdata('division_id'));
    foreach ($meetList as $meet) {
      $meet->meet_id = $meet->id;
    };
    $data['userMeets'] = false;
    $data['divisionMeets'] = $meetList;

    $this->template->add_js('assets/plugins/summernote/summernote-bs4.min.js');
    $this->template->add_js('application/modules/meets/assets/js/scripts.js');

    $this->template->add_css('assets/plugins/summernote/summernote-bs4.min.css');

    $data['page'] = "Division Meets List";
    $data['menu'] = "Division Meets";
    $data['user'] = $this->session->userdata();

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'index', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function new()
  {
    $this->template->add_js('assets/plugins/summernote/summernote-bs4.min.js');
    $this->template->add_js('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js');
    $this->template->add_js('assets/plugins/select2/js/select2.full.min.js');
    $this->template->add_js('application/modules/meets/assets/js/create.js');

    $this->template->add_css('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css');
    $this->template->add_css('assets/plugins/select2/css/select2.min.css');
    $this->template->add_css('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css');
    $this->template->add_css('assets/plugins/summernote/summernote-bs4.min.css');

    $data['page'] = "Create New Meet";
    $data['menu'] = "Meets";
    $data['user'] = $this->session->userdata();
    $data['internalTable'] = array();
    $data['externalTable'] = array();

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'create', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function edit($id)
  {
    $filter = array('meet_id' => $id);
    $meet = $this->Meets_model->getById($id);
    $extUsers = $meet->ext_users;
    $createdBy = $meet->created_by;
    $meet->ext_users = new \stdClass;
    $meet->users = new \stdClass;
    $meet->created_by = new \stdClass;
    $meet->ext_users = json_decode($extUsers);
    $meet->users = $this->Members_model->getAll($filter);
    $meet->created_by = $this->Users_model->getById($createdBy);
    $meetDate = $this->checkTime($meet->timedate);
    $editPermission = $this->checkPermission($meet->created_by->id);


    $data['page'] = "Edit Meet";
    $data['menu'] = "Meets";
    $data['user'] = $this->session->userdata();
    $data['meet'] = $meet;
    $data['division_dropdown'] = $this->Divisions_model->getAll(array('is_active' => 1));
    $data['users'] = $this->Users_model->getAll(array('users.is_active' => 1));

    if ($editPermission && $meetDate) {
      $this->template->add_js('assets/plugins/summernote/summernote-bs4.min.js');
      $this->template->add_js('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js');
      $this->template->add_js('assets/plugins/select2/js/select2.full.min.js');
      $this->template->add_js('application/modules/meets/assets/js/edit.js');

      $this->template->add_css('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css');
      $this->template->add_css('assets/plugins/select2/css/select2.min.css');
      $this->template->add_css('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css');
      $this->template->add_css('assets/plugins/summernote/summernote-bs4.min.css');

      $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
      $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
      $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
      $this->template->write_view('content', 'edit', '', TRUE);
      $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
      $this->template->render();
    } else {
      $this->template->add_js('application/modules/meets/assets/js/error.js');
      $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
      $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
      $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
      $this->template->write_view('content', 'error', '', TRUE);
      $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
      $this->template->render();
    }
  }

  public function get($id)
  {
    $filter = array('meet_id' => $id);
    $data = $this->Meets_model->getById($id);
    $extUsers = $data->ext_users;
    $createdBy = $data->created_by;
    $data->ext_users = new \stdClass;
    $data->users = new \stdClass;
    $data->created_by = new \stdClass;
    $data->ext_users = json_decode($extUsers);
    $data->users = $this->Members_model->getAll($filter);
    $data->created_by = $this->Users_model->getById($createdBy);
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $data
      )));
  }

  public function getByUser($id)
  {
    $data = $this->Meets_model->getByUserId($id);
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $data
      )));
  }

  public function add()
  {
    $this->load->helper(array('form', 'url'));

    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('body', 'Body', 'required');
    $this->form_validation->set_rules('url', 'URL', 'required');
    $this->form_validation->set_rules('division_id', 'Division', 'required');
    $this->form_validation->set_rules('users', 'Users', 'required');
    $this->form_validation->set_rules('ext_users', 'External Users', 'required');
    $this->form_validation->set_rules('timedate', 'Time Date', 'required');
    $title = $this->input->post('title');
    $body = $this->input->post('body');
    $url = $this->input->post('url');
    $division_id = $this->input->post('division_id');
    $users = $this->input->post('users');
    $ext_users = $this->input->post('ext_users');
    $inputdate = $this->input->post('timedate');

    $timedate = DateTime::createFromFormat('m/d/Y h:i A', $inputdate)->format('Y-m-d H:i:s');
    $data = [
      "title" => $title,
      "body" => $body,
      "url" => $url,
      "division_id" => $division_id,
      "ext_users" => $ext_users,
      "timedate" => $timedate,
      "created_by" => $this->session->userdata('id'),
    ];

    if ($this->form_validation->run()) {
      $this->Meets_model->add($data);
      $newMeetId = $this->db->insert_id();

      $users = json_decode($users);

      foreach ($users as $user) {
        $item = [
          "user_id" => $user,
          "meet_id" => $newMeetId,
        ];

        $this->Members_model->add($item);
      }

      $data = new \stdClass;

      $filter = array('meet_id' => $newMeetId);
      $data = $this->Meets_model->getById($newMeetId);
      $data->id = $newMeetId;
      $extUsers = $data->ext_users;
      $createdBy = $data->created_by;
      $data->ext_users = new \stdClass;
      $data->users = new \stdClass;
      $data->created_by = new \stdClass;
      $data->ext_users = json_decode($extUsers);
      $data->users = $this->Members_model->getAll($filter);
      $data->created_by = $this->Users_model->getById($createdBy);
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'text' => 'Success',
          'data' => $data
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

    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('body', 'Body', 'required');
    $this->form_validation->set_rules('url', 'URL', 'required');
    $this->form_validation->set_rules('division_id', 'Division', 'required');
    $this->form_validation->set_rules('users', 'Users', 'required');
    $this->form_validation->set_rules('ext_users', 'External Users', 'required');
    $this->form_validation->set_rules('timedate', 'Time Date', 'required');
    $title = $this->input->post('title');
    $body = $this->input->post('body');
    $url = $this->input->post('url');
    $division_id = $this->input->post('division_id');
    $users = $this->input->post('users');
    $ext_users = $this->input->post('ext_users');
    $inputdate = $this->input->post('timedate');

    $timedate = DateTime::createFromFormat('m/d/Y h:i A', $inputdate)->format('Y-m-d H:i:s');
    $data = [
      "title" => $title,
      "body" => $body,
      "url" => $url,
      "division_id" => $division_id,
      "ext_users" => $ext_users,
      "timedate" => $timedate,
    ];

    if ($this->form_validation->run()) {
      $this->Meets_model->update($id, $data);
      $this->Members_model->deleteByFilter(array('meet_id' => $id));

      $filter = array('meet_id' => $id);
      $users = json_decode($users);

      foreach ($users as $user) {
        $item = [
          "user_id" => $user,
          "meet_id" => $id,
        ];

        $this->Members_model->add($item);
      }

      $data = new \stdClass;

      $data = $this->Meets_model->getById($id);
      $data->id = $id;
      $extUsers = $data->ext_users;
      $createdBy = $data->created_by;
      $data->ext_users = new \stdClass;
      $data->users = new \stdClass;
      $data->created_by = new \stdClass;
      $data->ext_users = json_decode($extUsers);
      $data->users = $this->Members_model->getAll($filter);
      $data->created_by = $this->Users_model->getById($createdBy);
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'text' => 'Success',
          'data' => $data
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

  private function checkTime($time)
  {
    $nowTime =  time();
    $meetTime = DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('U');
    return $meetTime > $nowTime;
  }

  private function checkPermission($data)
  {
    return $data == $this->session->userdata('id') || $this->session->userdata('role') == 'Admin' || $this->session->userdata('role') == 'Division Lead';
  }
}
