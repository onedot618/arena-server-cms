<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	public function index()
	{
		if (!$this->session->userdata('admin')) {
			$this->load->view('login_v');
		} else {
			redirect('user');
		}
	}

	public function logindo()
	{
		$this->load->model('login_m');
		$login = $this->login_m->login($this->input->post());
		if ($login) {
			$this->session->set_userdata('admin', $this->input->post('cid'));
		}
		echo json_encode(array('login' => $login));
	}

	public function logoutdo()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
