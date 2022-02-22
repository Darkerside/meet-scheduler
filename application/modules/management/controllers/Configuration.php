<?php
class Configuration extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('Configuration_model');
    $this->load->model('management/Users_model');
  }

  public function index()
  {
    $this->template->add_js('application/modules/management/assets/js/config.js');

    $configTable = $this->Configuration_model->getAll();
    $data = array('configTable' => $configTable);
    $data['page'] = "Configuration";
    $data['menu'] = "Configuration";
    $data['user'] = $this->session->userdata();

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'configuration', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function update()
  {
    $this->load->helper(array('form', 'url'));

    $this->form_validation->set_rules('email', 'Email', 'required');
    $this->form_validation->set_rules('password', 'password', 'required');
    $this->form_validation->set_rules('emailName', 'Email Name', 'required');
    $this->form_validation->set_rules('subject', 'Subject', 'required');
    $this->form_validation->set_rules('adminPassword', 'Admin Password', 'required');
    $email = htmlspecialchars($this->input->post('email'));
    $password = base64_decode(htmlspecialchars($this->input->post('password')));
    $email_name = htmlspecialchars($this->input->post('emailName'));
    $subject = htmlspecialchars($this->input->post('subject'));
    $admin_password = base64_decode(htmlspecialchars($this->input->post('adminPassword')));
    
    $jwt_password = $this->createJWT($password);

    $data = new \stdClass;
    $data = array(
      ['variable' => 'Email', 'value' => $email],
      ['variable' => 'Password', 'value' => $jwt_password],
      ['variable' => 'Name', 'value' => $email_name],
      ['variable' => 'Subject', 'value' => $subject],
    );


    if ($this->form_validation->run()) {
      $admin = $this->db->get_where('users', ['id' => $this->session->userdata('id')])->row_array();
      if (password_verify($admin_password, $admin['password'])) {

        $count = 0;
        foreach ($data as $item) {
          $count = $count + 1;
          $this->Configuration_model->updateById($count, $item);
        };

        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'text' => 'Success',
          'data' => 'Success Updating Configuration Values!'
        )));
      }
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
          'text' => 'Failed',
          'data' => 'Admin Password is Wrong!'
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

  private function createJWT($data)
  {
    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    // Create token payload as a JSON string
    $payload = json_encode(['password' => $data]);

    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'awesome-scheduler!', true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
  }
}
