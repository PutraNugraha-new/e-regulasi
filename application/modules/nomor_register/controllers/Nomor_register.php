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

        if ($data_last_trx->status_tracking == "3") {
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
                //update
                $data_trx = array(
                    "catatan_ditolak" => $this->ipost("catatan_disposisi"),
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $this->trx_raperbup_model->edit($cek_disposisi->id_trx_raperbup, $data_trx);
            } else {
                //insert
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
}
