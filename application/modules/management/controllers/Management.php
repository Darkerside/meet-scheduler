<?php
class Management extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      redirect(base_url('auth'));
    }
  }

  public function index() {
    redirect(base_url('home'));
  }
}
