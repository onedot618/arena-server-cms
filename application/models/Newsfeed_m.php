<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsfeed_m extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function all_count($wh = null)
	{
		$query = $this->db->query("SELECT n_id FROM m_newsfeed WHERE 1" . $wh);

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
		$query = $this->db->query("SELECT * FROM m_newsfeed WHERE 1" . $wh . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . "," . $limit);

		if (!empty($query)) {
			$response = (object)array();

			$i = 0;

			foreach ($query->result() as $row) {

				$user = $this->db->query("SELECT u_email FROM m_user WHERE u_id = ?", [$row->n_u_id]);

				$response->rows[$i]['id']   = $row->n_id;
				$response->rows[$i]['cell'] = [
					$row->n_rdate,
					$row->n_u_id,
					$row->n_u_id . '(' . $user->row()->u_email . ')',
					$row->n_message
				];
				$i++;
			}
			return $response;
		} else {
			return false;
		}
	}

	public function add($p)
	{
		$this->db->trans_start();

		$sql = "INSERT INTO m_newsfeed (n_u_id,n_message) VALUES (?,?)";

		if (!$this->db->query($sql, [$p['n_u_id'], $p['n_message']])) {
			throw new exception('add - ' . $this->db->last_query());
		}

		$insert_id = $this->db->insert_id();

		if (!$this->db->trans_complete()) {
			throw new exception('add - transaction failed.');
		}

		return $insert_id;
	}

	public function edit($p)
	{

		$this->db->trans_start();

		$this->db->query("UPDATE m_newsfeed SET n_u_id = ?, n_message = ? WHERE n_id = ?", [$p['n_u_id'], $p['n_message'], $p['id']]);

		log_message("debug", 'edit - ' . $this->db->last_query());

		if (!$this->db->trans_complete()) {
			throw new exception('edit - transaction');
		}

		return true;
	}

	public function delete($p)
	{
		$this->db->trans_start();

		foreach ($p as $value) {

			$this->db->query("DELETE FROM m_newsfeed WHERE n_id = ?", [$value]);
			if ($this->db->affected_rows() < 1) {
				return false;
			}
		}

		if (!$this->db->trans_complete()) {
			throw new exception('delete - transaction error');
		}

		return true;
	}

}
