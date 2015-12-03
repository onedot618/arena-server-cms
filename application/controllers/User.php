<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->base_class->check_login('admin');
		$this->load->model('user_m');
	}

	public function index()
	{
		$this->load->view('header_v');
		$this->load->view('user_v');
		$this->load->view('footer_v');
	}

	public function get_list()
	{
		log_message('debug', print_r($this->input->get(), true));
		$page  = $this->input->get('page');
		$limit = $this->input->get('rows');
		$sidx  = $this->input->get('sidx');
		$sord  = $this->input->get('sord');
		if (!$sidx) $sidx = 1;

		$wh = '';
		if ($this->input->get('_search') == 'true') {
			$sq = [];
			foreach ($this->input->get() as $k => $v) {
				switch ($k) {
					case 'u_email':
						$sq[] = "u_email LIKE '%" . $v . "%'";
						break;
					case'u_deviceid':
						$sq[] = "u_deviceid LIKE '%" . $v . "%'";
						break;
					case'u_account_type':
						$sq[] = "u_account_type = '" . $v . "'";
						break;
					case'u_real_name':
						$sq[] = "u_real_name LIKE '%" . $v . "%'";
						break;
					case'u_page_name':
						$sq[] = "u_page_name LIKE '%" . $v . "%'";
						break;
					case'u_sex':
						$sq[] = "u_sex = '" . $v . "'";
						break;
					case'u_birth':
						$sq[] = "u_birth LIKE '%" . $v . "%'";
						break;
					case'u_phone':
						$sq[] = "u_phone LIKE '%" . $v . "%'";
						break;
					default:
						break;
				}
			}
			$wh = ' AND ' . implode(' AND ', $sq);
		}

		$totalrows = $this->input->get('totalrows') ? $this->input->get('totalrows') : false;
		if ($totalrows) {
			$limit = $totalrows;
		}

		$all = $this->user_m->all_count($wh);

		$total_pages = ($all['count']) ? ceil($all['count'] / $limit) : 0;
		$start       = $limit * $page - $limit;

		$response = $this->user_m->get_list($start, $limit, $sidx, $sord, $wh);

		$response->page    = $page;
		$response->total   = $total_pages;
		$response->records = $all['count'];

		echo json_encode($response);
	}

	public function edit()
	{
		if ($this->user_m->edit($this->input->post())) {
			$result  = true;
			$message = 'Success';
		} else {
			$result  = false;
			$message = 'Udate is missing!';
		}

		echo json_encode(array(
			'result'  => $result,
			'id'      => $this->input->post('id'),
			'message' => $message
		));
	}

	public function add()
	{
		$insert_id = '';

		if ($this->input->post()) {
			if ($insert_id = $this->user_m->add($this->input->post())) {
				$result  = true;
				$message = 'Success';
			} else {
				$result  = false;
				$message = 'Insert is missing!';
			}
		} else {
			$result  = false;
			$message = 'Bad request.';
		}

		echo json_encode(array(
			'result'  => $result,
			'id'      => $insert_id,
			'message' => $message
		));
	}

	public function delete()
	{
		$arr = explode(',', $this->input->post('id'));

		if ($this->user_m->delete($arr)) {
			$result  = true;
			$message = 'Success';
		} else {
			$result  = false;
			$message = 'Delete is failed.';
		}

		echo json_encode(array(
			'result'  => $result,
			'message' => $message
		));
	}

	public function checkid()
	{
		echo json_encode(array(
			'valid' => $this->user_m->checkid($this->input->post())
		));
	}

	public function upload($id = null)
	{
		if ($id === null) {
			print json_encode(array('result' => false, 'message' => 'missing params.'));
			return;
		}

		log_message('debug', print_r($id, true));
		log_message('debug', print_r($_FILES, true));

		foreach ($_FILES as $key => $value) {
			if ($value['name']) {

				$this->load->library('upload', array(
					'upload_path'   => $this->config->item('datadir', 'arena') . 'myPhoto/',
					'allowed_types' => 'gif|jpg|png',
					'max_size'      => '1024',
					'max_width'     => '2048',
					'max_height'    => '1024',
					'file_name'     => 'myPhoto-' . $id,
					'overwrite'     => true
				));

				if (!$this->upload->do_upload($key)) {
					print json_encode(array('result' => false, 'message' => $this->upload->display_errors(null, null)));
					return;
				}

				$upload_data = $this->upload->data();

				$post = (object)array('id' => $id, 'key' => $key, 'filename' => $upload_data['file_name']);

				if (!$this->user_m->upload_file($post)) {
					print json_encode(array('result' => false, 'message' => 'missing update.'));
					return;
				}

				log_message('debug', print_r($this->upload->data(), true));
			}
		}
		print json_encode(array('result' => true, 'message' => 'okay'));
	}
}
