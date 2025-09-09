<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('usulan_raperbup/usulan_raperbup_model', 'usulan_raperbup_model');
        $this->load->model('monitoring_raperbup/trx_raperbup_model', 'trx_raperbup_model');
    }

    public function get_data_usulan_raperbup()
    {
        $skpd = $this->iget("skpd");

        $wh = array();
        if ($skpd) {
            $wh["b.master_satker_id"] = $skpd;
        }

        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "fields" => "usulan_raperbup.*,a.keterangan,teruskan_provinsi,nama",
                "left_join" => array(
                    "user AS a" => "a.id_user=id_user_kasubbag",
                    "user AS b" => "b.id_user=usulan_raperbup.id_user_created",
                    "master_satker" => "id_master_satker=b.master_satker_id",
                ),
                "join" => array(
                    "kategori_usulan" => "id_kategori_usulan=kategori_usulan_id",
                ),
                "where" => $wh,
                "order_by" => array(
                    "nama_peraturan" => "ASC"
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
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Sudah Di Publish</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1' && $data_terakhir->bupati_agree_disagree == '2') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1' && $data_terakhir->wabup_agree_disagree == '2') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1' && $data_terakhir->sekda_agree_disagree == '2') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '1') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1' && $data_terakhir->asisten_agree_disagree == '2') {
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '1' && $data_terakhir->kabag_agree_disagree == '1') {
                //kasubag setuju dan kabag menyetujui
                if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "1") {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Provinsi</div>";
                } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "2") {
                    $file = "";
                    if ($data_terakhir->file_catatan_perbaikan) {
                        $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                        $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                        $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $file_extension[1] . "')\">View</butto>";
                    }

                    $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui Provinsi</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "") {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success text-left'>Lampiran untuk Provinsi, <br />sudah di upload oleh " . $data_terakhir->nama_lengkap . " & sudah dikirim ke Admin Provinsi</div>";
                } else {
                    $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . $data_terakhir->nama_lengkap . "</div>";
                }
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree == '2' && $data_terakhir->kabag_agree_disagree == '1') {
                //kasubag tidak setuju dan kabag menyetujui
                $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . $data_terakhir->nama_lengkap . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree != '' && $data_terakhir->kabag_agree_disagree == '2') {
                //proses kasubag dan kabag hukum
                $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . $row->keterangan . "</div>";
            } else if ($data_terakhir->status_tracking == "3" && $data_terakhir->kasubbag_agree_disagree != '' && $data_terakhir->kabag_agree_disagree == '') {
                //proses kasubag dan kabag hukum
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

            $file_extension = explode(".", $data_terakhir->file_usulan_raperbup);
            $usulan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup;
            $templist[$key]['file'] = "<button type='button' class='btn btn-primary' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . $file_extension[1] . "')\">View</button>";

            if ($row->lampiran) {
                $file_extension = explode(".", $row->lampiran);
                $lampiran = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran;
                $link_lampiran = "<a  href='#viewdetail' class='dropdown-item' class='btn btn-primary' href='#view' onclick=\"view_detail('" . $lampiran . "','" . $file_extension[1] . "')\">Kepala Dinas</a>";
            }

            if ($row->lampiran_sk_tim) {
                $file_extension_sk_tim = explode(".", $row->lampiran_sk_tim);
                $lampiran_sk_tim = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran_sk_tim;
                $link_lampiran_sk_tim = "<a  href='#viewdetail' class='dropdown-item' class='btn btn-primary' href='#view' onclick=\"view_detail('" . $lampiran_sk_tim . "','" . $file_extension_sk_tim[1] . "')\">SK Tim</a>";
            }

            if ($row->lampiran_daftar_hadir) {
                $file_extension_daftar_hadir = explode(".", $row->lampiran_daftar_hadir);
                $lampiran_daftar_hadir = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran_daftar_hadir;
                $link_lampiran_daftar_hadir = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_daftar_hadir . "','" . $file_extension_daftar_hadir[1] . "')\">Daftar Hadir</a>";
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
                $lampiran_group .= $link_lampiran;
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

    public function get_data_usulan_raperbup_by_id()
    {
        $data['usulan_raperbup'] = $this->usulan_raperbup_model->get_by(decrypt_data($this->iget("id_usulan_raperbup")));

        $data['trx_raperbup'] = $this->trx_raperbup_model->get(
            array(
                "where" => array(
                    "usulan_raperbup_id" => decrypt_data($this->iget("id_usulan_raperbup")),
                    "status_tracking" => "2",
                ),
            ),
            "row"
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
