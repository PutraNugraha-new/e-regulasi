<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_raperbup extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('usulan_raperbup/usulan_raperbup_model', 'usulan_raperbup_model');
        $this->load->model('trx_raperbup_model');
        $this->load->model('master_satker_model');
        $this->load->model('kategori_usulan_model');
        $this->load->model('Notifikasi_model'); // Tambah model notifikasi
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

        $data['breadcrumb'] = ["header_content" => "Monitoring Usulan", "breadcrumb_link" => [['link' => false, 'content' => 'Monitoring Usulan', 'is_active' => true]]];

        if ($this->session->userdata("level_user_id") == "6") {
            $this->execute('index_kabag', $data);
        } else if ($this->session->userdata("level_user_id") == "7") {
            $this->execute('index_kasubbag', $data);
        } else if ($this->session->userdata("level_user_id") == "8") {
            $this->execute('index_asisten', $data);
        } else if ($this->session->userdata("level_user_id") == "9") {
            $this->execute('index_sekda', $data);
        } else if ($this->session->userdata("level_user_id") == "10") {
            $this->execute('index_wabup', $data);
        } else if ($this->session->userdata("level_user_id") == "11") {
            $this->execute('index_bupati', $data);
        } else if ($this->session->userdata("level_user_id") == "4") {
            $data['kategori_usulan'] = $this->kategori_usulan_model->get(
                array(
                    "order_by" => array(
                        "nama_kategori" => "ASC"
                    )
                )
            );
            $this->execute('index_admin', $data);
        } else if ($this->session->userdata("level_user_id") == "12") {
            $this->execute('index_kalteng', $data);
        } else if ($this->session->userdata("level_user_id") == "13") {
            $data['kategori_usulan'] = $this->kategori_usulan_model->get(
                array(
                    "order_by" => array(
                        "nama_kategori" => "ASC"
                    )
                )
            );
            $this->execute('index_admin_diskominfo', $data);
        }
    }

    public function disposisi_monitoring_raperbup()
    {
        $id_usulan_raperbup = $this->ipost("id_usulan_raperbup");
        $catatan_disposisi = $this->ipost("catatan_disposisi");
        $id_kasubbag = $this->ipost("id_kasubbag");

        $nomor_register = array(
            "id_user_kasubbag" => decrypt_data($id_kasubbag),
            'updated_at' => $this->datetime(),
            "id_user_updated" => $this->session->userdata("id_user")
        );

        $status = $this->usulan_raperbup_model->edit(decrypt_data($id_usulan_raperbup), $nomor_register);

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        $data_trx = array(
            "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
            "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
            "catatan_ditolak" => $catatan_disposisi,
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => "2",
            "status_pesan" => "2",
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        if ($status) {
            $id_usulan_raperbup_decrypted = decrypt_data($id_usulan_raperbup);
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup_decrypted)->nama_peraturan ?: 'Usulan Tanpa Nama';

            // Notif ke Kasubbag (id_user_kasubbag)
            $data_notif = [
                'id_user_tujuan' => decrypt_data($id_kasubbag),
                'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                'tipe_notif' => 'disposisi',
                'pesan' => 'Usulan diteruskan oleh Admin Hukum untuk koreksi: ' . $nama_peraturan . ($catatan_disposisi ? ' (Catatan: ' . $catatan_disposisi . ')' : ''),
                'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup)
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif disposisi saved for Kasubbag: ' . json_encode($data_notif));

            // Notif ke Admin PD (pengaju)
            $id_pengaju = $this->db->select('id_user_created')
                ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                ->get('usulan_raperbup')
                ->row()->id_user_created;
            $data_notif = [
                'id_user_tujuan' => $id_pengaju,
                'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                'tipe_notif' => 'disposisi',
                'pesan' => 'Usulan Anda diteruskan ke Kasubbag oleh Admin Hukum: ' . $nama_peraturan . ($catatan_disposisi ? ' (Catatan: ' . $catatan_disposisi . ')' : ''),
                'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup)
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif disposisi saved for Admin PD: ' . json_encode($data_notif));
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function setuju_ditolak_monitoring_raperbup()
    {
        $id_peraturan = decrypt_data($this->ipost("id_peraturan"));
        $status = $this->ipost("status");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_peraturan,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        $data_trx = array(
            "usulan_raperbup_id" => $id_peraturan,
            "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
            "file_perbaikan" => ($status == '1' ? $data_last_trx->file_perbaikan : NULL),
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => '3',
            "status_pesan" => "1",
            "kasubbag_agree_disagree" => $status,
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        if ($status) {
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';
            if ($status == '1') {
                // Setuju: Notif ke Kabag Hukum (level 6)
                $kabag_users = $this->db->select('id_user')->where('level_user_id', 6)->get('user')->result();
                foreach ($kabag_users as $kabag) {
                    $data_notif = [
                        'id_user_tujuan' => $kabag->id_user,
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_kasubbag',
                        'pesan' => 'Usulan disetujui oleh Kasubbag untuk review Kabag: ' . $nama_peraturan,
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_peraturan))
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif setuju_kasubbag saved: ' . json_encode($data_notif));
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function raperbup_ditolak()
    {
        $id_usulan_raperbup = $this->ipost("id_usulan_raperbup");
        $catatan = $this->ipost("catatan");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        $id_usulan_raperbup_decrypted = decrypt_data($id_usulan_raperbup);
        $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup_decrypted)->nama_peraturan ?: 'Usulan Tanpa Nama';

        if (!empty($_FILES['file_upload']['name'])) {
            $input_name = "file_upload";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
            if (!isset($upload_file['error'])) {
                $file_name = $upload_file['data']['file_name'];

                $data_trx = array(
                    "usulan_raperbup_id" => $id_usulan_raperbup_decrypted,
                    "catatan_ditolak" => ($catatan ? $catatan : NULL),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "file_catatan_perbaikan" => $file_name,
                    "file_perbaikan" => $data_last_trx->file_perbaikan,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => "3",
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);

                if ($status) {
                    // Notif ke Admin PD (pengaju)
                    $id_pengaju = $this->db->select('id_user_created')
                        ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                        ->get('usulan_raperbup')
                        ->row()->id_user_created;
                    $data_notif = [
                        'id_user_tujuan' => $id_pengaju,
                        'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                        'tipe_notif' => 'tolak_kasubbag',
                        'pesan' => 'Usulan ditolak oleh Kasubbag: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup)
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif tolak_kasubbag saved: ' . json_encode($data_notif));
                }
            } else {
                $status = false;
            }
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => $id_usulan_raperbup_decrypted,
                "catatan_ditolak" => ($catatan ? $catatan : NULL),
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "file_catatan_perbaikan" => NULL,
                "file_perbaikan" => $data_last_trx->file_perbaikan,
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => "3",
                "status_pesan" => "2",
                "kasubbag_agree_disagree" => "2",
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status) {
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                    'tipe_notif' => 'tolak_kasubbag',
                    'pesan' => 'Usulan ditolak oleh Kasubbag: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup)
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_kasubbag saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_kasubbag()
    {
        $id_usulan_raperbup = decrypt_data($this->ipost("id_usulan_raperbup"));
        $enum_status = $this->ipost("status_disposisi");
        $catatan = $this->ipost("catatan");

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

        $file_usulan = $data_last_trx->file_usulan_raperbup;
        $file_catatan_perbaikan = NULL;
        if ($data_last_trx->file_perbaikan && $enum_status == '1') {
            $file_usulan = $data_last_trx->file_perbaikan;
        }

        $data_trx = array(
            "usulan_raperbup_id" => $id_usulan_raperbup,
            "file_usulan_raperbup" => $file_usulan,
            "file_catatan_perbaikan" => ($enum_status == '1' ? $data_last_trx->file_catatan_perbaikan : NULL),
            "file_perbaikan" => ($enum_status == '2' ? $data_last_trx->file_perbaikan : NULL),
            "catatan_ditolak" => ($enum_status == '1' ? $data_last_trx->catatan_ditolak : $catatan),
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => $data_last_trx->status_tracking,
            "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
            "kabag_agree_disagree" => $enum_status,
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        if ($status) {
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
            if ($enum_status == '1') {
                // Setuju: Notif ke Asisten (level 8) atau Admin Provinsi (level 12) jika teruskan_provinsi = 1
                $usulan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup);
                if ($usulan->teruskan_provinsi == '1') {
                    $provinsi_users = $this->db->select('id_user')->where('level_user_id', 12)->get('user')->result();
                    foreach ($provinsi_users as $provinsi) {
                        $data_notif = [
                            'id_user_tujuan' => $provinsi->id_user,
                            'id_usulan_raperbup' => $id_usulan_raperbup,
                            'tipe_notif' => 'setuju_kabag',
                            'pesan' => 'Usulan disetujui oleh Kabag Hukum untuk review Provinsi: ' . $nama_peraturan,
                            'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_usulan_raperbup))
                        ];
                        $this->Notifikasi_model->simpan_notif($data_notif);
                        log_message('debug', 'Notif setuju_kabag saved for Provinsi: ' . json_encode($data_notif));
                    }
                } else {
                    $asisten_users = $this->db->select('id_user')->where('level_user_id', 8)->get('user')->result();
                    foreach ($asisten_users as $asisten) {
                        $data_notif = [
                            'id_user_tujuan' => $asisten->id_user,
                            'id_usulan_raperbup' => $id_usulan_raperbup,
                            'tipe_notif' => 'setuju_kabag',
                            'pesan' => 'Usulan disetujui oleh Kabag Hukum untuk review Asisten: ' . $nama_peraturan,
                            'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_usulan_raperbup))
                        ];
                        $this->Notifikasi_model->simpan_notif($data_notif);
                        log_message('debug', 'Notif setuju_kabag saved for Asisten: ' . json_encode($data_notif));
                    }
                }
            } else {
                // Tolak: Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_usulan_raperbup)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_usulan_raperbup,
                    'tipe_notif' => 'tolak_kabag',
                    'pesan' => 'Usulan ditolak oleh Kabag Hukum: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_usulan_raperbup))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_kabag saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_asisten()
    {
        $id_peraturan = decrypt_data($this->ipost("id_usulan_raperbup"));
        $status = $this->ipost("status");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_peraturan,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->sekda_agree_disagree == "1") {
            $status = false;
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => $id_peraturan,
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => '3',
                "status_pesan" => "2",
                "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "asisten_agree_disagree" => $status,
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status && $status == '1') {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Setuju: Notif ke Sekda (level 9)
                $sekda_users = $this->db->select('id_user')->where('level_user_id', 9)->get('user')->result();
                foreach ($sekda_users as $sekda) {
                    $data_notif = [
                        'id_user_tujuan' => $sekda->id_user,
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_asisten',
                        'pesan' => 'Usulan disetujui oleh Asisten untuk review Sekda: ' . $nama_peraturan,
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_peraturan))
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif setuju_asisten saved: ' . json_encode($data_notif));
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_asisten_ditolak()
    {
        $id_raperbup = decrypt_data($this->ipost("id_raperbup"));
        $catatan = $this->ipost("catatan");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->sekda_agree_disagree == "1") {
            $status = false;
        } else {
            if (!empty($_FILES['file_upload']['name'])) {
                $input_name = "file_upload";
                $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                if (!isset($upload_file['error'])) {
                    $file_name = $upload_file['data']['file_name'];

                    $data_trx = array(
                        "usulan_raperbup_id" => $id_raperbup,
                        "catatan_ditolak" => ($catatan ? $catatan : NULL),
                        "file_catatan_perbaikan" => $file_name,
                        "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                        "level_user_id_status" => $this->session->userdata("level_user_id"),
                        "status_tracking" => '3',
                        "status_pesan" => "2",
                        "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                        "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                        "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "asisten_agree_disagree" => "2",
                        'created_at' => $this->datetime(),
                        "id_user_created" => $this->session->userdata("id_user")
                    );

                    $status = $this->trx_raperbup_model->save($data_trx);
                } else {
                    $status = false;
                }
            } else {
                $data_trx = array(
                    "usulan_raperbup_id" => $id_raperbup,
                    "catatan_ditolak" => ($catatan ? $catatan : NULL),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '3',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                    "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "asisten_agree_disagree" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);
            }

            if ($status) {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_raperbup)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_raperbup,
                    'tipe_notif' => 'tolak_asisten',
                    'pesan' => 'Usulan ditolak oleh Asisten: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_raperbup))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_asisten saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_provinsi()
    {
        $id_raperbup = decrypt_data($this->ipost("id_raperbup"));
        $id_trx_raperbup = decrypt_data($this->ipost("id_trx_raperbup"));
        $status = $this->ipost("status");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->asisten_agree_disagree == "1") {
            $status = false;
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => $id_raperbup,
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => '3',
                "status_pesan" => "2",
                "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "provinsi_agree_disagree" => $status,
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status && $status == '1') {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Setuju: Notif ke Admin PD, Admin Hukum, Kasubbag
                $users = array_merge(
                    $this->db->select('id_user')->where('level_user_id', 5)->get('user')->result_array(),
                    $this->db->select('id_user')->where('level_user_id', 4)->get('user')->result_array(),
                    $this->db->select('id_user')->where('level_user_id', 7)->get('user')->result_array()
                );
                foreach ($users as $user) {
                    $data_notif = [
                        'id_user_tujuan' => $user['id_user'],
                        'id_usulan_raperbup' => $id_raperbup,
                        'tipe_notif' => 'setuju_provinsi',
                        'pesan' => 'Usulan disetujui oleh Admin Provinsi: ' . $nama_peraturan,
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_raperbup))
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif setuju_provinsi saved: ' . json_encode($data_notif));
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function usulan_ditolak_kalteng()
    {
        $id_raperbup = decrypt_data($this->ipost("id_raperbup"));
        $id_trx_raperbup = decrypt_data($this->ipost("id_trx_raperbup"));
        $catatan = $this->ipost("catatan");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->asisten_agree_disagree == "1") {
            $status = false;
        } else {
            if (!empty($_FILES['file_upload']['name'])) {
                $input_name = "file_upload";
                $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                if (!isset($upload_file['error'])) {
                    $file_name = $upload_file['data']['file_name'];

                    $data_trx = array(
                        "usulan_raperbup_id" => $id_raperbup,
                        "catatan_ditolak" => ($catatan ? $catatan : NULL),
                        "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                        "file_catatan_perbaikan" => $file_name,
                        "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                        "level_user_id_status" => $this->session->userdata("level_user_id"),
                        "status_tracking" => '3',
                        "status_pesan" => "2",
                        "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                        "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "provinsi_agree_disagree" => "2",
                        'created_at' => $this->datetime(),
                        "id_user_created" => $this->session->userdata("id_user")
                    );

                    $status = $this->trx_raperbup_model->save($data_trx);
                } else {
                    $status = false;
                }
            } else {
                $data_trx = array(
                    "usulan_raperbup_id" => $id_raperbup,
                    "catatan_ditolak" => ($catatan ? $catatan : NULL),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '3',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "provinsi_agree_disagree" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);
            }

            if ($status) {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_raperbup)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_raperbup,
                    'tipe_notif' => 'tolak_provinsi',
                    'pesan' => 'Usulan ditolak oleh Admin Provinsi: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_raperbup))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_provinsi saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_sekda()
    {
        $id_peraturan = decrypt_data($this->ipost("id_usulan_raperbup"));
        $status = $this->ipost("status");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_peraturan,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->wabup_agree_disagree == "1") {
            $status = false;
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => $id_peraturan,
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => '3',
                "status_pesan" => "2",
                "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                "sekda_agree_disagree" => $status,
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status && $status == '1') {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Setuju: Notif ke Wakil Bupati (level 10)
                $wabup_users = $this->db->select('id_user')->where('level_user_id', 10)->get('user')->result();
                foreach ($wabup_users as $wabup) {
                    $data_notif = [
                        'id_user_tujuan' => $wabup->id_user,
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_sekda',
                        'pesan' => 'Usulan disetujui oleh Sekda untuk review Wakil Bupati: ' . $nama_peraturan,
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_peraturan))
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif setuju_sekda saved: ' . json_encode($data_notif));
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_sekda_ditolak()
    {
        $id_raperbup = decrypt_data($this->ipost("id_raperbup"));
        $catatan = $this->ipost("catatan");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->wabup_agree_disagree == "1") {
            $status = false;
        } else {
            if (!empty($_FILES['file_upload']['name'])) {
                $input_name = "file_upload";
                $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                if (!isset($upload_file['error'])) {
                    $file_name = $upload_file['data']['file_name'];

                    $data_trx = array(
                        "usulan_raperbup_id" => $id_raperbup,
                        "catatan_ditolak" => ($catatan ? $catatan : NULL),
                        "file_catatan_perbaikan" => $file_name,
                        "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                        "level_user_id_status" => $this->session->userdata("level_user_id"),
                        "status_tracking" => '3',
                        "status_pesan" => "2",
                        "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                        "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                        "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                        "sekda_agree_disagree" => "2",
                        'created_at' => $this->datetime(),
                        "id_user_created" => $this->session->userdata("id_user")
                    );

                    $status = $this->trx_raperbup_model->save($data_trx);
                } else {
                    $status = false;
                }
            } else {
                $data_trx = array(
                    "usulan_raperbup_id" => $id_raperbup,
                    "catatan_ditolak" => ($catatan ? $catatan : NULL),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '3',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                    "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                    "sekda_agree_disagree" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);
            }

            if ($status) {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_raperbup)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_raperbup,
                    'tipe_notif' => 'tolak_sekda',
                    'pesan' => 'Usulan ditolak oleh Sekda: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_raperbup))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_sekda saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_wabup()
    {
        $id_peraturan = decrypt_data($this->ipost("id_usulan_raperbup"));
        $status = $this->ipost("status");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_peraturan,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->bupati_agree_disagree == "1") {
            $status = false;
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => $id_peraturan,
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => '3',
                "status_pesan" => "2",
                "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                "wabup_agree_disagree" => $status,
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status && $status == '1') {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Setuju: Notif ke Bupati (level 11)
                $bupati_users = $this->db->select('id_user')->where('level_user_id', 11)->get('user')->result();
                foreach ($bupati_users as $bupati) {
                    $data_notif = [
                        'id_user_tujuan' => $bupati->id_user,
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_wabup',
                        'pesan' => 'Usulan disetujui oleh Wakil Bupati untuk review Bupati: ' . $nama_peraturan,
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_peraturan))
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif setuju_wabup saved: ' . json_encode($data_notif));
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_wabup_ditolak()
    {
        $id_raperbup = decrypt_data($this->ipost("id_raperbup"));
        $catatan = $this->ipost("catatan");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else if ($data_last_trx->bupati_agree_disagree == "1") {
            $status = false;
        } else {
            if (!empty($_FILES['file_upload']['name'])) {
                $input_name = "file_upload";
                $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                if (!isset($upload_file['error'])) {
                    $file_name = $upload_file['data']['file_name'];

                    $data_trx = array(
                        "usulan_raperbup_id" => $id_raperbup,
                        "catatan_ditolak" => ($catatan ? $catatan : NULL),
                        "file_catatan_perbaikan" => $file_name,
                        "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                        "level_user_id_status" => $this->session->userdata("level_user_id"),
                        "status_tracking" => '3',
                        "status_pesan" => "2",
                        "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                        "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                        "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                        "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                        "wabup_agree_disagree" => "2",
                        'created_at' => $this->datetime(),
                        "id_user_created" => $this->session->userdata("id_user")
                    );

                    $status = $this->trx_raperbup_model->save($data_trx);
                } else {
                    $status = false;
                }
            } else {
                $data_trx = array(
                    "usulan_raperbup_id" => $id_raperbup,
                    "catatan_ditolak" => ($catatan ? $catatan : NULL),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '3',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                    "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                    "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                    "wabup_agree_disagree" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);
            }

            if ($status) {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_raperbup)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_raperbup,
                    'tipe_notif' => 'tolak_wabup',
                    'pesan' => 'Usulan ditolak oleh Wakil Bupati: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_raperbup))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_wabup saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_bupati()
    {
        $id_peraturan = decrypt_data($this->ipost("id_usulan_raperbup"));
        $status = $this->ipost("status");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_peraturan,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => $id_peraturan,
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => '3',
                "status_pesan" => "2",
                "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                "wabup_agree_disagree" => $data_last_trx->wabup_agree_disagree,
                "bupati_agree_disagree" => $status,
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status && $status == '1') {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Setuju: Notif ke Admin PD, Admin Hukum, Kasubbag
                $users = array_merge(
                    $this->db->select('id_user')->where('level_user_id', 5)->get('user')->result_array(),
                    $this->db->select('id_user')->where('level_user_id', 4)->get('user')->result_array(),
                    $this->db->select('id_user')->where('level_user_id', 7)->get('user')->result_array()
                );
                foreach ($users as $user) {
                    $data_notif = [
                        'id_user_tujuan' => $user['id_user'],
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_bupati',
                        'pesan' => 'Usulan disetujui oleh Bupati: ' . $nama_peraturan,
                        'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_peraturan))
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif setuju_bupati saved: ' . json_encode($data_notif));
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function change_status_pemeriksaan_bupati_ditolak()
    {
        $id_raperbup = decrypt_data($this->ipost("id_raperbup"));
        $catatan = $this->ipost("catatan");

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_raperbup,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $status = false;
        } else {
            if (!empty($_FILES['file_upload']['name'])) {
                $input_name = "file_upload";
                $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                if (!isset($upload_file['error'])) {
                    $file_name = $upload_file['data']['file_name'];

                    $data_trx = array(
                        "usulan_raperbup_id" => $id_raperbup,
                        "catatan_ditolak" => ($catatan ? $catatan : NULL),
                        "file_catatan_perbaikan" => $file_name,
                        "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                        "level_user_id_status" => $this->session->userdata("level_user_id"),
                        "status_tracking" => '3',
                        "status_pesan" => "2",
                        "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                        "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                        "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                        "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                        "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                        "wabup_agree_disagree" => $data_last_trx->wabup_agree_disagree,
                        "bupati_agree_disagree" => "2",
                        'created_at' => $this->datetime(),
                        "id_user_created" => $this->session->userdata("id_user")
                    );

                    $status = $this->trx_raperbup_model->save($data_trx);
                } else {
                    $status = false;
                }
            } else {
                $data_trx = array(
                    "usulan_raperbup_id" => $id_raperbup,
                    "catatan_ditolak" => ($catatan ? $catatan : NULL),
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '3',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                    "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                    "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                    "wabup_agree_disagree" => $data_last_trx->wabup_agree_disagree,
                    "bupati_agree_disagree" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);
            }

            if ($status) {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_raperbup)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_raperbup,
                    'tipe_notif' => 'tolak_bupati',
                    'pesan' => 'Usulan ditolak oleh Bupati: ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_raperbup))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif tolak_bupati saved: ' . json_encode($data_notif));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function delete_trx_raperbup()
    {
        $id_trx_raperbup = decrypt_data($this->iget('id_trx_raperbup'));
        $data_master = $this->trx_raperbup_model->get_by($id_trx_raperbup);

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $data_master->usulan_raperbup_id,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if (!$data_master) {
            $this->page_error();
        }

        if ($data_last_trx->id_trx_raperbup == $id_trx_raperbup) {
            if ($data_last_trx->status_tracking == "2") {
                $data_remove_disposisi = [
                    "id_user_kasubbag" => NULL
                ];
                $this->usulan_raperbup_model->edit($data_master->usulan_raperbup_id, $data_remove_disposisi);
            }
            $data_remove = array(
                "deleted_at" => $this->datetime(),
                "id_user_deleted" => $this->session->userdata("id_user")
            );

            $status = $this->trx_raperbup_model->edit($id_trx_raperbup, $data_remove);

            if ($status && $data_last_trx->status_tracking == "2") {
                $nama_peraturan = $this->usulan_raperbup_model->get_by($data_master->usulan_raperbup_id)->nama_peraturan ?: 'Usulan Tanpa Nama';
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $data_master->usulan_raperbup_id)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $data_master->usulan_raperbup_id,
                    'tipe_notif' => 'disposisi_dibatalkan',
                    'pesan' => 'Disposisi usulan "' . $nama_peraturan . '" dibatalkan oleh Admin Hukum',
                    'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($data_master->usulan_raperbup_id))
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif disposisi_dibatalkan saved: ' . json_encode($data_notif));
            }
        } else {
            $status = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function publish_for_skpd()
    {
        $id_usulan = decrypt_data($this->ipost("id_usulan_raperbup"));

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_usulan,
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if (!empty($_FILES['file_final']['name'])) {
            $input_name = "file_final";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_final'), "", "doc");
            if (!isset($upload_file['error'])) {
                $file_name = $upload_file['data']['file_name'];

                $data_trx = array(
                    "usulan_raperbup_id" => $id_usulan,
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "file_catatan_perbaikan" => $data_last_trx->file_catatan_perbaikan,
                    "file_perbaikan" => $data_last_trx->file_perbaikan,
                    "catatan_ditolak" => $data_last_trx->catatan_ditolak,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '5',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "file_lampiran_provinsi" => $data_last_trx->file_lampiran_provinsi,
                    "provinsi_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "asisten_agree_disagree" => $data_last_trx->asisten_agree_disagree,
                    "sekda_agree_disagree" => $data_last_trx->sekda_agree_disagree,
                    "wabup_agree_disagree" => $data_last_trx->wabup_agree_disagree,
                    "bupati_agree_disagree" => $data_last_trx->bupati_agree_disagree,
                    "file_final" => $file_name,
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);

                if ($status) {
                    $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan)->nama_peraturan ?: 'Usulan Tanpa Nama';
                    // Notif ke Admin PD, Admin Hukum, Kasubbag
                    $users = array_merge(
                        $this->db->select('id_user')->where('level_user_id', 5)->get('user')->result_array(),
                        $this->db->select('id_user')->where('level_user_id', 4)->get('user')->result_array(),
                        $this->db->select('id_user')->where('level_user_id', 7)->get('user')->result_array()
                    );
                    foreach ($users as $user) {
                        $data_notif = [
                            'id_user_tujuan' => $user['id_user'],
                            'id_usulan_raperbup' => $id_usulan,
                            'tipe_notif' => 'usulan_dipublish',
                            'pesan' => 'Usulan "' . $nama_peraturan . '" telah dipublish',
                            'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_usulan))
                        ];
                        $this->Notifikasi_model->simpan_notif($data_notif);
                        log_message('debug', 'Notif usulan_dipublish saved: ' . json_encode($data_notif));
                    }
                }
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        $data_usulan_raperbup = array(
            "last_level_user" => $data_last_trx->level_user_id_status,
        );

        $this->usulan_raperbup_model->edit($id_usulan, $data_usulan_raperbup);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function usulan_provinsi()
    {
        $id_usulan_raperbup = decrypt_data($this->ipost("id_usulan_raperbup"));

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

        if (!empty($_FILES['lampiran_provinsi']['name'])) {
            $input_name = "lampiran_provinsi";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_lampiran_provinsi'), "", "doc");
            if (!isset($upload_file['error'])) {
                $file_name = $upload_file['data']['file_name'];

                $data_trx = array(
                    "usulan_raperbup_id" => $id_usulan_raperbup,
                    "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                    "status_tracking" => '3',
                    "status_pesan" => "2",
                    "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
                    "kabag_agree_disagree" => $data_last_trx->kabag_agree_disagree,
                    "file_lampiran_provinsi" => $file_name,
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->save($data_trx);

                if ($status) {
                    $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';
                    // Notif ke Admin Provinsi (level 12)
                    $provinsi_users = $this->db->select('id_user')->where('level_user_id', 12)->get('user')->result();
                    foreach ($provinsi_users as $provinsi) {
                        $data_notif = [
                            'id_user_tujuan' => $provinsi->id_user,
                            'id_usulan_raperbup' => $id_usulan_raperbup,
                            'tipe_notif' => 'usulan_provinsi',
                            'pesan' => 'Usulan dikirim ke Provinsi untuk review: ' . $nama_peraturan,
                            'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($id_usulan_raperbup))
                        ];
                        $this->Notifikasi_model->simpan_notif($data_notif);
                        log_message('debug', 'Notif usulan_provinsi saved: ' . json_encode($data_notif));
                    }
                }
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function save_date_transaksi()
    {
        $id = $this->ipost("id");
        $tanggal = $this->ipost("tanggal");
        $expl_tanggal_form = explode(" ", $tanggal);
        $exp_tanggal = explode("-", $expl_tanggal_form[0]);
        $new_tanggal = $exp_tanggal[2] . "-" . $exp_tanggal[1] . "-" . $exp_tanggal[0] . " " . $expl_tanggal_form[1];

        $data_trx = array(
            "created_at" => $new_tanggal,
        );

        $status = $this->trx_raperbup_model->edit($id, $data_trx);

        if ($status) {
            // Ambil data transaksi untuk notifikasi
            $trx_data = $this->trx_raperbup_model->get_by($id);
            $nama_peraturan = $this->usulan_raperbup_model->get_by($trx_data->usulan_raperbup_id)->nama_peraturan ?: 'Usulan Tanpa Nama';
            // Notif ke Admin PD (pengaju)
            $id_pengaju = $this->db->select('id_user_created')
                ->where('id_usulan_raperbup', $trx_data->usulan_raperbup_id)
                ->get('usulan_raperbup')
                ->row()->id_user_created;
            $data_notif = [
                'id_user_tujuan' => $id_pengaju,
                'id_usulan_raperbup' => $trx_data->usulan_raperbup_id,
                'tipe_notif' => 'ubah_tanggal_transaksi',
                'pesan' => 'Tanggal transaksi usulan "' . $nama_peraturan . '" telah diubah menjadi ' . $new_tanggal,
                'link' => base_url('usulan_raperbup/detail_usulan_raperbup/' . encrypt_data($trx_data->usulan_raperbup_id))
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif ubah_tanggal_transaksi saved: ' . json_encode($data_notif));
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }
}