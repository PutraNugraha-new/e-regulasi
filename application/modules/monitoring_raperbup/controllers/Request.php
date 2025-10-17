<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('usulan_raperbup/usulan_raperbup_model', 'usulan_raperbup_model');
        $this->load->model('trx_raperbup_model');
        $this->load->model('monitoring_raperbup/Trx_raperbup_model', 'trx_raperbup_model');
        $this->load->model('user_model');
        $this->load->model('level_user/level_user_model', 'level_user_model');
    }

    public function get_detail_peraturan()
    {
        $id_usulan_raperbup = decrypt_data($this->iget("id_peraturan"));

        $nama_kasubbag = $this->usulan_raperbup_model->get(
            array(
                "fields" => "user.*",
                "where" => array(
                    "id_usulan_raperbup" => $id_usulan_raperbup
                ),
                "join" => array(
                    "user" => "id_user=id_user_kasubbag"
                )
            ),
            "row"
        );

        $data_usulan = $this->trx_raperbup_model->get(
            array(
                "fields" => "trx_raperbup.*,
                                DATE_FORMAT(trx_raperbup.created_at,'%Y-%m-%d') AS tanggal_custom,
                                DATE_FORMAT(trx_raperbup.created_at,'%H:%i:%s') AS time_custom,
                                class_color,
                                a.keterangan,
                                b.nama_lengkap,
                                teruskan_provinsi",
                "join" => array(
                    "level_user" => "level_user_id_status=id_level_user",
                    "usulan_raperbup" => "id_usulan_raperbup=usulan_raperbup_id",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "left_join" => array(
                    "user AS a" => "a.id_user=id_user_kasubbag",
                    "user AS b" => "b.id_user=trx_raperbup.id_user_created",
                ),
                "where" => array(
                    "usulan_raperbup_id" => $id_usulan_raperbup
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                )
            )
        );

        if ($this->session->userdata("level_user_id") == '7') {
            $data_disposisi = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $id_usulan_raperbup,
                        "status_tracking" => "2"
                    ),
                ),
                "row"
            );
        } else {
            $data_disposisi = true;
        }

        $templist = array();
        if ($data_disposisi) {

            foreach ($data_usulan as $key => $row) {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }
                $templist[$key]['status_terakhir'] = "";
                $templist[$key]['action'] = "";

                if ($row->status_tracking == "5") {
                    //publish
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Sudah Di Publish</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '1' && $row->bupati_agree_disagree == '1') {
                    //bupati setuju
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '1' && $row->bupati_agree_disagree == '2') {
                    //bupati tidak setuju
                    $file = "";
                    if ($row->file_catatan_perbaikan) {
                        $file_extension = explode(".", $row->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '1') {
                    //wabup setuju
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '2') {
                    //wabup tidak setuju
                    $file = "";
                    if ($row->file_catatan_perbaikan) {
                        $file_extension = explode(".", $row->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1') {
                    //sekda setuju
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '2') {
                    //sekda tidak setuju
                    $file = "";
                    if ($row->file_catatan_perbaikan) {
                        $file_extension = explode(".", $row->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1') {
                    //kesra setuju
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '2') {
                    //kesra tidak setujui
                    $file = "";
                    if ($row->file_catatan_perbaikan) {
                        $file_extension = explode(".", $row->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1') {
                    //usulan disetujui kabag hukum
                    if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi == "") {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                    } else if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi != "" && !$row->provinsi_agree_disagree) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Lampiran untuk Provinsi, sudah di upload oleh " . $row->nama_lengkap . " & sudah dikirim ke Admin Provinsi</div>";
                    } else if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi != "" && $row->provinsi_agree_disagree == "1") {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Provinsi</div>";
                    } else if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi != "" && $row->provinsi_agree_disagree == "2") {
                        $file = "";
                        if ($row->file_catatan_perbaikan) {
                            $file_extension = explode(".", $row->file_catatan_perbaikan);
                            $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                            $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                        }

                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui Provinsi</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                    } else {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                    }
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '2') {
                    //kasubag setuju tapi kabag tidak menyetujui
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Hasil Pemeriksaan " . $nama_kasubbag->nama_lengkap . " Tidak Disetujui Kabag Hukum </div>" . ($row->catatan_ditolak ? "<br />Catatan :<br />" . nl2br($row->catatan_ditolak) : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '2' && $row->kabag_agree_disagree == '1') {
                    //kasubag tidak setuju dan kabag menyetujui
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Hasil Pemeriksaan " . $nama_kasubbag->nama_lengkap . " Disetujui Kabag Hukum</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '2' && $row->kabag_agree_disagree == '2') {
                    //kasubag tidak setuju dan kabag tidak menyetujui
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Hasil Pemeriksaan " . $nama_kasubbag->nama_lengkap . " Tidak Disetujui Kabag Hukum </div>" . ($row->catatan_ditolak ? "<br />Catatan :<br />" . nl2br($row->catatan_ditolak) : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1') {
                    //kasubag setuju
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $nama_kasubbag->nama_lengkap . "</div>";
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '2') {
                    //kasubag tidak setujui
                    $file = "";
                    if ($row->file_catatan_perbaikan) {
                        $file_extension = explode(".", $row->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $nama_kasubbag->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<br />Catatan :<br />" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '' && $row->kabag_agree_disagree == '') {
                    //usulan perbaikan
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Usulan Perbaikan</div>";
                } else if ($row->status_tracking == "2") {
                    //usulan disposisi
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-info mb-2'>Diteruskan ke " . $row->keterangan . "</div><br />Catatan :<br />" . nl2br($row->catatan_ditolak);
                } else if ($row->status_tracking == "1") {
                    //usulan baru
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-light'>Usulan Baru</div>";
                }

                $templist[$key]['tanggal_custom'] = longdate_indo($row->tanggal_custom) . " " . $row->time_custom;

                $templist[$key]['file'] = "";
                if ($row->status_tracking == '1') {
                    $file_extension = explode(".", $row->file_usulan_raperbup);
                    $usulan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_usulan_raperbup;
                    $templist[$key]['file'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</butt>";
                } else if ($row->status_tracking == '3' && $row->kasubbag_agree_disagree == '' && $row->kabag_agree_disagree == '') {
                    $file_extension = explode(".", $row->file_perbaikan);
                    $usulan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_perbaikan;
                    $templist[$key]['file'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</button>";
                }

                $templist[$key]['action_delete'] = "";
                //delete trx
                if ($key == "0" && ($row->level_user_id_status == $this->session->userdata("level_user_id") || $row->status_tracking == '2')) {
                    $templist[$key]['action_delete'] = "<a class='dropdown-item has-icon text-danger mt-2' onClick=\"confirm_delete('" . encrypt_data($row->id_trx_raperbup) . "')\" href='#' style='padding:0 !important;'><i class='fas fa-trash-alt'></i></a>";
                }

                $templist[$key]['id_encrypt'] = encrypt_data($row->id_trx_raperbup);
            }
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    public function tandaiSemuaDibaca()
    {
        // Load model
        $this->load->model('trx_raperbup_model');

        // Panggil fungsi updateStatusPesan dari model
        $this->trx_raperbup_model->updateStatusPesan(2);

        // Respon ke view atau lakukan tindakan lain yang diperlukan
        echo 'Pesan telah ditandai semua telah dibaca!';
    }

    public function get_data_peraturan()
    {
        $filter = $this->iget("filter");
        $usulan_id = $this->iget("usulan_id");
        $skpd = $this->iget("skpd");
        if ($this->iget("kategori_usulan")) {
            $kategori_usulan = $this->iget("kategori_usulan");
        } else {
            $kategori_usulan = "all";
        }

        $wh = array();
        if ($usulan_id) {
            $wh["usulan_raperbup.id_usulan_raperbup"] = $usulan_id; // Gunakan ID mentah
        }
        if ($kategori_usulan != "all") {
            $wh["kategori_usulan_id"] = $kategori_usulan; // Gunakan ID mentah
        }
        if ($skpd) {
            $wh["user.master_satker_id"] = $skpd; // Filter by user's master_satker_id
        }

        // Kasubbag
        if ($this->session->userdata("level_user_id") == '7') {
            $wh["id_user_kasubbag"] = $this->session->userdata("id_user");

            $data_usulan = $this->usulan_raperbup_model->get(
                array(
                    "join" => array(
                        "user" => "usulan_raperbup.id_user_created = user.id_user"
                    ),
                    "where" => $wh,
                    "where_false" => "nomor_register IS NOT NULL",
                    "order_by" => array(
                        "nama_peraturan" => "DESC"
                    )
                )
            );
        } else {
            // Kabag && Admin
            $data_usulan = $this->usulan_raperbup_model->get(
                array(
                    "join" => array(
                        "user" => "usulan_raperbup.id_user_created = user.id_user"
                    ),
                    "where" => $wh,
                    "where_false" => "nomor_register IS NOT NULL",
                    "order_by" => array(
                        "nama_peraturan" => "DESC"
                    )
                )
            );
        }

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $row->id_usulan_raperbup,
                    ),
                    "order_by" => array(
                        "created_at" => "DESC"
                    ),
                    "limit" => 1
                ),
                "row"
            );

            if (!$data_terakhir) {
                continue; // Skip jika tidak ada data transaksi
            }

            // Filter belum diperiksa
            if ($filter == "belum") {
                if ($this->session->userdata("level_user_id") == '7') {
                    if (($data_terakhir->status_tracking == "3" && (($data_terakhir->kasubbag_agree_disagree == "1" && $data_terakhir->kabag_agree_disagree == "1") || ($data_terakhir->kasubbag_agree_disagree == "2" && $data_terakhir->kabag_agree_disagree == "1") || (in_array($data_terakhir->kasubbag_agree_disagree, array("1", "2")) && $data_terakhir->kabag_agree_disagree == ""))) || $data_terakhir->status_tracking == "5") {
                        continue;
                    }
                } else {
                    if (($data_terakhir->status_tracking == "2" || $data_terakhir->status_tracking == "3" || $data_terakhir->status_tracking == "5") && $data_terakhir->kabag_agree_disagree != "") {
                        continue;
                    }
                }
            }

            // Filter sudah diperiksa
            if ($filter == "sudah") {
                if ($this->session->userdata("level_user_id") == '7') {
                    if ($data_terakhir->status_tracking == "2" || ($data_terakhir->status_tracking == "3" && (($data_terakhir->kasubbag_agree_disagree == "1" && $data_terakhir->kabag_agree_disagree == "2") || ($data_terakhir->kasubbag_agree_disagree == "2" && $data_terakhir->kabag_agree_disagree == "2"))) || $data_terakhir->status_tracking == "5") {
                        continue;
                    }
                } else {
                    if (($data_terakhir->status_tracking == "2" || $data_terakhir->status_tracking == "3" || $data_terakhir->status_tracking == "5") && $data_terakhir->kabag_agree_disagree == "") {
                        continue;
                    }
                }
            }

            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function check_disposisi()
    {
        $id_usulan_raperbu = decrypt_data($this->iget("id_peraturan"));
        $data_usulan = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => $id_usulan_raperbu,
                    "status_tracking" => "2"
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                )
            ),
            "row"
        );
        if ($data_usulan) {
            $data = true;
        } else {
            $data = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function check_upload_lampiran_provinsi()
    {
        $id_usulan_raperbu = decrypt_data($this->iget("id_peraturan"));
        $data_usulan = $this->trx_raperbup_model->get(
            array(
                "fields" => "trx_raperbup.*,teruskan_provinsi",
                "where" => array(
                    "usulan_raperbup_id" => $id_usulan_raperbu,
                    "status_tracking" => "3"
                ),
                "join" => array(
                    "usulan_raperbup" => "id_usulan_raperbup=usulan_raperbup_id",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "order_by" => array(
                    "created_at" => "DESC"
                ),
                "limit" => "1"
            ),
            "row"
        );

        if ($data_usulan->file_lampiran_provinsi == "" && $data_usulan->teruskan_provinsi == "1" && $data_usulan->kabag_agree_disagree == "1" && $data_usulan->kasubbag_agree_disagree == "1" && ($data_usulan->provinsi_agree_disagree == "2" || !$data_usulan->provinsi_agree_disagree)) {
            $data = true;
        } else {
            $data = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function check_disetujui_tidak_disetujui_kasubbag()
    {
        $id_usulan_raperbup = decrypt_data($this->iget("id_peraturan"));
        $data_usulan = $this->trx_raperbup_model->get(
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

        if ($data_usulan->status_tracking == '2' || $data_usulan->kabag_agree_disagree == '2' || ($data_usulan->status_tracking == '3' && $data_usulan->kabag_agree_disagree == '' && $data_usulan->kasubbag_agree_disagree == '')) {
            $data = true;
        } else {
            $data = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function check_disetujui_tidak_disetujui_kabag()
    {
        $id_usulan_raperbup = decrypt_data($this->iget("id_peraturan"));
        $data_usulan = $this->trx_raperbup_model->get(
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

        if ($data_usulan->status_tracking == '3' && $data_usulan->kasubbag_agree_disagree != '' && $data_usulan->kabag_agree_disagree == '') {
            $data = true;
        } else {
            $data = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_monitoring_raperbup_asisten()
    {
        $skpd = $this->iget("skpd");
        $filter = $this->iget("filter");

        $wh = array();
        if ($skpd) {
            $wh["master_satker_id"] = $skpd;
        }

        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,urutan_legalitas,teruskan_provinsi,nama",
                "left_join" => array(
                    "level_user" => "id_level_user=last_level_user"
                ),
                "join" => array(
                    "user" => "id_user=id_user_created",
                    "master_satker" => "id_master_satker=master_satker_id",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "where" => $wh,
                "order_by" => array(
                    "nama_peraturan" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            if ($row->urutan_legalitas) {
                if ($this->session->userdata("urutan_legalitas") > $row->urutan_legalitas) {
                    continue;
                }
            }
            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $row->id_usulan_raperbup,
                    ),
                    "order_by" => array(
                        "created_at" => "DESC"
                    ),
                    "limit" => 1
                ),
                "row"
            );

            if ($filter == "belum") {
                if ($data_terakhir->status_tracking == "5" || ($data_terakhir->status_tracking == "3" && $data_terakhir->asisten_agree_disagree != "")) {
                    continue;
                }
            }

            if ($filter == "sudah") {
                if ($data_terakhir->status_tracking == "3" && $data_terakhir->asisten_agree_disagree == "") {
                    continue;
                }
            }

            if (($row->teruskan_provinsi == "1" && $data_terakhir->provinsi_agree_disagree == "1") || ($row->teruskan_provinsi != "1" && ($data_terakhir->status_tracking == '3' || $data_terakhir->status_tracking == '5') && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->kasubbag_agree_disagree == '1')) {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }

                $templist[$key]['asisten_agree_disagree'] = $data_terakhir->asisten_agree_disagree ? $data_terakhir->asisten_agree_disagree : '';

                $templist[$key]['status_terakhir'] = "";

                if ($data_terakhir->status_tracking == "5") {
                    $templist[$key]['status_terakhir'] = "Usulan Sudah Dipublish";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Wakil Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Wakil Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Sekretariat Daerah";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Sekretariat Daerah";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Asisten Pemerintahan & Kesra";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Asisten Pemerintahan & Kesra";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1') {
                    if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "1") {
                        $templist[$key]['status_terakhir'] = "Usulan Disetujui Provinsi";
                    } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "2") {
                        $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Provinsi";
                    } else {
                        $templist[$key]['status_terakhir'] = "Usulan Disetujui Kabag Hukum";
                    }
                }

                $templist[$key]['last_status_tracking'] = $data_terakhir->status_tracking;

                $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
                $templist[$key]['file'] = "<button type='button' class='btn btn-primary' href='#view' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View <i class='fas fa-eye'></i></button>";

                $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
            }
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_monitoring_raperbup_sekda()
    {
        $skpd = $this->iget("skpd");
        $filter = $this->iget("filter");

        $wh = array();
        if ($skpd) {
            $wh["master_satker_id"] = $skpd;
        }

        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,urutan_legalitas,nama",
                "left_join" => array(
                    "level_user" => "id_level_user=last_level_user"
                ),
                "join" => array(
                    "user" => "id_user=id_user_created",
                    "master_satker" => "id_master_satker=master_satker_id",
                ),
                "where" => $wh,
                "order_by" => array(
                    "nama_peraturan" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            if ($row->urutan_legalitas) {
                if ($this->session->userdata("urutan_legalitas") > $row->urutan_legalitas) {
                    continue;
                }
            }
            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $row->id_usulan_raperbup,
                    ),
                    "order_by" => array(
                        "created_at" => "DESC"
                    ),
                    "limit" => 1
                ),
                "row"
            );

            if ($filter == "belum") {
                if ($data_terakhir->status_tracking == "5" || ($data_terakhir->status_tracking == "3" && $data_terakhir->sekda_agree_disagree != "")) {
                    continue;
                }
            }

            if ($filter == "sudah") {
                if ($data_terakhir->status_tracking == "3" && $data_terakhir->sekda_agree_disagree == "") {
                    continue;
                }
            }

            if (($data_terakhir->status_tracking == '3' || $data_terakhir->status_tracking == '5') && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1') {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }

                $templist[$key]['sekda_agree_disagree'] = $data_terakhir->sekda_agree_disagree ? $data_terakhir->sekda_agree_disagree : '';

                $templist[$key]['status_terakhir'] = "";

                if ($data_terakhir->status_tracking == "5") {
                    $templist[$key]['status_terakhir'] = "Usulan Sudah Dipublish";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Wakil Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Wakil Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Sekretariat Daerah";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Sekretariat Daerah";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Asisten Pemerintahan & Kesra";
                }

                $templist[$key]['last_status_tracking'] = $data_terakhir->status_tracking;

                $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
                $templist[$key]['file'] = "<a href='#view' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View<i class='fas fa-eye'></i></a>";

                $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
            }
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_monitoring_raperbup_wabup()
    {
        $skpd = $this->iget("skpd");
        $filter = $this->iget("filter");

        $wh = array();
        if ($skpd) {
            $wh["master_satker_id"] = $skpd;
        }

        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,urutan_legalitas,nama",
                "left_join" => array(
                    "level_user" => "id_level_user=last_level_user"
                ),
                "join" => array(
                    "user" => "id_user=id_user_created",
                    "master_satker" => "id_master_satker=master_satker_id",
                ),
                "where" => $wh,
                "order_by" => array(
                    "nama_peraturan" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            if ($row->urutan_legalitas) {
                if ($this->session->userdata("urutan_legalitas") > $row->urutan_legalitas) {
                    continue;
                }
            }

            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $row->id_usulan_raperbup,
                    ),
                    "order_by" => array(
                        "created_at" => "DESC"
                    ),
                    "limit" => 1
                ),
                "row"
            );

            if ($filter == "belum") {
                if ($data_terakhir->status_tracking == "5" || ($data_terakhir->status_tracking == "3" && $data_terakhir->wabup_agree_disagree != "")) {
                    continue;
                }
            }

            if ($filter == "sudah") {
                if ($data_terakhir->status_tracking == "3" && $data_terakhir->wabup_agree_disagree == "") {
                    continue;
                }
            }

            if (($data_terakhir->status_tracking == '3' || $data_terakhir->status_tracking == '5') && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1') {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }

                $templist[$key]['wabup_agree_disagree'] = $data_terakhir->wabup_agree_disagree ? $data_terakhir->wabup_agree_disagree : '';

                $templist[$key]['status_terakhir'] = "";

                if ($data_terakhir->status_tracking == "5") {
                    $templist[$key]['status_terakhir'] = "Usulan Sudah Dipublish";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Wakil Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '2') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Wakil Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Sekretariat Daerah";
                }

                $templist[$key]['last_status_tracking'] = $data_terakhir->status_tracking;

                $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
                $templist[$key]['file'] = "<a href='#view' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View<i class='fas fa-eye'></i></a>";

                $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
            }
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_monitoring_raperbup_bupati()
    {
        $skpd = $this->iget("skpd");
        $filter = $this->iget("filter");

        $wh = array();
        if ($skpd) {
            $wh["master_satker_id"] = $skpd;
        }

        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,urutan_legalitas,nama",
                "left_join" => array(
                    "level_user" => "id_level_user=last_level_user"
                ),
                "join" => array(
                    "user" => "id_user=id_user_created",
                    "master_satker" => "id_master_satker=master_satker_id",
                ),
                "where" => $wh,
                "order_by" => array(
                    "nama_peraturan" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            if ($row->urutan_legalitas) {
                if ($this->session->userdata("urutan_legalitas") > $row->urutan_legalitas) {
                    continue;
                }
            }

            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $row->id_usulan_raperbup,
                    ),
                    "order_by" => array(
                        "created_at" => "DESC"
                    ),
                    "limit" => 1
                ),
                "row"
            );

            if ($filter == "belum") {
                if ($data_terakhir->status_tracking == "5" || ($data_terakhir->status_tracking == "3" && $data_terakhir->bupati_agree_disagree != "")) {
                    continue;
                }
            }

            if ($filter == "sudah") {
                if ($data_terakhir->status_tracking == "3" && $data_terakhir->bupati_agree_disagree == "") {
                    continue;
                }
            }

            if (($data_terakhir->status_tracking == '3' || $data_terakhir->status_tracking == '5') && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }

                $templist[$key]['bupati_agree_disagree'] = $data_terakhir->bupati_agree_disagree ? $data_terakhir->bupati_agree_disagree : '';

                $templist[$key]['status_terakhir'] = "";

                if ($data_terakhir->status_tracking == "5") {
                    $templist[$key]['status_terakhir'] = "Usulan Sudah Dipublish";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Tidak Disetujui Bupati";
                } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                    $templist[$key]['status_terakhir'] = "Usulan Disetujui Wakil Bupati";
                }

                $templist[$key]['last_status_tracking'] = $data_terakhir->status_tracking;

                $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
                $templist[$key]['file'] = "<a href='#view' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View<i class='fas fa-eye'></i></a>";

                $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
            }
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_monitoring_kalteng()
    {
        $skpd = $this->iget("skpd");

        $wh = array();
        if ($skpd) {
            $wh["master_satker_id"] = $skpd;
        }

        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,urutan_legalitas,teruskan_provinsi",
                "left_join" => array(
                    "level_user" => "id_level_user=last_level_user"
                ),
                "join" => array(
                    "user" => "id_user=id_user_created",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "where" => $wh,
                "order_by" => array(
                    "nama_peraturan" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            if ($row->urutan_legalitas) {
                if ($this->session->userdata("urutan_legalitas") > $row->urutan_legalitas) {
                    continue;
                }
            }
            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $row->id_usulan_raperbup,
                    ),
                    "order_by" => array(
                        "created_at" => "DESC"
                    ),
                    "limit" => 1
                ),
                "row"
            );

            if (($data_terakhir->status_tracking == '3' || $data_terakhir->status_tracking == '5') && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->kasubbag_agree_disagree == '1' && $row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "") {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }

                $templist[$key]['id_encrypt_trx_raperbup'] = encrypt_data($data_terakhir->id_trx_raperbup);
                $templist[$key]['provinsi_agree_disagree'] = $data_terakhir->provinsi_agree_disagree ? $data_terakhir->provinsi_agree_disagree : '';
                $templist[$key]['asisten_agree_disagree'] = $data_terakhir->asisten_agree_disagree ? $data_terakhir->asisten_agree_disagree : '';

                $templist[$key]['last_status_tracking'] = $data_terakhir->status_tracking;

                $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
                $templist[$key]['file'] = "<a href='#view' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View<i class='fas fa-eye'></i></a>";

                $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
            }
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_last_file()
    {
        $id_usulan_raperbup = decrypt_data($this->iget("id_peraturan"));
        $data_terakhir = $this->trx_raperbup_model->get(
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

        $data_usulan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup);

        if ($data_usulan->lampiran) {
            $file_extension = explode(".", $data_usulan->lampiran);
            $lampiran = base_url() . $this->config->item("file_lampiran") . "/" . $data_usulan->lampiran;
            $link_lampiran = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran . "','" . $file_extension[1] . "')\">Kepala Dinas</a>";
        }

        if ($data_usulan->lampiran_sk_tim) {
            $file_extension_sk_tim = explode(".", $data_usulan->lampiran_sk_tim);
            $lampiran_sk_tim = base_url() . $this->config->item("file_lampiran") . "/" . $data_usulan->lampiran_sk_tim;
            $link_lampiran_sk_tim = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_sk_tim . "','" . $file_extension_sk_tim[1] . "')\">SK Tim</a>";
        }

        if ($data_usulan->lampiran_daftar_hadir) {
            $file_extension_daftar_hadir = explode(".", $data_usulan->lampiran_daftar_hadir);
            $lampiran_daftar_hadir = base_url() . $this->config->item("file_lampiran") . "/" . $data_usulan->lampiran_daftar_hadir;
            $link_lampiran_daftar_hadir = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_daftar_hadir . "','" . $file_extension_daftar_hadir[1] . "')\">Daftar Hadir</a>";
        }

        $lampiran_group = "";
        $lampiran_group .= "<div class='dropdown d-inline mr-2'>
        <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          Lampiran
        </button>
        <div class='dropdown-menu'>";

        if (in_array($data_usulan->kategori_usulan_id, array("1", "2"))) {
            if ($data_usulan->lampiran) {
                $lampiran_group .= $link_lampiran;
            }

            if ($data_usulan->lampiran_sk_tim) {
                $lampiran_group .= $link_lampiran_sk_tim;
            }

            if ($data_usulan->lampiran_daftar_hadir) {
                $lampiran_group .= $link_lampiran_daftar_hadir;
            }
        } else {
            $lampiran_group .= $link_lampiran;
        }

        $lampiran_group .= "</div></div>";

        $data['lampiran_group'] = $lampiran_group;

        $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
        $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
        $data['usulan'] = "<button  type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</button>";

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_kasubbag()
    {
        $data_kasubbag = $this->user_model->get(
            array(
                "where" => array(
                    "level_user_id" => "7"
                ),
                "order_by" => array(
                    "nama_lengkap" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_kasubbag as $key => $row) {

            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_user);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_data_level_user()
    {
        $data_level_user = $this->level_user_model->get(
            array(
                "where_false" => "id_level_user IN ('8','9','10','11')",
            )
        );

        $templist = array();
        foreach ($data_level_user as $key => $row) {

            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_level_user);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function batas_akhir_persetujuan()
    {
        $id_peraturan = decrypt_data($this->iget("id_peraturan"));
        $data = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,nama_level_user",
                "left_join" => array(
                    "level_user" => "id_level_user=last_level_user"
                ),
                "where" => array(
                    "id_usulan_raperbup" => $id_peraturan
                )
            ),
            "row"
        );
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function check_publish_up()
    {
        $id_usulan_raperbup = decrypt_data($this->iget("id_peraturan"));
        $data_usulan = $this->trx_raperbup_model->get(
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

        if ($data_usulan->bupati_agree_disagree) {
            if ($data_usulan->status_tracking == '3' && $data_usulan->bupati_agree_disagree == '1') {
                $data = true;
            } else {
                $data = false;
            }
        } else if ($data_usulan->wabup_agree_disagree) {
            if ($data_usulan->status_tracking == '3' && $data_usulan->wabup_agree_disagree == '1') {
                $data = true;
            } else {
                $data = false;
            }
        } else if ($data_usulan->sekda_agree_disagree) {
            if ($data_usulan->status_tracking == '3' && $data_usulan->sekda_agree_disagree == '1') {
                $data = true;
            } else {
                $data = false;
            }
        } else if ($data_usulan->asisten_agree_disagree) {
            if ($data_usulan->status_tracking == '3' && $data_usulan->asisten_agree_disagree == '1') {
                $data = true;
            } else {
                $data = false;
            }
        } else {
            $data = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function check_raise_usulan()
    {
        $id_usulan_raperbup = decrypt_data($this->iget("id_usulan_raperbup"));
        $data_usulan = $this->trx_raperbup_model->get(
            array(
                "fields" => "trx_raperbup.*,teruskan_provinsi",
                "where" => array(
                    "usulan_raperbup_id" => $id_usulan_raperbup,
                ),
                "join" => array(
                    "usulan_raperbup" => "id_usulan_raperbup=usulan_raperbup_id",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "order_by" => array(
                    "id_trx_raperbup" => "DESC"
                ),
                "limit" => 1
            ),
            "row"
        );

        $data['kabupaten'] = false;
        $data['provinsi'] = false;

        if ($data_usulan->kasubbag_agree_disagree == "1" || $data_usulan->kabag_agree_disagree == "1" && $data_usulan->teruskan_provinsi == "1") {
            $data['kabupaten'] = false;
            $data['provinsi'] = false;
        } else {
            if ($data_usulan->kasubbag_agree_disagree == "1" || $data_usulan->kabag_agree_disagree == "1") {
                $data['kabupaten'] = true;
                $data['provinsi'] = false;
            } else {
                $data['kabupaten'] = false;
                $data['provinsi'] = false;
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    function get_trx_raperbup()
    {
        $id = $this->iget("id");

        $data = $this->trx_raperbup_model->get_by($id);
        $expl_created_at = explode(" ", $data->created_at);
        $tanggal = explode("-", $expl_created_at[0]);
        $data->created_at = $tanggal[2] . "-" . $tanggal[1] . "-" . $tanggal[0] . " " . $expl_created_at[1];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
