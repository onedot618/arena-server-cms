<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_class {

	var $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		set_exception_handler(array($this, 'exception_handler'));
	}

	public function exception_handler(Exception $e)
	{
		$this->error_view('', $e->getFile() . ' - ' . $e->getLine() . ' - ' . $e->getMessage());
		mail('limuz@storysoop.com', $e->getMessage(), $e->getMessage());
		exit;
	}

	public function error_view($msg = '', $log = '', $ret = FALSE)
	{
		if ($log !== '') {
			log_message('error', $log);
		}

    echo $msg;
	}

	public function post_validation($param = array())
	{
		foreach ($param as $ids) {
			if ($this->CI->input->post($ids) === '') {
				$this->error_view('Invaild post data - '.$ids);
				return FALSE;
			}
		}
		return TRUE;
	}

	public function check_login( $id )
	{
		if ( ! $this->CI->session->userdata( $id ) ) {
			if ( $id == 'admin' ) {
				redirect( 'login' );
			}
		}
	}
}
