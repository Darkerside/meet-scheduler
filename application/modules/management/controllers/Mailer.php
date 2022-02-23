<?php
class Mailer extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('Meets/Meets_model');
    $this->load->model('Configuration_model');
    $this->load->model('Members_model');
  }

  public function index()
  {
    redirect(base_url('home'));
  }

  public function send()
  {
    $id = $this->input->post('id');
    $configTable = $this->Configuration_model->getAll();

    $email = $this->Configuration_model->get(array("variable" => "Email"));
    $password = $this->Configuration_model->get(array("variable" => "Password"));
    $subject = $this->Configuration_model->get(array("variable" => "Subject"));
    $name = $this->Configuration_model->get(array("variable" => "Name"));
    $mail_type = $this->Configuration_model->get(array("variable" => "Mail Type"));
    $charset = $this->Configuration_model->get(array("variable" => "Charset"));
    $protocol = $this->Configuration_model->get(array("variable" => "Protocol"));
    $host = $this->Configuration_model->get(array("variable" => "Server URI"));
    $port = $this->Configuration_model->get(array("variable" => "Server Port"));

    $jwt = $password->value;
    $decode = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[1]))));
    $decodePassword =  $decode->password;

    $config = array($email, $password, $subject, $name);

    $filter = array('meet_id' => $id);
    $data = $this->Meets_model->getById($id);
    $data->users = new \stdClass;

    $extUsers = $data->ext_users;
    $data->ext_users = new \stdClass;
    $data->ext_users = json_decode($extUsers);

    $data->users = $this->Members_model->getAll($filter);

    $config = [
      'mailtype'  => $mail_type,
      'charset'   => $charset,
      'protocol'  => $protocol,
      'smtp_host' => $host,
      'smtp_user' => $email->value,  // Email gmail
      'smtp_pass'   => $decodePassword,  // Password gmail
      'smtp_port'   => intval($port),
      // 'crlf'    => "\r\n",
      // 'newline' => "\r\n"
    ];

    // Load library email dan konfigurasinya
    $this->load->library('email', $config);

    // Email dan nama pengirim
    $this->email->from($email->value, $name->value);

    // Subject email
    $this->email->subject($subject->value);

    // Isi email
    $body = $this->messageBody($data);
    $this->email->message($body);

    foreach ($data->users as $user) {
      $this->email->to($user->email);
      // Tampilkan pesan sukses atau error
      $this->email->send();
    }

    if (!empty($data->ext_users)) {
      foreach ($data->ext_users as $user) {
        $this->email->to($user->email);
        // Tampilkan pesan sukses atau error
        $this->email->send();
      }
    }

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'text' => 'Success',
        'data' => null
      )));
  }

  private function messageBody($data)
  {
    $body = <<<EOL
Hi,<br/>
You are listed on a Meeting as Participant, here is the Meeting detail:<br/>
Title: $data->title <br/>
Time: $data->timedate <br/>
Url: $data->url <br/>
Content: $data->body <br/>
We hope you can attend this meeting.<br/>
Thanks<br/>
<br/>
Reminder Bot.
EOL;
return $body;
  }
}
