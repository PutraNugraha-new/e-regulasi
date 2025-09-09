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

        if (!empty($_FILES['file_upload']['name'])) {
            $input_name = "file_upload";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
            if (!isset($upload_file['error'])) {
                $file_name = $upload_file['data']['file_name'];

                $data_trx = array(
                    "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
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
            } else {
                $status = false;
            }
        } else {
            $data_trx = array(
                "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
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

    public function save_date_transaksi(){
        $id = $this->ipost("id");
        $tanggal = $this->ipost("tanggal");
        $expl_tanggal_form = explode(" ",$tanggal);
        $exp_tanggal = explode("-",$expl_tanggal_form[0]);
        $new_tanggal = $exp_tanggal[2]."-".$exp_tanggal[1]."-".$exp_tanggal[0]." ".$expl_tanggal_form[1];

        $data_trx = array(
            "created_at" => $new_tanggal,
        );

        $status = $this->trx_raperbup_model->edit($id, $data_trx);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }
}
