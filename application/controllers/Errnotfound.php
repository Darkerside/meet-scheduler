<?php
class Errnotfound extends MY_Controller
{

  public function index() {
    // $this->load->view('index');

    /**
     * if you have any js to add for this page
     */
    $this->template->add_js('assets/js/jquery.slim.min.js');
    $this->template->add_js('assets/js/bootstrap.bundle.js');
    /**
     * if you have any css to add for this page
     */

    $data['page'] = "404";

    $this->template->write_view('navbar', 'templates/snippets/navbar-login', '', TRUE);
    $this->template->write_view('sidebar', 'templates/snippets/sidebar', $data, TRUE);
    $this->template->write_view('content', '404/content_404', '', TRUE);
    $this->template->render();
  }
}
