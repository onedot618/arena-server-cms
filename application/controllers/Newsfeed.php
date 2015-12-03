<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsfeed extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->base_class->check_login('admin');
		$this->load->model('newsfeed_m');
	}

	public function index()
	{
		$this->load->view('header_v');
		$this->load->view('newsfeed_v');
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
					case'u_email':
						$sq[] = "n_u_id = '" . $v . "'";
						break;
					case'n_message':
						$sq[] = "n_message LIKE '%" . $v . "%'";
						break;
					default:
						break;
				}
			}

			$wh = ' AND ' . implode(' AND ', $sq);
		}

		log_message('debug', 'RULES QUERY - ' . print_r($wh, true));

		$totalrows = $this->input->get('totalrows') ? $this->input->get('totalrows') : false;
		if ($totalrows) {
			$limit = $totalrows;
		}

		$all = $this->newsfeed_m->all_count($wh);

		$total_pages = ($all['count']) ? ceil($all['count'] / $limit) : 0;
		$start       = $limit * $page - $limit;

		$response = $this->newsfeed_m->get_list($start, $limit, $sidx, $sord, $wh);

		$response->page    = $page;
		$response->total   = $total_pages;
		$response->records = $all['count'];

		echo json_encode($response);
	}

	public function edit()
	{
		if ($this->newsfeed_m->edit($this->input->post())) {
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

			if ($insert_id = $this->newsfeed_m->add($this->input->post())) {
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

		if ($this->newsfeed_m->delete($arr)) {
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

}
