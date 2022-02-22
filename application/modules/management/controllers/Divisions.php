<?php
class Divisions extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('Divisions_model');
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
    $this->template->add_js('application/modules/management/assets/js/divisions.js');

    $this->template->add_css('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css');
    $this->template->add_css('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css');
    $this->template->add_css('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css');

    $divisionsTable = $this->Divisions_model->getAll('');
    foreach ($divisionsTable as &$row) {
      if ($row->is_active == 1) $row->is_active = 'Active';
      else $row->is_active = 'Non-Active';
    }
    $data = array('divisionsTable' => $divisionsTable);
    $data['page'] = "Management Division";
    $data['menu'] = "Management Division";
    $data['user'] = $this->session->userdata();

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'division', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function getAll()
  {
    $divisionsTable = $this->Divisions_model->getAll('');
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $divisionsTable
      )));
  }

  public function getAllActive()
  {
    $divisionsTable = $this->Divisions_model->getAll(array("is_active" => 1));
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $divisionsTable
      )));
  }

  public function add()
  {
    $this->load->helper(array('form', 'url'));

    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('isActive', 'Is Active', 'required');
    $name = htmlspecialchars($this->input->post('name'));
    $active = htmlspecialchars($this->input->post('isActive'));
    $data = [
      'division_name' => $name,
      'is_active' => $active,
    ];

    if ($this->form_validation->run()) {
      $this->Divisions_model->add($data);
      $newTable = $this->getAll(array("divisions.is_active" => 1));
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
    $this->Divisions_model->updateById($id, $data);
    $newTable = $this->getAll(array("divisions.is_active" => 1));
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
    $data = $this->Divisions_model->getById($id);
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => $data
      )));
  }

  public function update($id)
  {
    $this->load->helper(array('form', 'url'));
    
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('isActive', 'Is Active', 'required');
    $name = htmlspecialchars($this->input->post('name'));
    $active = htmlspecialchars($this->input->post('isActive'));
    $data = [
      'division_name' => $name,
      'is_active' => $active,
    ];

    if ($this->form_validation->run()) {
      $this->Divisions_model->updateById($id, $data);
      $newTable = $this->getAll(array("divisions.is_active" => 1));
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
}
