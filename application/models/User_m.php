<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function all_count($wh = null)
	{
		$query = $this->db->query("SELECT * FROM m_user WHERE u_enable = 'Y'" . $wh);

		log_message('debug', print_r($this->db->last_query(), true));

		if (!empty($query)) {
			return array(
				'count'     => $query->num_rows(),
				'amountsum' => 0
			);
		} else {
			return false;
		}
	}

	public function get_list($start, $limit, $sidx, $sord, $wh = null)
	{
		$query = $this->db->query("SELECT * FROM m_user WHERE u_enable = 'Y'" . $wh . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . "," . $limit);

		log_message('debug', print_r($this->db->last_query(), true));

		if (!empty($query)) {
			$response = (object)array();
			$i        = 0;
			foreach ($query->result() as $row) {
				$response->rows[$i]['id']   = $row->u_id;
				$response->rows[$i]['cell'] = [
					$row->u_id,
					$row->u_rdate,
					$row->u_email,
					$row->u_deviceid,
					$row->u_account_type,
					$row->u_passwd,
					$row->u_real_name,
					$row->u_page_name,
					$row->u_sex,
					$row->u_birth,
					$row->u_phone,
					$row->u_myphoto,
					$row->u_country_code
				];
				$i++;
			}
			//$response->userdata['amount'] = $at;
			return $response;
		} else {
			return false;
		}
	}

	public function add($p)
	{
		$this->db->trans_start();

		$sql = 'INSERT INTO m_user (u_email, u_deviceid, u_account_type, u_passwd, u_real_name, u_page_name, u_sex, u_birth, u_phone, u_country_code) VALUES (?,?,?,?,?,?,?,?,?,?)';

		if (!$this->db->query($sql, [$p['u_email'], $p['u_deviceid'], $p['u_account_type'], $p['u_passwd'], $p['u_real_name'], $p['u_page_name'], $p['u_sex'], $p['u_birth'], $p['u_phone'], $p['u_country_code']])) {
			throw new exception('user add - ' . $this->db->last_query());
		}

		$insert_id = $this->db->insert_id();

		if (!$this->db->trans_complete()) {
			throw new exception('user add - transaction failed.');
		}

		return $insert_id;
	}

	public function edit($p)
	{

		$this->db->trans_start();

		$this->db->query("UPDATE m_user SET u_email = ?, u_deviceid = ?, u_account_type = ?, u_passwd = ?, u_real_name=?, u_page_name=?, u_sex=?, u_birth=?, u_phone=?, u_country_code=? WHERE u_id = ?", [$p['u_email'], $p['u_deviceid'], $p['u_account_type'], $p['u_passwd'], $p['u_real_name'], $p['u_page_name'], $p['u_sex'], $p['u_birth'], $p['u_phone'], $p['u_country_code'], $p['id']]);

		log_message("debug", 'user edit - ' . $this->db->last_query());

		if (!$this->db->trans_complete()) {
			throw new exception('chapter edit - transaction');
		}

		return true;
	}

	public function delete($p)
	{
		$this->db->trans_start();

		foreach ($p as $value) {

			$this->db->query("DELETE FROM m_user WHERE u_id = ?", [$value]);
			if ($this->db->affected_rows() < 1) {
				return false;
			}
		}

		if (!$this->db->trans_complete()) {
			throw new exception('user delete - transaction error');
		}

		return true;
	}

	public function upload_file($p)
	{
		log_message('debug', print_r($p, true));

		$this->db->trans_start();

		if (!$this->db->query("UPDATE m_user SET u_myphoto = ? WHERE u_id = ?", array($p->filename, $p->id))) {
			throw new exception('user upload_file - ' . $this->db->last_query());
		}

		log_message('debug', print_r($this->db->last_query(), true));

		if (!$this->db->trans_complete()) {
			throw new exception('user transaction - ' . $this->db->last_query());
		}

		return true;
	}

	public function checkid($p)
	{
		$query = $this->db->query("SELECT u_id FROM m_user WHERE u_id != ? AND u_email = ?", [$p['u_id'], $p['u_email']]);

		if ($query->num_rows() === 0) {
			return true;
		} else {
			return false;
		}
	}

}
