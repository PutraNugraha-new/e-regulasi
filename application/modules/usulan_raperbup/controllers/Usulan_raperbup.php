<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usulan_raperbup extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('usulan_raperbup_model');
        $this->load->model('kategori_usulan_model');
        $this->load->model('monitoring_raperbup/trx_raperbup_model', 'trx_raperbup_model');
    }

    public function index()
    {
        $data['breadcrumb'] = ["header_content" => "Usulan", "breadcrumb_link" => [['link' => false, 'content' => 'Usulan', 'is_active' => true]]];
        $this->execute('index', $data);
    }

    public function tambah_usulan_raperbup()
    {
        if (empty($_POST)) {
            $data['kategori_usulan'] = $this->kategori_usulan_model->get(
                array(
                    "order_by" => array(
                        "nama_kategori" => "ASC"
                    )
                )
            );

            $data['breadcrumb'] = ["header_content" => "Usulan", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'usulan_raperbup', 'content' => 'Usulan', 'is_active' => false], ['link' => false, 'content' => 'Tambah Usulan', 'is_active' => true]]];
            $this->execute('form_usulan_raperbup', $data);
        } else {
            $input_name = "file_upload";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");

            if (!isset($upload_file['error'])) {

                if (in_array(decrypt_data($this->ipost("kategori_usulan")), array("1", "2"))) {
                    //perda & perbup
                    $input_name_lampiran = "file_lampiran";
                    $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc");

                    if (!isset($upload_file_lampiran['error'])) {
                        $input_name_lampiran_sk_tim = "file_lampiran_sk_tim";
                        $upload_file_lampiran_sk_tim = $this->upload_file($input_name_lampiran_sk_tim, $this->config->item('file_lampiran'), "", "doc");

                        if (!isset($upload_file_lampiran_sk_tim['error'])) {

                            $input_name_lampiran_daftar_hadir = "file_lampiran_daftar_hadir";
                            $upload_file_lampiran_daftar_hadir = $this->upload_file($input_name_lampiran_daftar_hadir, $this->config->item('file_lampiran'), "", "doc");

                            if (!isset($upload_file_lampiran_daftar_hadir['error'])) {
                                $data = array(
                                    "nama_peraturan" => $this->ipost("nama_peraturan"),
                                    "lampiran" => $upload_file_lampiran['data']['file_name'],
                                    "lampiran_sk_tim" => $upload_file_lampiran_sk_tim['data']['file_name'],
                                    "lampiran_daftar_hadir" => $upload_file_lampiran_daftar_hadir['data']['file_name'],
                                    "kategori_usulan_id" => decrypt_data($this->ipost("kategori_usulan")),
                                    // "nomor_register" => $final_code,
                                    'created_at' => $this->datetime(),
                                    "id_user_created" => $this->session->userdata("id_user")
                                );

                                $status = $this->usulan_raperbup_model->save($data);
                                if ($status) {
                                    $data_trx = array(
                                        "file_usulan_raperbup" => $upload_file['data']['file_name'],
                                        "usulan_raperbup_id" => $status,
                                        "level_user_id_status" => $this->session->userdata("level_user_id"),
                                        "status_tracking" => "1",
                                        'created_at' => $this->datetime(),
                                        "id_user_created" => $this->session->userdata("id_user")
                                    );

                                    $this->trx_raperbup_model->save($data_trx);
                                    $this->session->set_flashdata('message', 'Data baru berhasil ditambahkan');
                                    $this->session->set_flashdata('type-alert', 'success');
                                    redirect('usulan_raperbup');
                                } else {
                                    $this->session->set_flashdata('message', 'Data baru gagal ditambahkan');
                                    $this->session->set_flashdata('type-alert', 'danger');
                                    redirect('usulan_raperbup');
                                }
                            } else {
                                $this->session->set_flashdata('message', $upload_file_lampiran_daftar_hadir['error']);
                                $this->session->set_flashdata('type-alert', 'danger');
                                redirect('usulan_raperbup/tambah_usulan_raperbup');
                            }
                        } else {
                            $this->session->set_flashdata('message', $upload_file_lampiran_sk_tim['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('usulan_raperbup/tambah_usulan_raperbup');
                        }
                    } else {
                        $this->session->set_flashdata('message', $upload_file_lampiran['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup/tambah_usulan_raperbup');
                    }
                } else {
                    //kepbup
                    $input_name_lampiran = "file_lampiran";
                    $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc");

                    if (!isset($upload_file_lampiran['error'])) {
                        $data = array(
                            "nama_peraturan" => $this->ipost("nama_peraturan"),
                            "lampiran" => $upload_file_lampiran['data']['file_name'],
                            "kategori_usulan_id" => decrypt_data($this->ipost("kategori_usulan")),
                            // "nomor_register" => $final_code,
                            'created_at' => $this->datetime(),
                            "id_user_created" => $this->session->userdata("id_user")
                        );

                        $status = $this->usulan_raperbup_model->save($data);
                        if ($status) {
                            $data_trx = array(
                                "file_usulan_raperbup" => $upload_file['data']['file_name'],
                                "usulan_raperbup_id" => $status,
                                "level_user_id_status" => $this->session->userdata("level_user_id"),
                                "status_tracking" => "1",
                                'created_at' => $this->datetime(),
                                "id_user_created" => $this->session->userdata("id_user")
                            );

                            $this->trx_raperbup_model->save($data_trx);
                            $this->session->set_flashdata('message', 'Data baru berhasil ditambahkan');
                            $this->session->set_flashdata('type-alert', 'success');
                            redirect('usulan_raperbup');
                        } else {
                            $this->session->set_flashdata('message', 'Data baru gagal ditambahkan');
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('usulan_raperbup');
                        }
                    } else {
                        $this->session->set_flashdata('message', $upload_file_lampiran['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup/tambah_usulan_raperbup');
                    }
                }
            } else {
                $this->session->set_flashdata('message', $upload_file['error']);
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('usulan_raperbup/tambah_usulan_raperbup');
            }
        }
    }

    public function edit_usulan_raperbup($id_usulan_raperbup)
    {
        $data_master = $this->usulan_raperbup_model->get(
            array(
                "join" => array(
                    "trx_raperbup" => "id_usulan_raperbup=usulan_raperbup_id AND trx_raperbup.deleted_at IS NULL"
                ),
                "where" => array(
                    "id_usulan_raperbup" => decrypt_data($id_usulan_raperbup)
                )
            )
        );

        if (!$data_master) {
            $this->page_error();
        } else {
            $file_usulan_raperbup = $this->trx_raperbup_model->get(
                array(
                    "order_by" => array(
                        "id_trx_raperbup" => "ASC"
                    ),
                    "where" => array(
                        "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup)
                    ),
                    "limit" => "1"
                ),
                "row"
            );

            if (empty($_POST)) {
                $data['kategori_usulan'] = $this->kategori_usulan_model->get(
                    array(
                        "order_by" => array(
                            "nama_kategori" => "ASC"
                        )
                    )
                );

                $data['content'] = $data_master[0];

                $ekstensi_file_usulan = explode(".", $file_usulan_raperbup->file_usulan_raperbup);
                $data['url_preview_usulan'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_usulan") . "/" . $file_usulan_raperbup->file_usulan_raperbup . "','" . $ekstensi_file_usulan[1] . "')\">View</button>";

                $data['url_preview_lampiran'] = "";
                if ($data_master[0]->lampiran) {
                    $ekstensi_file_lampiran = explode(".", $data_master[0]->lampiran);
                    $data['url_preview_lampiran'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_lampiran") . "/" . $data_master[0]->lampiran . "','" . $ekstensi_file_lampiran[1] . "')\">View</button>";
                }

                $data['url_preview_lampiran_sk_tim'] = "";
                if ($data_master[0]->lampiran_sk_tim) {
                    $ekstensi_file_lampiran_sk_tim = explode(".", $data_master[0]->lampiran_sk_tim);
                    $data['url_preview_lampiran_sk_tim'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_lampiran") . "/" . $data_master[0]->lampiran_sk_tim . "','" . $ekstensi_file_lampiran_sk_tim[1] . "')\">View</button>";
                }

                $data['url_preview_lampiran_daftar_hadir'] = "";
                if ($data_master[0]->lampiran_daftar_hadir) {
                    $ekstensi_file_lampiran_daftar_hadir = explode(".", $data_master[0]->lampiran_daftar_hadir);
                    $data['url_preview_lampiran_daftar_hadir'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_lampiran") . "/" . $data_master[0]->lampiran_daftar_hadir . "','" . $ekstensi_file_lampiran_daftar_hadir[1] . "')\">View</button>";
                }

                $data['breadcrumb'] = ["header_content" => "Usulan", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'usulan_raperbup', 'content' => 'Usulan', 'is_active' => false], ['link' => false, 'content' => 'Ubah Usulan', 'is_active' => true]]];
                $this->execute('form_usulan_raperbup', $data);
            } else {
                $nama_file_usulan = $file_usulan_raperbup->file_usulan_raperbup;
                if (!empty($_FILES['file_upload']['name'])) {
                    if (count($data_master) > 1) {
                        $this->session->set_flashdata('message', 'Data tidak bisa diubah');
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup');
                    } else {
                        $input_name = "file_upload";
                        $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                        if (isset($upload_file['error'])) {
                            $this->session->set_flashdata('message', $upload_file['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_usulan = $upload_file['data']['file_name'];
                    }
                }

                $nama_file_lampiran = $data_master[0]->lampiran;
                if (!empty($_FILES['file_lampiran']['name'])) {
                    $input_name_lampiran = "file_lampiran";
                    $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc");
                    if (isset($upload_file_lampiran['error'])) {
                        $this->session->set_flashdata('message', $upload_file_lampiran['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                    }

                    $nama_file_lampiran = $upload_file_lampiran['data']['file_name'];
                }

                $nama_file_lampiran_sk_tim = $data_master[0]->lampiran_sk_tim;
                if (!empty($_FILES['file_lampiran_sk_tim']['name'])) {
                    $input_name_lampiran_sk_tim = "file_lampiran_sk_tim";
                    $upload_file_lampiran_sk_tim = $this->upload_file($input_name_lampiran_sk_tim, $this->config->item('file_lampiran'), "", "doc");
                    if (isset($upload_file_lampiran_sk_tim['error'])) {
                        $this->session->set_flashdata('message', $upload_file_lampiran_sk_tim['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                    }

                    $nama_file_lampiran_sk_tim = $upload_file_lampiran_sk_tim['data']['file_name'];
                }

                $nama_file_lampiran_daftar_hadir = $data_master[0]->lampiran_daftar_hadir;
                if (!empty($_FILES['file_lampiran_daftar_hadir']['name'])) {
                    $input_name_lampiran_daftar_hadir = "file_lampiran_daftar_hadir";
                    $upload_file_lampiran_daftar_hadir = $this->upload_file($input_name_lampiran_daftar_hadir, $this->config->item('file_lampiran'), "", "doc");
                    if (isset($upload_file_lampiran_daftar_hadir['error'])) {
                        $this->session->set_flashdata('message', $upload_file_lampiran_daftar_hadir['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                    }

                    $nama_file_lampiran_daftar_hadir = $upload_file_lampiran_daftar_hadir['data']['file_name'];
                }

                $data = array(
                    "nama_peraturan" => $this->ipost("nama_peraturan"),
                    "lampiran" => $nama_file_lampiran,
                    "lampiran_sk_tim" => $nama_file_lampiran_sk_tim,
                    "lampiran_daftar_hadir" => $nama_file_lampiran_daftar_hadir,
                    "kategori_usulan_id" => decrypt_data($this->ipost("kategori_usulan")),
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $this->usulan_raperbup_model->edit(decrypt_data($id_usulan_raperbup), $data);

                $data_trx = array(
                    "file_usulan_raperbup" => $nama_file_usulan,
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->edit($data_master[0]->id_trx_raperbup, $data_trx);
                if ($status) {
                    $this->session->set_flashdata('message', 'Data berhasil diubah');
                    $this->session->set_flashdata('type-alert', 'success');
                    redirect('usulan_raperbup');
                } else {
                    $this->session->set_flashdata('message', 'Data gagal diubah');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('usulan_raperbup');
                }
            }
        }
    }

    public function delete_usulan_raperbup()
    {
        $id_usulan_raperbup = decrypt_data($this->iget('id_usulan_raperbup'));
        $data_master = $this->usulan_raperbup_model->get(
            array(
                "join" => array(
                    "trx_raperbup" => "id_usulan_raperbup=usulan_raperbup_id AND trx_raperbup.deleted_at IS NULL"
                ),
                "where" => array(
                    "id_usulan_raperbup" => $id_usulan_raperbup
                )
            )
        );

        if (!$data_master) {
            $this->page_error();
        } else if (count($data_master) > 1) {
            $status = false;
        } else {
            $data_remove = array(
                "deleted_at" => $this->datetime(),
                "id_user_deleted" => $this->session->userdata("id_user")
            );
            foreach ($data_master as $key => $row) {
                $this->trx_raperbup_model->edit($row->id_trx_raperbup, $data_remove);
            }
            $status = $this->usulan_raperbup_model->edit($id_usulan_raperbup, $data_remove);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function detail_usulan_raperbup($id_usulan_raperbup)
    {
        $data['id_usulan_raperbup'] = $id_usulan_raperbup;

        $data_last_trx = $this->trx_raperbup_model->get(
            array(
                "fields" => "trx_raperbup.*,teruskan_provinsi",
                "where" => array(
                    "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
                ),
                "join" => array(
                    "usulan_raperbup" => "id_usulan_raperbup=usulan_raperbup_id",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id"
                ),
                "order_by" => array(
                    "trx_raperbup.created_at" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        if ($data_last_trx->status_tracking == "5") {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1' && $data_last_trx->sekda_agree_disagree == '1' && $data_last_trx->wabup_agree_disagree == '1' && $data_last_trx->bupati_agree_disagree == '1') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1' && $data_last_trx->sekda_agree_disagree == '1' && $data_last_trx->wabup_agree_disagree == '1' && $data_last_trx->bupati_agree_disagree == '2') {
            $data['status_upload_perbaikan'] = true;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1' && $data_last_trx->sekda_agree_disagree == '1' && $data_last_trx->wabup_agree_disagree == '1') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1' && $data_last_trx->sekda_agree_disagree == '1' && $data_last_trx->wabup_agree_disagree == '2') {
            $data['status_upload_perbaikan'] = true;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1' && $data_last_trx->sekda_agree_disagree == '1') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1' && $data_last_trx->sekda_agree_disagree == '2') {
            $data['status_upload_perbaikan'] = true;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '1') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1' && $data_last_trx->asisten_agree_disagree == '2') {
            $data['status_upload_perbaikan'] = true;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '1' && $data_last_trx->kabag_agree_disagree == '1') {
            //kasubag setuju dan kabag menyetujuinya
            if ($data_last_trx->teruskan_provinsi == "1" && $data_last_trx->file_lampiran_provinsi != "" && $data_last_trx->provinsi_agree_disagree == "1") {
                $data['status_upload_perbaikan'] = false;
            } else if ($data_last_trx->teruskan_provinsi == "1" && $data_last_trx->file_lampiran_provinsi != "" && $data_last_trx->provinsi_agree_disagree == "2") {
                $data['status_upload_perbaikan'] = true;
            } else if ($data_last_trx->teruskan_provinsi == "1" && $data_last_trx->file_lampiran_provinsi != "") {
                $data['status_upload_perbaikan'] = false;
            } else {
                $data['status_upload_perbaikan'] = false;
            }
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '2' && $data_last_trx->kabag_agree_disagree == '1') {
            $data['status_upload_perbaikan'] = true;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree != '' && $data_last_trx->kabag_agree_disagree == '2') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree != '' && $data_last_trx->kabag_agree_disagree == '') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "3" && $data_last_trx->kasubbag_agree_disagree == '' && $data_last_trx->kabag_agree_disagree == '') {
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "2") {
            //disposisi
            $data['status_upload_perbaikan'] = false;
        } else if ($data_last_trx->status_tracking == "1") {
            $data['status_upload_perbaikan'] = false;
        }

        $data['breadcrumb'] = ["header_content" => "Detail Usulan", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'usulan_raperbup', 'content' => 'Usulan', 'is_active' => false], ['link' => false, 'content' => 'Detail Usulan', 'is_active' => true]]];
        $this->execute('detail_raperbup', $data);
    }

    public function upload_perbaikan()
    {
        $id_usulan_raperbup = $this->ipost("id_usulan_raperbup_modal");
        $input_name = "file_upload";
        $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");

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

        if (!isset($upload_file['error'])) {

            $data_trx = array(
                "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
                "file_perbaikan" => $upload_file['data']['file_name'],
                "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => "3",
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $this->trx_raperbup_model->save($data_trx);

            $this->session->set_flashdata('message', 'File Perbaikan berhasil ditambahkan');
            $this->session->set_flashdata('type-alert', 'success');
            redirect('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup);
        } else {
            $this->session->set_flashdata('message', $upload_file['error']);
            $this->session->set_flashdata('type-alert', 'danger');
            redirect('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup);
        }
    }

    public function upload_perbaikan_hasil_rapat()
    {
        $id_usulan_raperbup = $this->ipost("id_usulan_raperbup_modal");
        $input_name = "file_upload";
        $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");

        if (!isset($upload_file['error'])) {

            $data_trx = array(
                "file_usulan_raperbup" => $upload_file['data']['file_name'],
                "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup),
                "level_user_id_status" => $this->session->userdata("level_user_id"),
                "status_tracking" => "4",
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            $this->trx_raperbup_model->save($data_trx);

            $this->session->set_flashdata('message', 'File Perbaikan berhasil ditambahkan');
            $this->session->set_flashdata('type-alert', 'success');
            redirect('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup);
        } else {
            $this->session->set_flashdata('message', $upload_file['error']);
            $this->session->set_flashdata('type-alert', 'danger');
            redirect('usulan_raperbup/detail_usulan_raperbup/' . $id_usulan_raperbup);
        }
    }
}
