<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function login($param)
	{
		$admin = $this->config->item( 'cms' );
		if ($admin['user'] == $param['cid'] and $admin['pass'] == $param['passwd']) {
			return true;
		} else {
			return false;
		}
	}
}
