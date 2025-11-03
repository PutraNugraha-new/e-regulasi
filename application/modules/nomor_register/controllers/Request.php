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
        try {
            $skpd = $this->iget("skpd");
            $has_nomor_register = $this->iget("has_nomor_register");
            $sort_order = $this->iget("sort_order") ? strtoupper($this->iget("sort_order")) : 'DESC';
            $status = $this->iget("status");
            $tahun = $this->iget("tahun");
            $tipe = $this->iget("tipe");

            $wh = array();
            if ($skpd) {
                $wh["b.master_satker_id"] = $skpd;
            }
            if ($tipe) {
                $wh["usulan_raperbup.kategori_usulan_id"] = $tipe;
            }
            if ($tahun) {
                $wh["YEAR(usulan_raperbup.created_at)"] = $tahun;
            }
            if ($has_nomor_register !== '') {
                if ($has_nomor_register == '1') {
                    $wh["usulan_raperbup.nomor_register IS NOT NULL"] = NULL;
                } elseif ($has_nomor_register == '0') {
                    $wh["usulan_raperbup.nomor_register IS NULL"] = NULL;
                }
            }

            // Subquery untuk status terakhir
            $subquery = "(SELECT usulan_raperbup_id, status_tracking
                      FROM trx_raperbup
                      WHERE created_at = (SELECT MAX(created_at) FROM trx_raperbup AS sub_trx WHERE sub_trx.usulan_raperbup_id = trx_raperbup.usulan_raperbup_id)
                     ) AS t";
            $this->db->join($subquery, 'usulan_raperbup.id_usulan_raperbup = t.usulan_raperbup_id', 'left');
            if ($status) {
                $wh["t.status_tracking"] = $status;
            }

            // Query dengan ORDER BY menggunakan raw expression
            $this->db->select("usulan_raperbup.*, a.keterangan, kategori_usulan.teruskan_provinsi, b.nama_lengkap AS nama");
            $this->db->from('usulan_raperbup');
            $this->db->join('user AS a', 'a.id_user = usulan_raperbup.id_user_kasubbag', 'left');
            $this->db->join('user AS b', 'b.id_user = usulan_raperbup.id_user_created', 'left');
            $this->db->join('master_satker', 'master_satker.id_master_satker = b.master_satker_id', 'left');
            $this->db->join('kategori_usulan', 'kategori_usulan.id_kategori_usulan = usulan_raperbup.kategori_usulan_id', 'left');
            $this->db->where($wh);
            $this->db->where('usulan_raperbup.deleted_at IS NULL');
            // Nonaktifkan escaping untuk ORDER BY
            $this->db->order_by("CASE WHEN usulan_raperbup.nomor_register IS NULL THEN 1 ELSE 0 END ASC", NULL, FALSE);
            $this->db->order_by("usulan_raperbup.nomor_register $sort_order", NULL, FALSE);

            $data_usulan = $this->db->get()->result();

            // Debug: Log hasil query
            log_message('debug', 'Last Query: ' . $this->db->last_query());
            log_message('debug', 'Data Usulan: ' . json_encode($data_usulan));

            $templist = array();
            foreach ($data_usulan as $key => $row) {
                foreach ($row as $keys => $rows) {
                    $templist[$key][$keys] = $rows;
                }

                $data_terakhir = $this->trx_raperbup_model->get(
                    array(
                        "fields" => "trx_raperbup.*, nama_lengkap",
                        "join" => array(
                            "user" => "id_user_created = id_user"
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

                // Jika tidak ada data transaksi, set status default
                $templist[$key]['status_terakhir'] = $data_terakhir ? "" : "<div class='badge badge-light'>Belum Ada Transaksi</div>";

                if ($data_terakhir) {
                    // ===== STATUS 5: PUBLISH =====
                    if ($data_terakhir->status_tracking == "5") {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Sudah Di Publish</div>";
                    }
                    // ===== STATUS 6: DIBATALKAN =====
                    else if ($data_terakhir->status_tracking == "6") {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-secondary'>Usulan Dibatalkan</div>";
                    }

                    // ===== BUPATI SETUJU (LENGKAP DENGAN JFT) =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1' &&
                        $data_terakhir->sekda_agree_disagree == '1' &&
                        $data_terakhir->wabup_agree_disagree == '1' &&
                        $data_terakhir->bupati_agree_disagree == '1'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== BUPATI TIDAK SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1' &&
                        $data_terakhir->sekda_agree_disagree == '1' &&
                        $data_terakhir->wabup_agree_disagree == '1' &&
                        $data_terakhir->bupati_agree_disagree == '2'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== WABUP SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1' &&
                        $data_terakhir->sekda_agree_disagree == '1' &&
                        $data_terakhir->wabup_agree_disagree == '1'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== WABUP TIDAK SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1' &&
                        $data_terakhir->sekda_agree_disagree == '1' &&
                        $data_terakhir->wabup_agree_disagree == '2'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== SEKDA SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1' &&
                        $data_terakhir->sekda_agree_disagree == '1'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== SEKDA TIDAK SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1' &&
                        $data_terakhir->sekda_agree_disagree == '2'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== ASISTEN/KESRA SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '1'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== ASISTEN/KESRA TIDAK SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        $data_terakhir->asisten_agree_disagree == '2'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== KABAG SETUJU (DENGAN JFT) =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1'
                    ) {
                        if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "1") {
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Provinsi</div>";
                        } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "2") {
                            $file = "";
                            if ($data_terakhir->file_catatan_perbaikan) {
                                $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                                $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                                $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . ($file_extension[1] ?? 'pdf') . "')\">View</button>";
                            }
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui Provinsi</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                        } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "") {
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-success text-left'>Lampiran untuk Provinsi, <br />sudah di upload oleh " . ($data_terakhir->nama_lengkap ?? 'Unknown') . " & sudah dikirim ke Admin Provinsi</div>";
                        } else {
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                        }
                    }

                    // ===== KABAG SETUJU (TANPA JFT - backward compatibility) =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '1' &&
                        ($data_terakhir->jft_agree_disagree == '' || !isset($data_terakhir->jft_agree_disagree))
                    ) {
                        if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "1") {
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui Provinsi</div>";
                        } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "" && $data_terakhir->provinsi_agree_disagree == "2") {
                            $file = "";
                            if ($data_terakhir->file_catatan_perbaikan) {
                                $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                                $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                                $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . ($file_extension[1] ?? 'pdf') . "')\">View</button>";
                            }
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Tidak Disetujui Provinsi</div>" . ($data_terakhir->catatan_ditolak ? "<div>Catatan :</div>" . nl2br($data_terakhir->catatan_ditolak) : "") . ($data_terakhir->file_catatan_perbaikan ? "<br /><br />File Catatan Perbaikan : " . $file : "");
                        } else if ($row->teruskan_provinsi == "1" && $data_terakhir->file_lampiran_provinsi != "") {
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-success text-left'>Lampiran untuk Provinsi, <br />sudah di upload oleh " . ($data_terakhir->nama_lengkap ?? 'Unknown') . " & sudah dikirim ke Admin Provinsi</div>";
                        } else {
                            $templist[$key]['status_terakhir'] = "<div class='badge badge-success'>Usulan Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                        }
                    }

                    // ===== KASUBBAG TIDAK SETUJU TAPI KABAG SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '2' &&
                        $data_terakhir->kabag_agree_disagree == '1'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui " . ($data_terakhir->nama_lengkap ?? 'Unknown') . "</div>";
                    }

                    // ===== JFT SETUJU, MENUNGGU KABAG =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == ''
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-info'>Disetujui JFT, menunggu review Kabag</div>";
                    }

                    // ===== JFT TIDAK SETUJU =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '2'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger'>Usulan Tidak Disetujui JFT</div>";
                    }

                    // ===== KASUBBAG SETUJU, MENUNGGU JFT =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '' &&
                        $data_terakhir->kabag_agree_disagree == ''
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-info'>Disetujui Kasubbag, menunggu review JFT</div>";
                    }

                    // ===== KABAG TOLAK (DENGAN JFT) =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '2'
                    ) {
                        $file = "";
                        if ($data_terakhir->file_catatan_perbaikan) {
                            $file_extension = explode(".", $data_terakhir->file_catatan_perbaikan);
                            $extension = (count($file_extension) > 1) ? $file_extension[1] : 'pdf';
                            $perbaikan = base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_catatan_perbaikan;
                            $file = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $perbaikan . "','" . $extension . "')\">View</button>";
                        }

                        $templist[$key]['status_terakhir'] = "<div class='badge badge-danger mb-3'>Usulan Ditolak oleh Kabag Hukum</div>" .
                            ($data_terakhir->catatan_ditolak ? "Catatan :<br />" . nl2br($data_terakhir->catatan_ditolak) : "") .
                            ($data_terakhir->file_catatan_perbaikan ? "<div class='mt-3'>File perbaikan : " . $file . "</div>" : "");
                    }

                    // ===== SEDANG DIPROSES (Kasubbag sudah, Kabag tolak) =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '1' &&
                        $data_terakhir->jft_agree_disagree == '1' &&
                        $data_terakhir->kabag_agree_disagree == '2'
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . ($row->keterangan ?? 'Unknown') . "</div>";
                    }

                    // ===== SEDANG DIPROSES (Kasubbag sudah, Kabag belum) =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree != '' &&
                        $data_terakhir->kabag_agree_disagree == ''
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Sedang diproses oleh " . ($row->keterangan ?? 'Unknown') . "</div>";
                    }

                    // ===== USULAN PERBAIKAN =====
                    else if (
                        $data_terakhir->status_tracking == "3" &&
                        $data_terakhir->kasubbag_agree_disagree == '' &&
                        $data_terakhir->kabag_agree_disagree == ''
                    ) {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-warning'>Usulan Perbaikan</div>";
                    }

                    // ===== DISPOSISI =====
                    else if ($data_terakhir->status_tracking == "2") {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-info'>Diteruskan ke " . ($row->keterangan ?? 'Unknown') . "</div>";
                    }

                    // ===== USULAN BARU =====
                    else if ($data_terakhir->status_tracking == "1") {
                        $templist[$key]['status_terakhir'] = "<div class='badge badge-light'>Usulan Baru</div>";
                    }
                }

                $file_extension = explode(".", $data_terakhir ? $data_terakhir->file_usulan_raperbup : '');
                $usulan = $data_terakhir ? (base_url() . $this->config->item("file_usulan") . "/" . $data_terakhir->file_usulan_raperbup) : '#';
                $templist[$key]['file'] = $data_terakhir ? "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $usulan . "','" . ($file_extension[1] ?? 'pdf') . "')\">View</button>" : '-';

                if ($row->lampiran) {
                    $file_extension = explode(".", $row->lampiran);
                    $lampiran = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran;
                    $link_lampiran = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran . "','" . ($file_extension[1] ?? 'pdf') . "')\">Kepala Dinas</a>";
                } else {
                    $link_lampiran = "<a href='#viewdetail' class='dropdown-item'>Tidak Ada</a>";
                }

                if ($row->lampiran_sk_tim) {
                    $file_extension_sk_tim = explode(".", $row->lampiran_sk_tim);
                    $lampiran_sk_tim = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran_sk_tim;
                    $link_lampiran_sk_tim = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_sk_tim . "','" . ($file_extension_sk_tim[1] ?? 'pdf') . "')\">SK Tim</a>";
                } else {
                    $link_lampiran_sk_tim = "<a href='#viewdetail' class='dropdown-item'>Tidak Ada</a>";
                }

                if ($row->lampiran_daftar_hadir) {
                    $file_extension_daftar_hadir = explode(".", $row->lampiran_daftar_hadir);
                    $lampiran_daftar_hadir = base_url() . $this->config->item("file_lampiran") . "/" . $row->lampiran_daftar_hadir;
                    $link_lampiran_daftar_hadir = "<a href='#viewdetail' class='dropdown-item' onclick=\"view_detail('" . $lampiran_daftar_hadir . "','" . ($file_extension_daftar_hadir[1] ?? 'pdf') . "')\">Daftar Hadir</a>";
                } else {
                    $link_lampiran_daftar_hadir = "<a href='#viewdetail' class='dropdown-item'>Tidak Ada</a>";
                }

                $lampiran_group = "<div class='dropdown d-inline mr-2'>
                <button class='btn btn-info dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Lampiran
                </button>
                <div class='dropdown-menu'>";
                if (in_array($row->kategori_usulan_id, array("1", "2"))) {
                    $lampiran_group .= $link_lampiran;
                    $lampiran_group .= $link_lampiran_sk_tim;
                    $lampiran_group .= $link_lampiran_daftar_hadir;
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
        } catch (Exception $e) {
            log_message('error', 'Exception in get_data_usulan_raperbup: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Internal server error: ' . $e->getMessage()]));
        }
    }

    public function get_data_usulan_raperbup_by_id()
    {
        try {
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
        } catch (Exception $e) {
            log_message('error', 'Exception in get_data_usulan_raperbup_by_id: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Internal server error: ' . $e->getMessage()]));
        }
    }
}
