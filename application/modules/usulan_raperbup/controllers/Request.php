<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('usulan_raperbup_model');
        $this->load->model('monitoring_raperbup/trx_raperbup_model', 'trx_raperbup_model');
    }

    public function get_data_usulan_raperbup()
    {
        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,keterangan,nama_kategori,teruskan_provinsi",
                "join" => array(
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id"
                ),
                "left_join" => array(
                    "user" => "id_user=id_user_kasubbag",
                ),
                "where" => array(
                    "id_user_created" => $this->session->userdata("id_user")
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }

            $data_terakhir = $this->trx_raperbup_model->get(
                array(
                    "fields" => "trx_raperbup.*,nama_lengkap",
                    "join" => array(
                        "user" => "id_user_created=id_user"
                    ),
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

            $templist[$key]['status_terakhir'] = "";
            if ($data_terakhir->status_tracking == "5") {
                // Usulan sudah final
                $templist[$key]['status_terakhir'] = "<div class='badge badge-primary'>Usulan Telah Dipublish</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                // Disetujui Bupati
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '2') {
                // Tidak disetujui Bupati
                $file = "";
                if ($data_terakhir->file_catatan_perbaikan) {
                    $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                    $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $extension . "')\">View</button>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                //disetujui wabup
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Wakil " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '2') {
                //tidak disetujui wabup
                $file = "";
                if ($data_terakhir->file_catatan_perbaikan) {
                    $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                    $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $extension . "')\">View</button>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1') {
                //disetujui sekda
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '2') {
                //tidak disetujui sekda
                $file = "";
                if ($data_terakhir->file_catatan_perbaikan) {
                    $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                    $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $extension . "')\">View</button>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1') {
                //disetujui kesra
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '2') {
                //tidak disetujui kesra
                $file = "";
                if ($data_terakhir->file_catatan_perbaikan) {
                    $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                    $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $extension . "')\">View</button>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1') {
                //kasubag setuju dan kabag menyetujui
                if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "1") {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Provinsi</div>";
                } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "2") {
                    $file = "";
                    if ($data_terakhir->file_catatan_perbaikan) {
                        $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                        $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $extension . "')\">View</button>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui Provinsi</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "") {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success text-left'>Lampiran untuk Provinsi, <br />sudah di upload oleh " . $data_terakhir->nama_lengkap . " & sudah dikirim ke Admin Provinsi</div>";
                } else {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
                }
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '2' && $data_terakhir->kabag_agree_disagree == '1') {
                //kasubag tidak setuju dan kabag menyetujui
                $file = "";
                if ($data_terakhir->file_catatan_perbaikan) {
                    $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                    $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                    $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $extension . "')\">View</button>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>" . ($data_terakhir->catatan_ditolak ? "Catatan :<br />" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<div class='mt-3'>File perbaikan : " . $file . "</div>" : "");
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '2' && $data_terakhir->kabag_agree_disagree == '') {
                //kasubag tidak setuju dan kabag menyetujui
                $file = "";
                if ($data_terakhir->file_catatan_perbaikan) {
                    $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                    $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                    $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $extension . "')\">View</button>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>" . ($data_terakhir->catatan_ditolak ? "Catatan :<br />" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<div class='mt-3'>File perbaikan : " . $file . "</div>" : "");
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree != '' && $data_terakhir->kabag_agree_disagree == '2') {
                //proses antara kasubag dan kabag hukum
                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . $row->keterangan . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree != '' && $data_terakhir->kabag_agree_disagree == '') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . $row->keterangan . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '' && $data_terakhir->kabag_agree_disagree == '') {
                //usulan perbaikan
                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Usulan Perbaikan</div>";
            } else if ($data_terakhir->status_tracking == "2") {
                //disposisi
                $templist[$key]['status_terakhir'] = "<div class='badge badge-info'>Diteruskan ke " . $row->keterangan . "</div>";
            } else if ($data_terakhir->status_tracking == "1") {
                //usulan baru
                $templist[$key]['status_terakhir'] = "<div class='badge badge-light'>Usulan Baru</div>";
            }

            $templist[$key]['file_usulan'] = "";
            if ($data_terakhir->file_usulan_raperbup) {
                $file_usulan_extension = explode(".", $data_terakhir->file_usulan_raperbup);
                $extension = (count($file_usulan_extension) > 1) ? $file_usulan_extension[1] : 'pdf';
                $usulan_url = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
                $templist[$key]['file_usulan'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan_url . "','" . $extension . "')\">View</button>";
            }

            $templist[$key]['file'] = "";
            if ($data_terakhir->status_tracking == "5" && $data_terakhir->file_final) {
                $file_extension = explode(".", $data_terakhir->file_final);
                $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                $usulan = base_url() . $this->config->item("file_final") . "/" . $data_terakhir->file_final;
                $templist[$key]['file'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $extension . "')\">View</button>";
            }

            // Initialize variables
            $link_lampiran = "";
            $link_lampiran_sk_tim = "";
            $link_lampiran_daftar_hadir = "";

            if ($row->lampiran) {
                $file_extension = explode(".", $row->lampiran);
                $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                $lampiran = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran;
                $link_lampiran = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran . "','" . $extension . "')\">Kepala Dinas</a>";
            }

            if ($row->lampiran_sk_tim) {
                $file_extension_sk_tim = explode(".", $row->lampiran_sk_tim);
                $extension = (count($file_extension_sk_tim) > 1) ? $file_extension_sk_tim[1] : 'pdf';
                $lampiran_sk_tim = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran_sk_tim;
                $link_lampiran_sk_tim = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_sk_tim . "','" . $extension . "')\">SK Tim</a>";
            }

            if ($row->lampiran_daftar_hadir) {
                $file_extension_daftar_hadir = explode(".", $row->lampiran_daftar_hadir);
                $extension = (count($file_extension_daftar_hadir) > 1) ? $file_extension_daftar_hadir[1] : 'pdf';
                $lampiran_daftar_hadir = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran_daftar_hadir;
                $link_lampiran_daftar_hadir = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_daftar_hadir . "','" . $extension . "')\">Daftar Hadir</a>";
            }

            $lampiran_group = "";
            $lampiran_group .= "<div class='dropdown d-inline mr-2'>
        <button class='btn btn-info dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          Lampiran
        </button>
        <div class='dropdown-menu'>";

            if (in_array($row->kategori_usulan_id, array("1", "2"))) {
                if ($row->lampiran) {
                    $lampiran_group .= $link_lampiran;
                }

                if ($row->lampiran_sk_tim) {
                    $lampiran_group .= $link_lampiran_sk_tim;
                }

                if ($row->lampiran_daftar_hadir) {
                    $lampiran_group .= $link_lampiran_daftar_hadir;
                }
            } else {
                if ($row->lampiran) {
                    $lampiran_group .= $link_lampiran;
                }
            }

            $lampiran_group .= "</div></div>";

            $templist[$key]['lampiran_group'] = $lampiran_group;

            $templist[$key]['id_encrypt'] = encrypt_data($row->id_usulan_raperbup);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_detail_usulan_raperbup()
    {
        $id_usulan_raperbup = $this->iget("id_usulan_raperbup");
        $data_usulan = $this->trx_raperbup_model->get(
            array(
                "fields" => "trx_raperbup.*,
                DATE_FORMAT(trx_raperbup.created_at,'%Y-%m-%d') AS tanggal_custom,
                DATE_FORMAT(trx_raperbup.created_at,'%H-%i-%s') AS time_custom,
                nama_level_user,
                a.keterangan,
                b.nama_lengkap,
                teruskan_provinsi",
                "join" => array(
                    "level_user" => "id_level_user=level_user_id_status",
                    "usulan_raperbup" => "id_usulan_raperbup=usulan_raperbup_id",
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "left_join" => array(
                    "user AS a" => "a.id_user=id_user_kasubbag",
                    "user AS b" => "b.id_user=trx_raperbup.id_user_created",
                ),
                "where" => array(
                    "usulan_raperbup_id" => decrypt_data($id_usulan_raperbup)
                ),
                "order_by" => array(
                    "id_trx_raperbup" => "DESC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['tanggal_custom'] = longdate_indo($row->tanggal_custom) . " " . $row->time_custom;
            if ($row->status_tracking == "5") {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Sudah Di Publish</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '1' && $row->bupati_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '1' && $row->bupati_agree_disagree == '2') {
                $file = "";
                if ($row->file_catatan_perbaikan) {
                    $file_extension = explode(".", $row->file_catatan_perbaikan);
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1' && $row->wabup_agree_disagree == '2') {
                $file = "";
                if ($row->file_catatan_perbaikan) {
                    $file_extension = explode(".", $row->file_catatan_perbaikan);
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1' && $row->sekda_agree_disagree == '2') {
                $file = "";
                if ($row->file_catatan_perbaikan) {
                    $file_extension = explode(".", $row->file_catatan_perbaikan);
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1' && $row->asisten_agree_disagree == '2') {
                $file = "";
                if ($row->file_catatan_perbaikan) {
                    $file_extension = explode(".", $row->file_catatan_perbaikan);
                    $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                    $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                }

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '1') {
                //kasubag setuju dan kabag menyetujuinya
                if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi != "" && $row->provinsi_agree_disagree == "1") {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Provinsi</div>";
                } else if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi != "" && $row->provinsi_agree_disagree == "2") {
                    $file = "";
                    if ($row->file_catatan_perbaikan) {
                        $file_extension = explode(".", $row->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui Provinsi</div>" . ($row->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->teruskan_provinsi == "1" && $row->file_lampiran_provinsi != "") {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Lampiran untuk Provinsi, sudah di upload oleh " . $row->nama_lengkap . " & sudah dikirim ke Admin Provinsi</div>";
                } else {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $row->nama_lengkap . "</div>";
                }
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '' && $row->kabag_agree_disagree == '') {
                // Proses antara kasubag dan kabag hukum
                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . $row->keterangan . "</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '2' && $row->kabag_agree_disagree == '') {
                // Kasubag tidak setuju dan kabag belum memberikan persetujuan
                $file_extension = explode(".", $row->file_catatan_perbaikan);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_catatan_perbaikan;
                $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</button>";

                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<br />Catatan :<br />" . nl2br($row->catatan_ditolak) : "") . ($row->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree == '1' && $row->kabag_agree_disagree == '2') {
                // Usulan ditangguhkan oleh Bagian Hukum setelah disetujui oleh Kasubbag
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Ditangguhkan oleh Bagian Hukum " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<br />Catatan :<br />" . nl2br($row->catatan_ditolak) : "");
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree != '' && $row->kabag_agree_disagree == '') {
                // Sedang diproses oleh kasubbag
                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . $row->keterangan . "</div>";
            } else if ($row->status_tracking == "3" && $row->kasubbag_agree_disagree != '' && $row->kabag_agree_disagree == '2') {
                // Usulan ditolak oleh Kabag Hukum setelah disetujui oleh Kasubbag
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Ditangguhkan oleh Bagian Hukum " . $row->nama_lengkap . "</div>" . ($row->catatan_ditolak ? "<br />Catatan :<br />" . nl2br($row->catatan_ditolak) : "");

                //usulan perbaikan
                $file_extension = explode(".", $row->file_perbaikan);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_perbaikan;
                $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</button>";

                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning mb-3'>Usulan Perbaikan</div> <div>File : " . $file . "</div>";
            } else if ($row->status_tracking == "2") {
                //disposisi
                $templist[$key]['status_terakhir'] = "<div class='badge badge-info'>Diteruskan ke " . $row->keterangan . "</div>";
            } else if ($row->status_tracking == "1") {
                //usulan baru
                $file_extension = explode(".", $row->file_usulan_raperbup);
                $usulan = base_url() . $this->config->item("file_usulan") . "/" . $row->file_usulan_raperbup;
                $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</button>";

                $templist[$key]['status_terakhir'] = "<div class='badge badge-light mb-3'>Usulan Baru</div><br /> File : " . $file;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_trx_raperbup);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_file_lampiran()
    {
        $id_usulan_raperbup = $this->iget("id_usulan_raperbup");
        $data_usulan = $this->usulan_raperbup_model->get_by(decrypt_data($id_usulan_raperbup));

        if ($data_usulan->lampiran) {
            $file_extension = explode(".", $data_usulan->lampiran);
            $lampiran = base_url() . $this->config->item("file_lampiran") . "/" . $data_usulan->lampiran;
            $data['lampiran_kepala_dinas'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $lampiran . "','" . $file_extension[1] . "')\">View</button>";
        }

        $data['lampiran_sk_tim'] = "";
        if ($data_usulan->lampiran_sk_tim) {
            $file_extension_sk_tim = explode(".", $data_usulan->lampiran_sk_tim);
            $lampiran_sk_tim = base_url() . $this->config->item("file_lampiran") . "/" . $data_usulan->lampiran_sk_tim;
            $data['lampiran_sk_tim'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $lampiran_sk_tim . "','" . $file_extension_sk_tim[1] . "')\">View</button>";
        }

        $data['lampiran_daftar_hadir'] = "";
        if ($data_usulan->lampiran_daftar_hadir) {
            $file_extension_daftar_hadir = explode(".", $data_usulan->lampiran_daftar_hadir);
            $lampiran_daftar_hadir = base_url() . $this->config->item("file_lampiran") . "/" . $data_usulan->lampiran_daftar_hadir;
            $data['lampiran_daftar_hadir'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $lampiran_daftar_hadir . "','" . $file_extension_daftar_hadir[1] . "')\">View</button>";
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
