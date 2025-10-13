<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nomor_register extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('master_satker_model');
        $this->load->model('usulan_raperbup/usulan_raperbup_model', 'usulan_raperbup_model');
        $this->load->model('monitoring_raperbup/trx_raperbup_model', 'trx_raperbup_model');
        $this->load->model('Kategori_usulan_model', 'kategori_usulan_model');
    }

    public function index()
    {
        $data['skpd'] = $this->master_satker_model->get(
            array(
                "order_by" => array(
                    "nama" => "ASC"
                )
            )
        );

        $data['kategori_usulan'] = $this->kategori_usulan_model->get_all();
        $data['tahun'] = $this->usulan_raperbup_model->get_tahun_unik();

        $data['breadcrumb'] = ["header_content" => "Register Masuk", "breadcrumb_link" => [['link' => false, 'content' => 'Register Masuk', 'is_active' => true]]];
        $this->execute('index', $data);
    }

    public function save_nomor_register()
    {
        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => decrypt_data($this->ipost("id_usulan_raperbup")),
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx && $data_last_trx->status_tracking == "3") {
            $status = false;
        } else {
            $nomor_register = array(
                "nomor_register" => ($this->ipost("nomor_register") ? $this->ipost("nomor_register") : NULL),
                "id_user_kasubbag" => (decrypt_data($this->ipost("id_kasubbag")) ? decrypt_data($this->ipost("id_kasubbag")) : NULL),
                'updated_at' => $this->datetime(),
                "id_user_updated" => $this->session->userdata("id_user")
            );

            $status = $this->usulan_raperbup_model->edit(decrypt_data($this->ipost("id_usulan_raperbup")), $nomor_register);

            $cek_disposisi = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => decrypt_data($this->ipost("id_usulan_raperbup")),
                        "status_tracking" => "2",
                    ),
                ),
                "row"
            );

            if ($cek_disposisi) {
                $data_trx = array(
                    "catatan_ditolak" => $this->ipost("catatan_disposisi"),
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $this->trx_raperbup_model->edit($cek_disposisi->id_trx_raperbup, $data_trx);
            } else {
                $data_trx = array(
                    "usulan_raperbup_id" => decrypt_data($this->ipost("id_usulan_raperbup")),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "catatan_ditolak" => $this->ipost("catatan_disposisi"),
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $this->trx_raperbup_model->save($data_trx);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function cancel_usulan()
    {
        $id_usulan_raperbup = decrypt_data($this->ipost("id_usulan_raperbup"));
        $catatan_pembatalan = $this->ipost("catatan_pembatalan");

        // Ambil level user dari database
        $level_user = $this->db->select('nama_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();

        if (!$level_user || $level_user->nama_level_user != 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Hanya Admin Hukum yang dapat membatalkan usulan']));
            return;
        }

        // Cek status terakhir
        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_usulan_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx && in_array($data_last_trx->status_tracking, ['5', '6'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Usulan sudah dipublish atau dibatalkan']));
            return;
        }

        // Simpan transaksi pembatalan
        $data_trx = array(
            "usulan_raperbup_id" => $id_usulan_raperbup,
            "file_usulan_raperbup" => $data_last_trx ? $data_last_trx->file_usulan_raperbup : NULL,
            "catatan_ditolak" => $catatan_pembatalan,
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => "6",
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => $status, 'message' => $status ? 'Usulan berhasil dibatalkan' : 'Gagal membatalkan usulan']));
    }
}