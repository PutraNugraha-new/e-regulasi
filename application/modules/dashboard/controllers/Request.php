<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->is_login();

		// Load model dengan pengecekan keberadaan file
		$pemilik_usaha_path = APPPATH . 'modules/pemilik_usaha/models/Pemilik_usaha_model.php';
		$data_usaha_path = APPPATH . 'modules/data_usaha/models/Data_usaha_model.php';
		$aset_omset_path = APPPATH . 'modules/aset_omset_usaha/models/Aset_omset_usaha_model.php';

		if (file_exists($pemilik_usaha_path)) {
			$this->load->model('pemilik_usaha/Pemilik_usaha_model', 'pemilik_usaha_model', true);
			log_message('debug', 'Pemilik_usaha_model loaded successfully');
		} else {
			log_message('error', 'Pemilik_usaha_model not found at ' . $pemilik_usaha_path);
			$this->pemilik_usaha_model = null;
		}

		if (file_exists($data_usaha_path)) {
			$this->load->model('data_usaha/Data_usaha_model', 'data_usaha_model', true);
			log_message('debug', 'Data_usaha_model loaded successfully');
		} else {
			log_message('error', 'Data_usaha_model not found at ' . $data_usaha_path);
			$this->data_usaha_model = null;
		}

		if (file_exists($aset_omset_path)) {
			$this->load->model('aset_omset_usaha/Aset_omset_usaha_model', 'aset_omset_usaha_model', true);
			log_message('debug', 'Aset_omset_usaha_model loaded successfully');
		} else {
			log_message('error', 'Aset_omset_usaha_model not found at ' . $aset_omset_path);
			$this->aset_omset_usaha_model = null;
		}

		$this->load->model('Notifikasi_model', 'notifikasi_model', true);
		if (!$this->notifikasi_model) {
			log_message('error', 'Notifikasi_model not loaded');
		} else {
			log_message('debug', 'Notifikasi_model loaded successfully');
		}
	}

	function get_jumlah_data_pemilik_usaha_masuk()
	{
		if (!$this->pemilik_usaha_model) {
			log_message('error', 'Pemilik_usaha_model not loaded in get_jumlah_data_pemilik_usaha_masuk');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Pemilik_usaha_model not loaded']));
			return;
		}
		$data = $this->pemilik_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_pemilik_usaha_masuk"
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_usaha_masuk()
	{
		if (!$this->data_usaha_model) {
			log_message('error', 'Data_usaha_model not loaded in get_jumlah_data_usaha_masuk');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Data_usaha_model not loaded']));
			return;
		}
		$data = $this->data_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_data_usaha_masuk",
				"join" => array(
					"master_pemilik_usaha" => "id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1",
				),
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_aset_omset_masuk()
	{
		if (!$this->aset_omset_usaha_model) {
			log_message('error', 'Aset_omset_usaha_model not loaded in get_jumlah_data_aset_omset_masuk');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Aset_omset_usaha_model not loaded']));
			return;
		}
		$data = $this->aset_omset_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_data_aset_omset_masuk",
				"join" => array(
					"master_data_usaha" => "id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL and master_data_usaha.is_verified = 1",
					"master_pemilik_usaha" => "id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1"
				),
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_pemilik_usaha_belum_terverifikasi()
	{
		if (!$this->pemilik_usaha_model) {
			log_message('error', 'Pemilik_usaha_model not loaded in get_jumlah_data_pemilik_usaha_belum_terverifikasi');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Pemilik_usaha_model not loaded']));
			return;
		}
		$data = $this->pemilik_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_pemilik_usaha_belum_terverifikasi",
				"where" => array(
					"master_pemilik_usaha.is_verified" => "0"
				)
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_usaha_belum_terverifikasi()
	{
		if (!$this->data_usaha_model) {
			log_message('error', 'Data_usaha_model not loaded in get_jumlah_data_usaha_belum_terverifikasi');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Data_usaha_model not loaded']));
			return;
		}
		$data = $this->data_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_data_usaha_belum_terverifikasi",
				"join" => array(
					"master_pemilik_usaha" => "id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1",
				),
				"where" => array(
					"master_data_usaha.is_verified" => "0"
				)
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_aset_omset_belum_terverifikasi()
	{
		if (!$this->aset_omset_usaha_model) {
			log_message('error', 'Aset_omset_usaha_model not loaded in get_jumlah_data_aset_omset_belum_terverifikasi');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Aset_omset_usaha_model not loaded']));
			return;
		}
		$data = $this->aset_omset_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_data_aset_omset_belum_terverifikasi",
				"join" => array(
					"master_data_usaha" => "id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL and master_data_usaha.is_verified = 1",
					"master_pemilik_usaha" => "id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1"
				),
				"where" => array(
					"aset_omset_usaha.is_verified" => "0"
				)
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_pemilik_usaha_sudah_terverifikasi()
	{
		if (!$this->pemilik_usaha_model) {
			log_message('error', 'Pemilik_usaha_model not loaded in get_jumlah_data_pemilik_usaha_sudah_terverifikasi');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Pemilik_usaha_model not loaded']));
			return;
		}
		$data = $this->pemilik_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_pemilik_usaha_sudah_terverifikasi",
				"where" => array(
					"master_pemilik_usaha.is_verified" => "1"
				)
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_usaha_sudah_terverifikasi()
	{
		if (!$this->data_usaha_model) {
			log_message('error', 'Data_usaha_model not loaded in get_jumlah_data_usaha_sudah_terverifikasi');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Data_usaha_model not loaded']));
			return;
		}
		$data = $this->data_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_data_usaha_sudah_terverifikasi",
				"join" => array(
					"master_pemilik_usaha" => "id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1",
				),
				"where" => array(
					"master_data_usaha.is_verified" => "1"
				)
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_data_aset_omset_sudah_terverifikasi()
	{
		if (!$this->aset_omset_usaha_model) {
			log_message('error', 'Aset_omset_usaha_model not loaded in get_jumlah_data_aset_omset_sudah_terverifikasi');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Aset_omset_usaha_model not loaded']));
			return;
		}
		$data = $this->aset_omset_usaha_model->get(
			array(
				"fields" => "IFNULL(count(*),0) as jumlah_data_aset_omset_sudah_terverifikasi",
				"join" => array(
					"master_data_usaha" => "id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL and master_data_usaha.is_verified = 1",
					"master_pemilik_usaha" => "id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1"
				),
				"where" => array(
					"aset_omset_usaha.is_verified" => "1"
				)
			),
			"row"
		);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_unit_usaha()
	{
		if (!$this->data_usaha_model) {
			log_message('error', 'Data_usaha_model not loaded in get_jumlah_unit_usaha');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Data_usaha_model not loaded']));
			return;
		}
		$data = $this->data_usaha_model->query("
            SELECT IFNULL(COUNT(*),0) AS jumlah_unit_usaha
            FROM master_data_usaha
            JOIN master_pemilik_usaha ON id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL AND master_pemilik_usaha.is_verified = 1
            WHERE DATE_FORMAT(master_data_usaha.created_at,'%Y') = (SELECT DATE_FORMAT(master_data_usaha.created_at,'%Y') AS tahun
            FROM master_data_usaha
            WHERE master_data_usaha.deleted_at IS NULL
            GROUP BY tahun
            ORDER BY tahun DESC
            LIMIT 1)
            AND master_data_usaha.deleted_at IS NULL AND master_data_usaha.is_verified = 1
        ")->row();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_aset_umkm()
	{
		if (!$this->aset_omset_usaha_model) {
			log_message('error', 'Aset_omset_usaha_model not loaded in get_jumlah_aset_umkm');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Aset_omset_usaha_model not loaded']));
			return;
		}
		$data = $this->aset_omset_usaha_model->query("
            SELECT IFNULL(SUM(jumlah_aset), 0) AS jumlah_aset_umkm 
            FROM aset_omset_usaha 
            JOIN master_data_usaha ON id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL AND master_data_usaha.is_verified = 1 
            JOIN master_pemilik_usaha ON id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL AND master_pemilik_usaha.is_verified = 1 
            WHERE aset_omset_usaha.is_verified = '1' AND aset_omset_usaha.deleted_at IS NULL
            AND tahun_berkenaan = (SELECT tahun_berkenaan AS tahun
            FROM aset_omset_usaha
            WHERE aset_omset_usaha.deleted_at IS NULL
            GROUP BY tahun
            ORDER BY tahun DESC
            LIMIT 1)
        ")->row();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_jumlah_omset_umkm()
	{
		if (!$this->aset_omset_usaha_model) {
			log_message('error', 'Aset_omset_usaha_model not loaded in get_jumlah_omset_umkm');
			$this->output->set_status_header(500)->set_output(json_encode(['error' => 'Aset_omset_usaha_model not loaded']));
			return;
		}
		$data = $this->aset_omset_usaha_model->query("
            SELECT IFNULL(SUM(jumlah_omset_per_tahun), 0) AS jumlah_omset_umkm 
            FROM aset_omset_usaha 
            JOIN master_data_usaha ON id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL AND master_data_usaha.is_verified = 1 
            JOIN master_pemilik_usaha ON id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL AND master_pemilik_usaha.is_verified = 1 
            WHERE aset_omset_usaha.is_verified = '1' AND aset_omset_usaha.deleted_at IS NULL
            AND tahun_berkenaan = (SELECT tahun_berkenaan AS tahun
            FROM aset_omset_usaha
            WHERE aset_omset_usaha.deleted_at IS NULL
            GROUP BY tahun
            ORDER BY tahun DESC
            LIMIT 1)
        ")->row();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	function get_notifikasi()
	{
		$user_id = $this->session->userdata('id_user');
		if (!$user_id) {
			log_message('error', 'No user ID in session for get_notifikasi');
			$this->output->set_status_header(401)->set_output(json_encode(['error' => 'User not logged in']));
			return;
		}

		try {
			$data = $this->notifikasi_model->get(
				array(
					"fields" => "notifikasi.id_notifikasi, notifikasi.id_user_tujuan, notifikasi.id_usulan_raperbup, notifikasi.tipe_notif, notifikasi.pesan, notifikasi.dibaca, notifikasi.created_at, IFNULL(usulan_raperbup.nama_peraturan, 'Usulan Tanpa Nama') as nama_peraturan, IFNULL(user.nama_lengkap, 'Unknown') as nama_pengguna, user.level_user_id, usulan_raperbup.nomor_register, usulan_raperbup.kategori_usulan_id",
					"join" => array(
						"usulan_raperbup" => "notifikasi.id_usulan_raperbup = usulan_raperbup.id_usulan_raperbup AND usulan_raperbup.deleted_at IS NULL",
						"user" => "usulan_raperbup.id_user_created = user.id_user AND user.deleted_at IS NULL"
					),
					"where" => array(
						"notifikasi.id_user_tujuan" => $user_id,
						"notifikasi.dibaca" => 0
					),
					"order_by" => array(
						"notifikasi.created_at" => "DESC"
					),
					"limit" => 10
				)
			);

			$total = $this->notifikasi_model->get(
				array(
					"fields" => "IFNULL(COUNT(*), 0) AS total",
					"where" => array(
						"notifikasi.id_user_tujuan" => $user_id,
						"notifikasi.dibaca" => 0
					)
				),
				"row"
			);
			$total = $total ? $total->total : 0;

			log_message('debug', 'Notifikasi retrieved for user ' . $user_id . ': ' . json_encode($data));
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['notifikasi' => $data, 'total' => $total]));
		} catch (Exception $e) {
			log_message('error', 'Exception in get_notifikasi: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Internal server error: ' . $e->getMessage()]));
		}
	}

	function tandai_dibaca()
	{
		$user_id = $this->session->userdata('id_user');
		if (!$user_id) {
			log_message('error', 'No user ID in session for tandai_dibaca');
			$this->output->set_status_header(401)->set_output(json_encode(['error' => 'User not logged in']));
			return;
		}

		try {
			$id_notifikasi = $this->input->post('id_notifikasi');
			if ($id_notifikasi) {
				$status = $this->notifikasi_model->edit($id_notifikasi, ['dibaca' => 1]);
				log_message('debug', 'Notifikasi ID ' . $id_notifikasi . ' marked as read: ' . ($status ? 'success' : 'failed'));
			} else {
				$this->db->where('id_user_tujuan', $user_id)->update('notifikasi', ['dibaca' => 1]);
				$status = $this->db->affected_rows() >= 0;
				log_message('debug', 'All notifications for user ' . $user_id . ' marked as read: ' . ($status ? 'success' : 'failed'));
			}

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($status));
		} catch (Exception $e) {
			log_message('error', 'Exception in tandai_dibaca: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Internal server error: ' . $e->getMessage()]));
		}
	}

	function tandai_semua_dibaca()
	{
		$user_id = $this->session->userdata('id_user');
		if (!$user_id) {
			log_message('error', 'No user ID in session for tandai_semua_dibaca');
			$this->output->set_status_header(401)->set_output(json_encode(['error' => 'User not logged in']));
			return;
		}

		try {
			$this->db->where('id_user_tujuan', $user_id)->update('notifikasi', ['dibaca' => 1]);
			$status = $this->db->affected_rows() >= 0;
			log_message('debug', 'All notifications for user ' . $user_id . ' marked as read: ' . ($status ? 'success' : 'failed'));

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['status' => $status, 'message' => $status ? 'Pesan telah ditandai semua telah dibaca!' : 'Gagal menandai semua notifikasi.']));
		} catch (Exception $e) {
			log_message('error', 'Exception in tandai_semua_dibaca: ' . $e->getMessage());
			$this->output
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Internal server error: ' . $e->getMessage()]));
		}
	}
}