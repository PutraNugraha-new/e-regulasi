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
            $id_enc = $this->iget("id_usulan_raperbup");
            $id_usulan = decrypt_data($id_enc);

            // 1. Validasi ID
            if (!$id_enc || !$id_usulan) {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'ID usulan tidak valid'
                    ]));
                return;
            }

            // 2. Ambil usulan
            $usulan = $this->usulan_raperbup_model->get_by($id_usulan);
            if (!$usulan) {
                $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Usulan tidak ditemukan'
                    ]));
                return;
            }

            // 3. Perbaiki tahun jika kosong
            if (!$usulan->tahun || $usulan->tahun == 0) {
                $usulan->tahun = $usulan->created_at
                    ? date('Y', strtotime($usulan->created_at))
                    : date('Y');
            }

            $data['usulan_raperbup'] = $usulan;

            // 4. Ambil transaksi (opsional)
            $data['trx_raperbup'] = $this->trx_raperbup_model->get(
                array(
                    "where" => array(
                        "usulan_raperbup_id" => $id_usulan,
                        "status_tracking" => "2",
                    ),
                ),
                "row"
            );

            // 5. Sukses
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        } catch (Exception $e) {
            log_message('error', 'Exception in get_data_usulan_raperbup_by_id: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Terjadi kesalahan sistem'
                ]));
        }
    }

    public function get_nomor_terakhir()
    {
        $tahun = $this->input->post('tahun');
        if (!$tahun || !is_numeric($tahun)) {
            echo json_encode(['status' => false]);
            return;
        }

        $this->db->select_max('nomor_register');
        $this->db->where('tahun', $tahun);
        $this->db->where('nomor_register IS NOT NULL', null, false);
        $query = $this->db->get('usulan_raperbup');
        $row = $query->row();

        $nomor = $row->nomor_register ?? 0;

        echo json_encode(['status' => true, 'nomor' => (int)$nomor]);
    }

    public function cek_nomor_ada()
    {
        $nomor = $this->input->post('nomor_register');
        $tahun = $this->input->post('tahun');
        $id_usulan = $this->input->post('id_usulan') ?: 0;

        $this->db->where('nomor_register', $nomor);
        $this->db->where('tahun', $tahun);
        $this->db->where('id_usulan_raperbup !=', $id_usulan);
        $ada = $this->db->count_all_results('usulan_raperbup') > 0;

        echo json_encode(['ada' => $ada]);
    }

    public function get_data_final()
    {
        $tahun = $this->iget('tahun');
        $wh = ['t.status_tracking' => '5', 't.file_final IS NOT NULL' => null];
        if ($tahun)
            $wh['YEAR(u.created_at)'] = $tahun;

        $this->db->select("
        u.tahun,
        u.nomor_register,
        u.nama_peraturan,
        k.nama_kategori,
        s.nama AS nama_skpd,
        t.file_final,
        CONCAT('assets/file_usulan/', t.file_final) AS file_final_path
    ")
            ->from('usulan_raperbup u')
            ->join('trx_raperbup t', 't.usulan_raperbup_id = u.id_usulan_raperbup AND t.status_tracking = 5 AND t.file_final IS NOT NULL', 'inner')
            ->join('kategori_usulan k', 'k.id_kategori_usulan = u.kategori_usulan_id', 'left')
            ->join('user us', 'us.id_user = u.id_user_created', 'left')
            ->join('master_satker s', 's.id_master_satker = us.master_satker_id', 'left')
            ->where($wh)
            ->order_by('u.tahun DESC, u.nomor_register');

        $result = $this->db->get()->result_array();

        // PAKAI FORMAT DATATABLES!
        $response = [
            'data' => $result
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function export_final_excel()
    {
        $tahun = $this->iget('tahun');
        $wh = ['t.status_tracking' => '5', 't.file_final IS NOT NULL' => null];
        if ($tahun)
            $wh['YEAR(u.created_at)'] = $tahun;

        $this->db->select("
        u.tahun,
        u.nomor_register,
        u.nama_peraturan,
        k.nama_kategori,
        s.nama AS nama_skpd
    ", false)
            ->from('usulan_raperbup u')
            ->join('trx_raperbup t', 't.usulan_raperbup_id = u.id_usulan_raperbup AND t.status_tracking = 5 AND t.file_final IS NOT NULL', 'inner')
            ->join('kategori_usulan k', 'k.id_kategori_usulan = u.kategori_usulan_id', 'left')
            ->join('user us', 'us.id_user = u.id_user_created', 'left')
            ->join('master_satker s', 's.id_master_satker = us.master_satker_id', 'left')
            ->where($wh)
            ->order_by('u.tahun DESC, u.nomor_register');

        $data = $this->db->get()->result_array();

        // Jika kosong, kasih contoh
        if (empty($data)) {
            $data = [
                ['tahun' => '2025', 'nomor_register' => '1/2025', 'nama_peraturan' => 'Contoh Perda RTRW', 'nama_kategori' => 'Perda', 'nama_skpd' => 'Bappeda'],
                ['tahun' => '2025', 'nomor_register' => '2/2025', 'nama_peraturan' => 'Contoh Perbup Kepegawaian', 'nama_kategori' => 'Perbup', 'nama_skpd' => 'BKPSDM'],
            ];
            $is_contoh = true;
        } else {
            $is_contoh = false;
        }

        $this->load->library('excel');
        $this->excel->export_final_regulasi($data, $tahun, $is_contoh);
    }

    public function download_template()
    {
        $data = [
            ['tahun' => '2025', 'nomor_register' => '1/2025', 'nama_peraturan' => 'Perda RTRW', 'nama_kategori' => 'Perda', 'nama_skpd' => 'Bappeda'],
            ['tahun' => '2025', 'nomor_register' => '2/2025', 'nama_peraturan' => 'Perbup Kepegawaian', 'nama_kategori' => 'Perbup', 'nama_skpd' => 'BKPSDM'],
        ];
        $this->load->library('excel');
        $this->excel->export_final_regulasi($data, null, true);
    }

    public function import_final_excel()
    {
        if (!$_FILES['file_excel']['name']) {
            echo json_encode(['status' => false, 'message' => 'File wajib diupload']);
            return;
        }

        $this->load->library('excel');
        $file = $_FILES['file_excel']['tmp_name'];
        $obj = PHPExcel_IOFactory::load($file);
        $sheet = $obj->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        $headers = $data[1];
        $rows = array_slice($data, 2);
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($rows as $i => $row) {
            $tahun = $row['A'];
            $nomor_register = $row['B'];
            $nama_peraturan = $row['C'];
            $jenis = $row['D'];
            $skpd = $row['E'];
            $file_final = $row['F'];

            if (!$tahun || !$nomor_register || !$nama_peraturan) {
                $errors[] = "Baris " . ($i + 1) . ": Tahun, Nomor Register, Nama Peraturan wajib diisi";
                $failed++;
                continue;
            }

            // Cek duplikat nomor register per tahun
            $exist = $this->db->where(['tahun' => $tahun, 'nomor_register' => $nomor_register])->get('usulan_raperbup')->row();
            if ($exist) {
                $errors[] = "Baris " . ($i + 1) . ": Nomor register $nomor_register tahun $tahun sudah ada";
                $failed++;
                continue;
            }

            // Cari kategori
            $kategori = $this->db->like('nama_kategori', $jenis)->get('kategori_usulan')->row();
            if (!$kategori) {
                $errors[] = "Baris " . ($i + 1) . ": Jenis '$jenis' tidak ditemukan";
                $failed++;
                continue;
            }

            // Cari SKPD
            $satker = $this->db->like('nama', $skpd)->get('master_satker')->row();
            if (!$satker) {
                $errors[] = "Baris " . ($i + 1) . ": SKPD '$skpd' tidak ditemukan";
                $failed++;
                continue;
            }

            // Simpan usulan
            $id_usulan = $this->usulan_raperbup_model->save([
                'nama_peraturan' => $nama_peraturan,
                'nomor_register' => $nomor_register,
                'tahun' => $tahun,
                'kategori_usulan_id' => $kategori->id_kategori_usulan,
                'id_user_created' => $this->session->userdata('id_user'),
                'created_at' => $this->datetime()
            ]);

            // Simpan trx final
            $this->trx_raperbup_model->save([
                'usulan_raperbup_id' => $id_usulan,
                'file_final' => $file_final,
                'status_tracking' => '5',
                'id_user_created' => $this->session->userdata('id_user'),
                'created_at' => $this->datetime()
            ]);

            $success++;
        }

        $message = "$success data berhasil diimport. ";
        if ($failed)
            $message .= "$failed gagal: " . implode('; ', $errors);

        echo json_encode(['status' => $success > 0, 'message' => $message]);
    }
}
