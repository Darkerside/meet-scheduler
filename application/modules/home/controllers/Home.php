<?php
class Home extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
    $this->load->model('management/Divisions_model');
    $this->load->model('management/Users_model');
    $this->load->model('Meets/Meets_model');
  }

  public function index()
  {
    $meetList = $this->Meets_model->getByUserId($this->session->userdata('id'));

    $data['dashboard'] = array(
      'meetList' => count($meetList)
    );

    $data['user'] = $this->session->userdata();
    $data['page'] = "Home";
    $data['menu'] = "Home";
    $data['userMeets'] = $meetList;

    if ($this->session->userdata('role') == 'Admin') {
      date_default_timezone_set('Asia/Jakarta');
      $totalUsers = $this->Users_model->getAll('');
      
      $divisionTable = $this->Divisions_model->getAll('');

      $filter = array('meets.timedate > ' => date('Y-m-d H:i:s'));
      $totalMeets = $this->Meets_model->getAll($filter);
      
      $data['dashboard']['users'] = count($totalUsers);
      $data['dashboard']['divisions'] = count($divisionTable);
      $data['dashboard']['totalMeets'] = count($totalMeets);
    } else if ($this->session->userdata('role') == 'Division Lead') {
      $filter = array('meets.timedate > ' => date('Y-m-d H:i:s'), 'meets.division_id' => $this->session->userdata('division_id'));
      $divisionMeets = $this->Meets_model->getAll($filter);
      $data['dashboard']['divisionMeets'] = count($divisionMeets);
    }

    $this->template->add_js('application/modules/home/assets/js/scripts.js');

    $this->template->write_view('navbar', 'templates/snippets/navbar', $data, TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', '', TRUE);
    $this->template->write_view('breadcrumb', 'templates/snippets/breadcrumb', '', TRUE);
    $this->template->write_view('content', 'home', '', TRUE);
    $this->template->write_view('footer', 'templates/snippets/footer', '', TRUE);
    $this->template->render();
  }

  public function mail()
  {
    // $this->load->library('pdf');
    // $html = $this->load->view('home', [], true);
    // $attachment = $this->pdf->createPDF($html, 'mypdf', false);

    // $config = [
    //   'mailtype'  => 'html',
    //   'charset'   => 'utf-8',
    //   'protocol'  => 'smtp',
    //   'smtp_host' => 'ssl://smtp.googlemail.com',
    //   'smtp_user' => 'riskimar2503@bsi.ac.id',  // Email gmail
    //   'smtp_pass' => 'nasjqlkt1a78b',  // Password gmail
    //   'smtp_port' => 465,
    //   'crlf'    => "\r\n",
    //   'newline' => "\r\n"
    // ];

    // // Load library email dan konfigurasinya
    // $this->load->library('email', $config);

    // // Email dan nama pengirim
    // $this->email->from('no-reply@scheduler.test', 'Scheduler Meet');

    // // Email penerima
    // $this->email->to('riski.mardianto@gmail.com'); // Ganti dengan email tujuan

    // // Lampiran email, isi dengan url/path file
    // // $this->email->attach($attachment);

    // // Subject email
    // $this->email->subject('Your Meeting Schedules Reminder');

    // // Isi email
    // $this->email->message("Ini adalah contoh email yang dikirim menggunakan SMTP Gmail pada CodeIgniter.");

    // // Tampilkan pesan sukses atau error
    // if ($this->email->send()) {
    //   echo 'Sukses! email berhasil dikirim.';
    // } else {
    //   echo 'Error! email tidak dapat dikirim.';
    //   echo $this->email->send();
    // }
  }

  public function mail2()
  {
    // $to       = 'riski.mardianto@gmail.com';
    // $subject  = 'Testing sendmail.exe';
    // $message  = 'Hi, you just received an email using sendmail!';
    // if (mail($to, $subject, $message))
    //   echo "WOOHOO, email sent";
    // else
    //   echo "BUMMER, email failed";


  }
}
