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
        $this->load->model('Usulan_revisi_model');
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
        $data['kategori_usulan'] = $this->kategori_usulan_model->get(
            array(
                "order_by" => array(
                    "nama_kategori" => "ASC"
                )
            )
        );

        // Ambil parameter dari URL
        $data['selected_usulan_id'] = $this->input->get('usulan_id', TRUE);
        $data['selected_kategori_usulan'] = $this->input->get('kategori_usulan_id', TRUE);
        $data['selected_skpd_id'] = $this->input->get('skpd_id', TRUE);

        // Validasi parameter
        if ($data['selected_usulan_id']) {
            $usulan = $this->usulan_raperbup_model->get_by(decrypt_data($data['selected_usulan_id']));
            if (!$usulan) {
                log_message('error', 'Invalid usulan_id: ' . $data['selected_usulan_id']);
                $data['selected_usulan_id'] = '';
            }
        }
        if ($data['selected_kategori_usulan']) {
            $kategori = $this->kategori_usulan_model->get_by(decrypt_data($data['selected_kategori_usulan']));
            if (!$kategori) {
                log_message('error', 'Invalid kategori_usulan_id: ' . $data['selected_kategori_usulan']);
                $data['selected_kategori_usulan'] = 'all';
            }
        }
        if ($data['selected_skpd_id']) {
            $skpd = $this->master_satker_model->get_by($data['selected_skpd_id']);
            if (!$skpd) {
                log_message('error', 'Invalid skpd_id: ' . $data['selected_skpd_id']);
                $data['selected_skpd_id'] = '';
            }
        }

        $data['breadcrumb'] = ["header_content" => "Monitoring Usulan", "breadcrumb_link" => [['link' => false, 'content' => 'Monitoring Usulan', 'is_active' => true]]];
        // Render view berdasarkan level_user_id
        switch ($this->session->userdata("level_user_id")) {
            case '6':
                $this->execute('index_kabag', $data);
                break;
            case '7':
                $this->execute('index_kasubbag', $data);
                break;
            case '15':
                $this->execute('index_jft', $data);
                break;
            case '8':
                $this->execute('index_asisten', $data);
                break;
            case '9':
                $this->execute('index_sekda', $data);
                break;
            case '10':
                $this->execute('index_wabup', $data);
                break;
            case '11':
                $this->execute('index_bupati', $data);
                break;
            case '12':
                $this->execute('index_kalteng', $data);
                break;
            case '13':
                $this->execute('index_admin_diskominfo', $data);
                break;
            case '4':
                $this->execute('index_admin', $data);
                break;
            default:
                log_message('error', 'Unknown level_user_id: ' . $this->session->userdata("level_user_id"));
                show_error('Unauthorized access', 403);
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

        $user_updated = $this->db->select('nama_lengkap')
            ->from('user')
            ->where('id_user', $data_master[0]->id_user_updated)
            ->get()
            ->row();
        $last_updated_at = $data_master[0]->updated_at ? date('d-m-Y H:i:s', strtotime($data_master[0]->updated_at)) : '-';

        $data['user_updated'] = $user_updated ? $user_updated->nama_lengkap : null;
        $data['last_updated_at'] = $last_updated_at;

        $user_name_login = $this->session->userdata("nama_lengkap");

        $level_user = $this->db->select('id_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();
        $data['level_user'] = $level_user ? $level_user->id_level_user : null;

        $data_revisi = $this->Usulan_revisi_model->get_all_revisi(decrypt_data($id_usulan_raperbup));
        $data['data_revisi'] = $data_revisi;

        if (!$data_master) {
            $this->page_error();
        } else {
            // Ambil transaksi PERTAMA (file original)
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

            // Ambil transaksi TERAKHIR
            $file_terakhir_trx = $this->trx_raperbup_model->get(
                array(
                    "order_by" => array(
                        "id_trx_raperbup" => "DESC"
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

                // Preview file (gunakan file perbaikan jika ada, jika tidak gunakan original)
                $file_untuk_preview = $file_terakhir_trx->file_perbaikan ?: $file_usulan_raperbup->file_usulan_raperbup;
                $ekstensi_file_usulan = explode(".", $file_untuk_preview);
                $data['url_preview_usulan'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_usulan") . "/" . $file_untuk_preview . "','" . $ekstensi_file_usulan[1] . "')\">View</button>";

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

                $data['url_preview_lampiran_usulan'] = "";
                if ($data_master[0]->lampiran_usulan) {
                    $ekstensi_file_lampiran_usulan = explode(".", $data_master[0]->lampiran_usulan);
                    $data['url_preview_lampiran_usulan'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_lampiran") . "/" . $data_master[0]->lampiran_usulan . "','" . $ekstensi_file_lampiran_usulan[1] . "')\">View</button>";
                }

                $data['breadcrumb'] = ["header_content" => "Usulan", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'usulan_raperbup', 'content' => 'Usulan', 'is_active' => false], ['link' => false, 'content' => 'Ubah Usulan', 'is_active' => true]]];

                $this->execute('form_usulan_raperbup_new', $data);
            } else {
                // ===== PROSES UPDATE =====

                // File original (tidak berubah)
                $nama_file_usulan_original = $file_usulan_raperbup->file_usulan_raperbup;

                // File perbaikan (yang akan diupdate)
                $nama_file_perbaikan = $file_terakhir_trx->file_perbaikan ?: $file_terakhir_trx->file_usulan_raperbup;

                if (!empty($_FILES['file_upload']['name'])) {
                    if (count($data_master) > 1) {
                        $this->session->set_flashdata('message', 'Data tidak bisa diubah');
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('monitoring_raperbup');
                    } else {
                        $input_name = "file_upload";
                        $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                        if (isset($upload_file['error'])) {
                            $this->session->set_flashdata('message', $upload_file['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('monitoring_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_perbaikan = $upload_file['data']['file_name'];
                    }
                }

                $kategori_id = decrypt_data($this->ipost("kategori_usulan"));

                if (in_array($kategori_id, array("1", "2"))) {
                    $nama_file_lampiran = $data_master[0]->lampiran;
                    if (!empty($_FILES['file_lampiran']['name'])) {
                        $input_name_lampiran = "file_lampiran";
                        $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc,pdf");
                        if (isset($upload_file_lampiran['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('monitoring_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_lampiran = $upload_file_lampiran['data']['file_name'];
                    }

                    $nama_file_lampiran_sk_tim = $data_master[0]->lampiran_sk_tim;
                    if (!empty($_FILES['file_lampiran_sk_tim']['name'])) {
                        $input_name_lampiran_sk_tim = "file_lampiran_sk_tim";
                        $upload_file_lampiran_sk_tim = $this->upload_file($input_name_lampiran_sk_tim, $this->config->item('file_lampiran'), "", "doc,pdf");
                        if (isset($upload_file_lampiran_sk_tim['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran_sk_tim['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('monitoring_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_lampiran_sk_tim = $upload_file_lampiran_sk_tim['data']['file_name'];
                    }

                    $nama_file_lampiran_daftar_hadir = $data_master[0]->lampiran_daftar_hadir;
                    if (!empty($_FILES['file_lampiran_daftar_hadir']['name'])) {
                        $input_name_lampiran_daftar_hadir = "file_lampiran_daftar_hadir";
                        $upload_file_lampiran_daftar_hadir = $this->upload_file($input_name_lampiran_daftar_hadir, $this->config->item('file_lampiran'), "", "doc,pdf");
                        if (isset($upload_file_lampiran_daftar_hadir['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran_daftar_hadir['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('monitoring_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_lampiran_daftar_hadir = $upload_file_lampiran_daftar_hadir['data']['file_name'];
                    }

                    $judul_bab = $this->input->post("judul_bab");
                    $judul_bagian = $this->input->post("judul_bagian");
                    $isi_pasal = $this->input->post("isi_pasal");
                    $pasal_bab_mapping = $this->input->post("pasal_bab_mapping");
                    $pasal_bagian_mapping = $this->input->post("pasal_bagian_mapping");
                    $bab_pasal_data = array();

                    if (!empty($judul_bab)) {
                        // Inisialisasi struktur bab
                        foreach ($judul_bab as $bab_number => $judul) {
                            $bab_pasal_data[$bab_number] = array(
                                'judul' => $judul,
                                'pasal' => array(),
                                'bagian' => array()
                            );
                        }

                        // Tambahkan bagian ke struktur bab
                        if (!empty($judul_bagian)) {
                            foreach ($judul_bagian as $bab_number => $bagian_array) {
                                if (isset($bab_pasal_data[$bab_number]) && is_array($bagian_array)) {
                                    foreach ($bagian_array as $bagian_number => $judul_bagian_text) {
                                        $bab_pasal_data[$bab_number]['bagian'][$bagian_number] = array(
                                            'judul' => $judul_bagian_text,
                                            'pasal' => array()
                                        );
                                    }
                                }
                            }
                        }

                        // Distribusikan pasal ke bab/bagian yang sesuai berdasarkan mapping
                        if (!empty($isi_pasal) && !empty($pasal_bab_mapping)) {
                            foreach ($isi_pasal as $pasal_number => $isi) {
                                $bab_number = $pasal_bab_mapping[$pasal_number];
                                $bagian_number = isset($pasal_bagian_mapping[$pasal_number]) ? $pasal_bagian_mapping[$pasal_number] : 0;

                                if (isset($bab_pasal_data[$bab_number])) {
                                    // Jika pasal ada di dalam bagian (bagian_number > 0)
                                    if ($bagian_number > 0 && isset($bab_pasal_data[$bab_number]['bagian'][$bagian_number])) {
                                        $bab_pasal_data[$bab_number]['bagian'][$bagian_number]['pasal'][$pasal_number] = array(
                                            'isi' => $isi
                                        );
                                    }
                                    // Jika pasal langsung di bab (bagian_number = 0)
                                    else {
                                        $bab_pasal_data[$bab_number]['pasal'][$pasal_number] = array(
                                            'isi' => $isi
                                        );
                                    }
                                }
                            }
                        }
                    }

                    $data = array(
                        "nama_peraturan" => $this->ipost("nama_peraturan"),
                        "menimbang" => $this->input->post("menimbang"),
                        "mengingat" => $this->input->post("mengingat"),
                        "menetapkan" => $this->input->post("menetapkan"),
                        "bab_pasal_data" => json_encode($bab_pasal_data),
                        "penjelasan" => $this->input->post("penjelasan"),
                        "lampiran" => $nama_file_lampiran,
                        "lampiran_sk_tim" => $nama_file_lampiran_sk_tim,
                        "lampiran_daftar_hadir" => $nama_file_lampiran_daftar_hadir,
                        "kategori_usulan_id" => $kategori_id,
                        'updated_at' => $this->datetime(),
                        "id_user_updated" => $this->session->userdata("id_user")
                    );
                } else {
                    $nama_file_lampiran = $data_master[0]->lampiran;
                    if (!empty($_FILES['file_lampiran']['name'])) {
                        $input_name_lampiran = "file_lampiran";
                        $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc,pdf");
                        if (isset($upload_file_lampiran['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('monitoring_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_lampiran = $upload_file_lampiran['data']['file_name'];
                    }

                    $nama_file_lampiran_usulan = $data_master[0]->lampiran_usulan;
                    if (!empty($_FILES['file_lampiran_usulan']['name'])) {
                        $input_name_lampiran_usulan = "file_lampiran_usulan";
                        $upload_file_lampiran_usulan = $this->upload_file($input_name_lampiran_usulan, $this->config->item('file_lampiran'), "", "pdf");
                        if (isset($upload_file_lampiran_usulan['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran_usulan['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('monitoring_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_lampiran_usulan = $upload_file_lampiran_usulan['data']['file_name'];
                    }

                    $keputusan = $this->input->post("keputusan");
                    $keputusan_string = is_array($keputusan) ? json_encode($keputusan) : $keputusan;

                    $data = array(
                        "nama_peraturan" => $this->ipost("nama_peraturan"),
                        "menimbang" => $this->input->post("menimbang"),
                        "mengingat" => $this->input->post("mengingat"),
                        "menetapkan" => $this->input->post("menetapkan"),
                        "memutuskan" => $keputusan_string,
                        "tembusan" => $this->input->post("tembusan"),
                        "lampiran" => $nama_file_lampiran,
                        "lampiran_usulan" => $nama_file_lampiran_usulan,
                        "kategori_usulan_id" => $kategori_id,
                        'updated_at' => $this->datetime(),
                        "id_user_updated" => $this->session->userdata("id_user")
                    );
                }

                // Update tabel usulan_raperbup
                $this->usulan_raperbup_model->edit(decrypt_data($id_usulan_raperbup), $data);

                $last_trx = $this->trx_raperbup_model->get(
                    array(
                        "order_by" => array("id_trx_raperbup" => "DESC"),
                        "where" => array("usulan_raperbup_id" => decrypt_data($id_usulan_raperbup)),
                        "limit" => "1"
                    ),
                    "row"
                );

                if (!$last_trx) {
                    // Handle error
                    $this->session->set_flashdata('message', 'Transaksi tidak ditemukan');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('monitoring_raperbup');
                    return;
                }

                $last_trx_id = $last_trx->id_trx_raperbup;

                $data_trx = array(
                    "file_perbaikan" => $nama_file_perbaikan,
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->edit($last_trx_id, $data_trx);

                if ($status) {
                    // Regenerate PDF dengan ID transaksi BARU
                    $this->generate_pdf_raperbup(decrypt_data($id_usulan_raperbup), $last_trx->id_trx_raperbup, 'F');

                    // Trigger notifikasi
                    $id_usulan_raperbup_decrypted = decrypt_data($id_usulan_raperbup);
                    $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup_decrypted)->nama_peraturan ?: 'Usulan Tanpa Nama';
                    $id_pengaju = $this->db->select('id_user_created')
                        ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                        ->get('usulan_raperbup')
                        ->row()->id_user_created;

                    $data_notif = [
                        'id_user_tujuan' => $id_pengaju,
                        'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                        'tipe_notif' => 'revisi_admin_hukum',
                        'pesan' => 'Usulan "' . $nama_peraturan . '" direvisi oleh ' . $user_name_login,
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif revisi_admin_hukum saved: ' . json_encode($data_notif));

                    $this->session->set_flashdata('message', 'Data berhasil diubah');
                    $this->session->set_flashdata('type-alert', 'success');
                    redirect('monitoring_raperbup');
                } else {
                    $this->session->set_flashdata('message', 'Data gagal diubah');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('monitoring_raperbup');
                }
            }
        }
    }

    public function preview_pdf_raperbup()
    {
        // Ambil data dari form dengan sanitasi
        $nama_peraturan = $this->input->post('nama_peraturan', TRUE);
        $menimbang = $this->input->post('menimbang');
        $mengingat = $this->input->post('mengingat');
        $menetapkan = $this->input->post('menetapkan');
        $keputusan = $this->input->post('keputusan');
        $tembusan = $this->input->post('tembusan');
        $kategori_usulan_id = decrypt_data($this->input->post('kategori_usulan', TRUE));
        $judul_bab = $this->input->post('judul_bab');
        $judul_bagian = $this->input->post('judul_bagian');
        $isi_pasal = $this->input->post('isi_pasal');
        $pasal_bab_mapping = $this->input->post('pasal_bab_mapping');
        $pasal_bagian_mapping = $this->input->post('pasal_bagian_mapping');
        $penjelasan = $this->input->post('penjelasan');

        // Validasi field wajib berdasarkan kategori
        if ($kategori_usulan_id == 3) { // Kepbup
            if (empty($nama_peraturan) || empty($menimbang) || empty($mengingat) || empty($menetapkan) || empty($keputusan) || empty($tembusan)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Field wajib (Nama Peraturan, Menimbang, Mengingat, Menetapkan, Keputusan, Tembusan) harus diisi']);
                return;
            }
        } else if ($kategori_usulan_id == 1 || $kategori_usulan_id == 2) { // Perda & Perbup
            if (empty($nama_peraturan) || empty($menimbang) || empty($mengingat) || empty($judul_bab) || empty($isi_pasal)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Field wajib (Nama Peraturan, Menimbang, Mengingat, Judul Bab, Isi Pasal) harus diisi']);
                return;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Kategori usulan tidak valid']);
            return;
        }

        // Proses data berdasarkan kategori
        if ($kategori_usulan_id == 1 || $kategori_usulan_id == 2) {
            // Buat struktur bab-bagian-pasal untuk Peraturan Bupati
            $bab_pasal_data = array();

            if (!empty($judul_bab)) {
                // Inisialisasi struktur bab
                foreach ($judul_bab as $bab_number => $judul) {
                    $bab_pasal_data[$bab_number] = array(
                        'judul' => $judul,
                        'pasal' => array(),
                        'bagian' => array()
                    );
                }

                // Proses bagian jika ada
                if (!empty($judul_bagian)) {
                    foreach ($judul_bagian as $bab_num => $bagian_array) {
                        if (is_array($bagian_array)) {
                            foreach ($bagian_array as $bagian_num => $judul_bagian_text) {
                                if (isset($bab_pasal_data[$bab_num])) {
                                    $bab_pasal_data[$bab_num]['bagian'][$bagian_num] = array(
                                        'judul' => $judul_bagian_text,
                                        'pasal' => array()
                                    );
                                }
                            }
                        }
                    }
                }

                // Distribusikan pasal ke bab dan bagian yang sesuai
                if (!empty($isi_pasal) && !empty($pasal_bab_mapping)) {
                    foreach ($isi_pasal as $pasal_number => $isi) {
                        $bab_number = isset($pasal_bab_mapping[$pasal_number]) ? $pasal_bab_mapping[$pasal_number] : null;
                        $bagian_number = isset($pasal_bagian_mapping[$pasal_number]) ? $pasal_bagian_mapping[$pasal_number] : 0;

                        if ($bab_number && isset($bab_pasal_data[$bab_number])) {
                            $pasal_data = array('isi' => $isi);

                            // Jika pasal ada dalam bagian
                            if ($bagian_number > 0 && isset($bab_pasal_data[$bab_number]['bagian'][$bagian_number])) {
                                $bab_pasal_data[$bab_number]['bagian'][$bagian_number]['pasal'][$pasal_number] = $pasal_data;
                            } else {
                                // Pasal langsung di bab (tanpa bagian)
                                $bab_pasal_data[$bab_number]['pasal'][$pasal_number] = $pasal_data;
                            }
                        }
                    }
                }
            }

            // Debug: Log struktur data (hapus setelah selesai debug)
            log_message('debug', 'Bab Pasal Data: ' . print_r($bab_pasal_data, true));

            // Data untuk template Peraturan Bupati
            $data = array(
                'nama_peraturan' => $nama_peraturan,
                'menimbang' => $menimbang,
                'mengingat' => $mengingat,
                'menetapkan' => $menetapkan,
                'bab_pasal_data' => $bab_pasal_data,
                'penjelasan' => $penjelasan,
                'nomor' => '123', // Nomor sementara untuk preview
                'tanggal' => date('d F Y', strtotime($this->datetime())),
                'lampiran' => '' // Kosong karena hanya preview
            );
        } else {
            // Proses keputusan sebagai array untuk Keputusan Bupati
            $keputusan_data = is_array($keputusan) ? $keputusan : [$keputusan];

            // Data untuk template Keputusan Bupati
            $data = array(
                'nama_peraturan' => $nama_peraturan,
                'menimbang' => $menimbang,
                'mengingat' => $mengingat,
                'menetapkan' => $menetapkan,
                'memutuskan' => $keputusan_data,
                'tembusan' => $tembusan,
                'nomor' => '123', // Nomor sementara untuk preview
                'tanggal' => date('d F Y', strtotime($this->datetime())),
                'lampiran' => '' // Kosong karena hanya preview
            );
        }

        // Pilih template berdasarkan kategori usulan
        $template = ($kategori_usulan_id == 1) ? 'template/perda' : (($kategori_usulan_id == 2) ? 'template/perbup' : 'template/kepbup');
        $html = $this->load->view($template, $data, TRUE);

        // Konfigurasi mPDF
        $this->mpdf_library->mpdf->SetTitle('Preview Peraturan Bupati Katingan');
        if ($kategori_usulan_id == 1 || $kategori_usulan_id == 2) {
            // Atur header dengan nomor halaman
            $header_html = '- {PAGENO} -';
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'E', true);
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'O', true);
            $this->mpdf_library->mpdf->SetHTMLHeader('', 'first');
            $this->mpdf_library->mpdf->WriteHTML($html);
        } else {
            $this->mpdf_library->mpdf->WriteHTML($html);
        }

        // Nama file PDF
        $pdf_file_name = 'Preview_Peraturan_Bupati_' . time() . '.pdf';

        // Bersihkan output buffer
        ob_clean();

        // Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $pdf_file_name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        $this->mpdf_library->mpdf->Output($pdf_file_name, 'I');
        exit;
    }

    public function generate_pdf_raperbup($id_usulan_raperbup, $trx_id, $output_mode = 'F')
    {
        // Ambil data usulan berdasarkan ID
        $data_usulan = $this->usulan_raperbup_model->get(
            array(
                "where" => array(
                    "id_usulan_raperbup" => $id_usulan_raperbup
                )
            ),
            "row"
        );

        if (!$data_usulan) {
            log_message('error', "Data usulan tidak ditemukan untuk ID: $id_usulan_raperbup");
            $this->session->set_flashdata('message', 'Data usulan tidak ditemukan');
            $this->session->set_flashdata('type-alert', 'danger');
            redirect('usulan_raperbup');
            return;
        }

        // Siapkan data untuk view berdasarkan kategori
        if ($data_usulan->kategori_usulan_id == 1 || $data_usulan->kategori_usulan_id == 2) {
            $bab_pasal_data = json_decode($data_usulan->bab_pasal_data, true);
            $data = array(
                'nama_peraturan' => $data_usulan->nama_peraturan,
                'menimbang' => $data_usulan->menimbang,
                'mengingat' => $data_usulan->mengingat,
                'menetapkan' => $data_usulan->menetapkan,
                'bab_pasal_data' => $bab_pasal_data,
                'penjelasan' => isset($data_usulan->penjelasan) ? $data_usulan->penjelasan : '',
                'nomor' => '123',
                'tanggal' => date('d F Y', strtotime($this->datetime())),
                'lampiran' => $data_usulan->lampiran,
                'lampiran_sk_tim' => isset($data_usulan->lampiran_sk_tim) ? $data_usulan->lampiran_sk_tim : '',
                'lampiran_daftar_hadir' => isset($data_usulan->lampiran_daftar_hadir) ? $data_usulan->lampiran_daftar_hadir : ''
            );

            if ($data_usulan->kategori_usulan_id == 1) {
                $data['penjelasan'] = isset($data_usulan->penjelasan) ? $data_usulan->penjelasan : '';
            }
        } else {
            $data = array(
                'nama_peraturan' => $data_usulan->nama_peraturan,
                'menimbang' => $data_usulan->menimbang,
                'mengingat' => $data_usulan->mengingat,
                'menetapkan' => $data_usulan->menetapkan,
                'memutuskan' => json_decode($data_usulan->memutuskan, true),
                'tembusan' => $data_usulan->tembusan,
                'nomor' => '123',
                'tanggal' => date('d F Y', strtotime($this->datetime())),
                'lampiran' => $data_usulan->lampiran,
                'lampiran_usulan' => $data_usulan->lampiran_usulan
            );
        }

        // Load view template berdasarkan kategori usulan
        $template = ($data_usulan->kategori_usulan_id == 1) ? 'template/perda' : (($data_usulan->kategori_usulan_id == 2) ? 'template/perbup' : 'template/kepbup');
        $html = $this->load->view($template, $data, TRUE);

        // Konfigurasi mPDF untuk dokumen utama
        $this->mpdf_library->mpdf->SetTitle('Keputusan Bupati Katingan');

        if ($data_usulan->kategori_usulan_id == 1 || $data_usulan->kategori_usulan_id == 2) {
            $header_html = '- {PAGENO} -';
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'E', true);
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'O', true);
            $this->mpdf_library->mpdf->SetHTMLHeader('', 'first');
        }

        $this->mpdf_library->mpdf->WriteHTML($html);

        $pdf_file_name = 'Keputusan_Bupati_' . str_replace(' ', '_', $data_usulan->nama_peraturan) . '_' . time() . '.pdf';
        $pdf_path = FCPATH . 'assets/file_usulan/' . $pdf_file_name;

        try {
            $this->mpdf_library->mpdf->Output($pdf_path, 'F');
            log_message('debug', "PDF dokumen utama disimpan di: $pdf_path");
        } catch (\Mpdf\MpdfException $e) {
            log_message('error', "Gagal menyimpan PDF dokumen utama: " . $e->getMessage());
            $this->session->set_flashdata('message', 'Gagal menghasilkan PDF dokumen utama');
            $this->session->set_flashdata('type-alert', 'danger');
            redirect('usulan_raperbup');
            return;
        }

        if ($data_usulan->kategori_usulan_id == 3 && !empty($data_usulan->lampiran_usulan)) {
            $lampiran_path = FCPATH . $this->config->item('file_lampiran') . '/' . $data_usulan->lampiran_usulan;
            $merged_pdf_path = FCPATH . 'assets/file_usulan/merged_' . $pdf_file_name;

            if (!file_exists($pdf_path)) {
                log_message('error', "File dokumen utama tidak ditemukan: $pdf_path");
                $this->session->set_flashdata('message', 'Gagal menggabungkan: File dokumen utama tidak ditemukan');
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('usulan_raperbup');
                return;
            }
            if (!file_exists($lampiran_path)) {
                log_message('error', "File lampiran_usulan tidak ditemukan: $lampiran_path");
                $this->session->set_flashdata('message', 'Gagal menggabungkan: File lampiran_usulan tidak ditemukan');
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('usulan_raperbup');
                return;
            }
            if (pathinfo($lampiran_path, PATHINFO_EXTENSION) !== 'pdf') {
                log_message('error', "File lampiran_usulan bukan PDF: $lampiran_path");
                $this->session->set_flashdata('message', 'File lampiran_usulan harus berformat PDF');
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('usulan_raperbup');
                return;
            }

            $pdf_files = [$pdf_path, $lampiran_path];
            $merge_result = $this->mpdf_library->merge_pdfs($pdf_files, $merged_pdf_path);

            if ($merge_result) {
                unlink($pdf_path);
                $pdf_path = $merged_pdf_path;
                $pdf_file_name = basename($merged_pdf_path);
                log_message('debug', "PDF berhasil digabungkan di: $pdf_path");
            } else {
                log_message('error', "Gagal menggabungkan PDF");
                $this->session->set_flashdata('message', 'Gagal menggabungkan PDF dengan lampiran_usulan');
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('usulan_raperbup');
                return;
            }
        }

        $this->trx_raperbup_model->edit($trx_id, array(
            'file_perbaikan' => $pdf_file_name,
            'updated_at' => $this->datetime(),
            'id_user_updated' => $this->session->userdata("id_user")
        ));

        if ($output_mode === 'I' || $output_mode === 'D') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: ' . ($output_mode === 'D' ? 'attachment' : 'inline') . '; filename="' . $pdf_file_name . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            if ($data_usulan->kategori_usulan_id == 3 && !empty($data_usulan->lampiran_usulan)) {
                if (file_exists($pdf_path)) {
                    header('Content-Length: ' . filesize($pdf_path));
                    readfile($pdf_path);
                    exit;
                } else {
                    log_message('error', "File hasil penggabungan tidak ditemukan: $pdf_path");
                    $this->session->set_flashdata('message', 'File hasil penggabungan tidak ditemukan');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('usulan_raperbup');
                    return;
                }
            } else {
                if (file_exists($pdf_path)) {
                    header('Content-Length: ' . filesize($pdf_path));
                    readfile($pdf_path);
                    exit;
                } else {
                    log_message('error', "File PDF tidak ditemukan: $pdf_path");
                    $this->session->set_flashdata('message', 'File PDF tidak ditemukan');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('usulan_raperbup');
                    return;
                }
            }
        }
    }

    public function simpan_revisi()
    {
        // Validasi level user
        $level_user = $this->db->select('id_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();
        if (!in_array($level_user->id_level_user, [4, 6, 7, 15])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengupdate catatan revisi'
            ]);
            return;
        }

        $this->load->model('Usulan_revisi_model');

        $id_usulan_raperbup = $this->input->post('id_usulan_raperbup');
        $kolom_tujuan = $this->input->post('kolom_tujuan');
        $catatan_revisi = $this->input->post('catatan_revisi');
        $id_user = $this->session->userdata('id_user');

        if (!$id_usulan_raperbup || !$kolom_tujuan || !$catatan_revisi) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ]);
            return;
        }

        $data = [
            'id_usulan_raperbup' => $id_usulan_raperbup,
            'id_user' => $id_user,
            'kolom_tujuan' => $kolom_tujuan,
            'catatan_revisi' => $catatan_revisi,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $id_pengaju = $this->db->select('id_user_created')
            ->where('id_usulan_raperbup', $id_usulan_raperbup)
            ->get('usulan_raperbup')
            ->row()->id_user_created;

        $data_notif = [
            'id_user_tujuan' => $id_pengaju,
            'id_usulan_raperbup' => $id_usulan_raperbup,
            'tipe_notif' => 'Catatan revisi_admin_hukum',
            'pesan' => $catatan_revisi,
        ];

        $this->Notifikasi_model->simpan_notif($data_notif);

        $insert_id = $this->Usulan_revisi_model->insert($data);

        if ($insert_id) {
            // Ambil data lengkap dengan nama user
            $revisi = $this->Usulan_revisi_model->get_by_id($insert_id);

            echo json_encode([
                'status' => 'success',
                'message' => 'Revisi berhasil disimpan',
                'data' => $revisi
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan revisi'
            ]);
        }
    }

    public function update_revisi()
    {
        // Validasi level user
        $level_user = $this->db->select('id_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();
        if (!in_array($level_user->id_level_user, [4, 6, 7, 15])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengupdate catatan revisi'
            ]);
            return;
        }

        $this->load->model('Usulan_revisi_model');

        $id_revisi = $this->input->post('id_revisi');
        $catatan_revisi = $this->input->post('catatan_revisi');
        $id_user = $this->session->userdata('id_user');

        if (!$id_revisi || !$catatan_revisi) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ]);
            return;
        }

        // Cek apakah revisi milik user yang login
        $revisi = $this->Usulan_revisi_model->get_by_id($id_revisi);
        if (!$revisi || $revisi->id_user != $id_user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengupdate revisi ini'
            ]);
            return;
        }

        $data = [
            'catatan_revisi' => $catatan_revisi,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $update = $this->Usulan_revisi_model->update($id_revisi, $data);

        if ($update) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Revisi berhasil diupdate',
                'data' => ['updated_at' => date('Y-m-d H:i:s')]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengupdate revisi'
            ]);
        }
    }

    public function hapus_revisi()
    {
        // Validasi level user
        $level_user = $this->db->select('id_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();
        if (!in_array($level_user->id_level_user, [4, 6, 7, 15])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengupdate catatan revisi'
            ]);
            return;
        }

        $this->load->model('Usulan_revisi_model');

        $id_revisi = $this->input->post('id_revisi');
        $id_user = $this->session->userdata('id_user');


        if (!$id_revisi) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID revisi tidak valid'
            ]);
            return;
        }

        // Cek apakah revisi milik user yang login
        $revisi = $this->Usulan_revisi_model->get_by_id($id_revisi);
        if (!$revisi || $revisi->id_user != $id_user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk menghapus revisi ini'
            ]);
            return;
        }

        $delete = $this->Usulan_revisi_model->delete($id_revisi);

        if ($delete) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Revisi berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus revisi'
            ]);
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
            $id_usulan_raperbup_decrypted = decrypt_data($this->ipost("id_usulan_raperbup"));
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup_decrypted)->nama_peraturan ?: 'Usulan Tanpa Nama';

            // Notif ke Kasubbag
            $data_notif = [
                'id_user_tujuan' => decrypt_data($this->ipost("id_kasubbag")),
                'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                'tipe_notif' => 'disposisi',
                'pesan' => 'Usulan diteruskan oleh Admin Hukum untuk koreksi: ' . $nama_peraturan . ($this->ipost("catatan_disposisi") ? ' (Catatan: ' . $this->ipost("catatan_disposisi") . ')' : ''),
                'created_at' => $this->datetime()
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif disposisi saved for Kasubbag: ' . json_encode($data_notif));

            // Notif ke Admin PD
            $id_pengaju = $this->db->select('id_user_created')
                ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                ->get('usulan_raperbup')
                ->row()->id_user_created;
            $data_notif = [
                'id_user_tujuan' => $id_pengaju,
                'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                'tipe_notif' => 'disposisi',
                'pesan' => 'Usulan Anda diteruskan ke Kasubbag oleh Admin Hukum: ' . $nama_peraturan . ($this->ipost("catatan_disposisi") ? ' (Catatan: ' . $this->ipost("catatan_disposisi") . ')' : ''),
                'created_at' => $this->datetime()
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
            "status_tracking" => '3', // ← TETAP 3
            "status_pesan" => "1",
            "kasubbag_agree_disagree" => $status,
            "jft_agree_disagree" => "", // ← TAMBAH: Kosongkan untuk JFT
            "kabag_agree_disagree" => "", // ← Tetap kosong
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status_save = $this->trx_raperbup_model->save($data_trx);

        if ($status_save) {
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';

            if ($status == '1') {
                // ← UBAH: Kirim notif ke JFT (level 15), bukan langsung Kabag
                $jft_users = $this->db->select('id_user')
                    ->where('level_user_id', 15)
                    ->get('user')
                    ->result();

                foreach ($jft_users as $jft) {
                    $data_notif = [
                        'id_user_tujuan' => $jft->id_user,
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_kasubbag',
                        'pesan' => 'Usulan disetujui oleh Kasubbag untuk review JFT: ' . $nama_peraturan,
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status_save));
    }

    public function setuju_ditolak_jft()
    {
        $id_peraturan = decrypt_data($this->ipost("id_peraturan"));
        $status = $this->ipost("status"); // 1=setuju, 2=tolak

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
            "file_perbaikan" => $data_last_trx->file_perbaikan,
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => '3', // ← TETAP 3
            "status_pesan" => "1",
            "kasubbag_agree_disagree" => '1', // Copy
            "jft_agree_disagree" => $status, // ← Isi keputusan JFT
            "kabag_agree_disagree" => "", // ← Kosong untuk Kabag
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status_save = $this->trx_raperbup_model->save($data_trx);

        if ($status_save) {
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_peraturan)->nama_peraturan ?: 'Usulan Tanpa Nama';

            if ($status == '1') {
                // Kirim notif ke Kabag (level 6)
                $kabag_users = $this->db->select('id_user')
                    ->where('level_user_id', 6)
                    ->get('user')
                    ->result();

                foreach ($kabag_users as $kabag) {
                    $data_notif = [
                        'id_user_tujuan' => $kabag->id_user,
                        'id_usulan_raperbup' => $id_peraturan,
                        'tipe_notif' => 'setuju_jft',
                        'pesan' => 'Usulan disetujui oleh JFT untuk review Kabag: ' . $nama_peraturan,
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                }
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status_save));
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

        // Cek level user yang sedang login
        $level_user_id = $this->session->userdata("level_user_id");
        $is_jft = ($level_user_id == 15);

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
                    "level_user_id_status" => $level_user_id,
                    "status_tracking" => "3",
                    "status_pesan" => "2",
                    'created_at' => $this->datetime(),
                    "id_user_created" => $this->session->userdata("id_user")
                );

                // Set kolom agree_disagree sesuai level user
                if ($is_jft) {
                    $data_trx["jft_agree_disagree"] = "2";
                } else {
                    $data_trx["kasubbag_agree_disagree"] = "2";
                }

                $status = $this->trx_raperbup_model->save($data_trx);

                if ($status) {
                    // Set tipe notif dan pesan sesuai level user
                    $tipe_notif = $is_jft ? 'tolak_jft' : 'tolak_kasubbag';
                    $penolak = $is_jft ? 'JFT' : 'Kasubbag';

                    // Tentukan tujuan notifikasi
                    if ($is_jft) {
                        // Jika JFT yang menolak, notif ke Kasubbag (level 7)
                        $id_tujuan = $this->db->select('id_user')
                            ->where('level_user_id', 7)
                            ->get('user')
                            ->row()->id_user;
                    } else {
                        // Jika Kasubbag yang menolak, notif ke Admin PD (pengaju)
                        $id_tujuan = $this->db->select('id_user_created')
                            ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                            ->get('usulan_raperbup')
                            ->row()->id_user_created;
                    }

                    $data_notif = [
                        'id_user_tujuan' => $id_tujuan,
                        'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                        'tipe_notif' => $tipe_notif,
                        'pesan' => 'Usulan ditolak oleh ' . $penolak . ': ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif ' . $tipe_notif . ' saved: ' . json_encode($data_notif));
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
                "level_user_id_status" => $level_user_id,
                "status_tracking" => "3",
                "status_pesan" => "2",
                'created_at' => $this->datetime(),
                "id_user_created" => $this->session->userdata("id_user")
            );

            // Set kolom agree_disagree sesuai level user
            if ($is_jft) {
                $data_trx["kasubbag_agree_disagree"] = "1";
                $data_trx["jft_agree_disagree"] = "2";
            } else {
                $data_trx["kasubbag_agree_disagree"] = "2";
            }

            $status = $this->trx_raperbup_model->save($data_trx);

            if ($status) {
                // Notif ke Admin PD (pengaju)
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;

                // Set tipe notif dan pesan sesuai level user
                $tipe_notif = $is_jft ? 'tolak_jft' : 'tolak_kasubbag';
                $penolak = $is_jft ? 'JFT' : 'Kasubbag';

                if ($is_jft) {
                    $data_notif = [
                        'id_user_tujuan' => $data_last_trx->id_user_created,
                        'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                        'tipe_notif' => $tipe_notif,
                        'pesan' => 'Usulan ditolak oleh ' . $penolak . ': ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    ];
                } else {
                    $data_notif = [
                        'id_user_tujuan' => $id_pengaju,
                        'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                        'tipe_notif' => $tipe_notif,
                        'pesan' => 'Usulan ditolak oleh ' . $penolak . ': ' . $nama_peraturan . ($catatan ? ' (Catatan: ' . $catatan . ')' : ''),
                    ];
                }

                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif ' . $tipe_notif . ' saved: ' . json_encode($data_notif));
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

            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif ubah_tanggal_transaksi saved: ' . json_encode($data_notif));
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    // ========================================
    // METHOD BARU: Kabag Approve Final
    // ========================================
    public function approve_final_kabag()
    {
        $id_usulan_raperbup = decrypt_data($this->ipost("id_usulan_raperbup"));
        $catatan = $this->ipost("catatan"); // Optional: catatan persetujuan

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

        // Validasi: JFT harus sudah approve
        if ($data_last_trx->jft_agree_disagree != '1') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Usulan harus disetujui JFT terlebih dahulu.'
                ]));
            return;
        }

        // Validasi: Kabag belum approve
        if ($data_last_trx->kabag_agree_disagree == '1') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Usulan sudah disetujui sebelumnya.'
                ]));
            return;
        }

        // Gunakan file perbaikan jika ada
        $file_usulan = $data_last_trx->file_usulan_raperbup;
        // if ($data_last_trx->file_perbaikan) {
        //     $file_usulan = $data_last_trx->file_perbaikan;
        // }

        // Simpan transaksi baru dengan status FINAL (status_tracking = 5)
        $data_trx = array(
            "usulan_raperbup_id" => $id_usulan_raperbup,
            "file_usulan_raperbup" => $file_usulan,
            "file_perbaikan" => $data_last_trx->file_perbaikan,
            "file_catatan_perbaikan" => $data_last_trx->file_catatan_perbaikan,
            "file_final" => $data_last_trx->file_perbaikan,
            "catatan_ditolak" => $catatan ?: $data_last_trx->catatan_ditolak,
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => '5', // ← STATUS FINAL (PUBLISH)
            "kasubbag_agree_disagree" => $data_last_trx->kasubbag_agree_disagree,
            "jft_agree_disagree" => $data_last_trx->jft_agree_disagree,
            "kabag_agree_disagree" => '1', // ← KABAG SETUJU
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        $source_path = FCPATH . 'assets/file_usulan/' . $data_last_trx->file_perbaikan;
        $destination_path = FCPATH . 'assets/file_final/' . $data_last_trx->file_perbaikan;

        if (!is_dir(dirname($destination_path))) {
            mkdir(dirname($destination_path), 0755, true);
        }

        copy($source_path, $destination_path);


        if ($status) {
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';

            // Kirim notifikasi ke Pengaju (Admin PD)
            $id_pengaju = $this->db->select('id_user_created')
                ->where('id_usulan_raperbup', $id_usulan_raperbup)
                ->get('usulan_raperbup')
                ->row()->id_user_created;

            $data_notif = [
                'id_user_tujuan' => $id_pengaju,
                'id_usulan_raperbup' => $id_usulan_raperbup,
                'tipe_notif' => 'approve_final_kabag',
                'pesan' => 'Usulan Anda telah disetujui dan difinalisasi oleh Kabag Hukum: ' . $nama_peraturan,
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif approve_final_kabag saved: ' . json_encode($data_notif));

            // Optional: Kirim notif ke Kasubbag dan JFT sebagai informasi
            if ($data_last_trx->id_user_created) {
                $data_notif_kasubbag = [
                    'id_user_tujuan' => $data_last_trx->id_user_created,
                    'id_usulan_raperbup' => $id_usulan_raperbup,
                    'tipe_notif' => 'info_final',
                    'pesan' => 'Usulan yang Anda periksa telah disetujui final oleh Kabag Hukum: ' . $nama_peraturan,
                ];
                $this->Notifikasi_model->simpan_notif($data_notif_kasubbag);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => $status]));
    }


    // ========================================
    // METHOD BARU: Kabag Tolak Final
    // ========================================
    public function reject_final_kabag()
    {
        $id_usulan_raperbup = decrypt_data($this->ipost("id_usulan_raperbup"));
        $catatan = $this->ipost("catatan"); // Wajib: alasan penolakan

        if (!$catatan) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Catatan penolakan wajib diisi.'
                ]));
            return;
        }

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

        // Validasi: JFT harus sudah approve
        if ($data_last_trx->jft_agree_disagree != '1') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Usulan harus disetujui JFT terlebih dahulu.'
                ]));
            return;
        }

        // Simpan transaksi baru dengan status TOLAK
        $data_trx = array(
            "usulan_raperbup_id" => $id_usulan_raperbup,
            "file_usulan_raperbup" => $data_last_trx->file_usulan_raperbup,
            "file_perbaikan" => $data_last_trx->file_perbaikan,
            "file_catatan_perbaikan" => $data_last_trx->file_catatan_perbaikan,
            "catatan_ditolak" => $catatan, // ← CATATAN PENOLAKAN
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => '3', // ← KEMBALI KE STATUS 3 (untuk perbaikan)
            "kasubbag_agree_disagree" => '1', // ← RESET untuk revisi ulang
            "jft_agree_disagree" => '1', // ← RESET untuk revisi ulang
            "kabag_agree_disagree" => '2', // ← KABAG TOLAK
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        if ($status) {
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';

            // Kirim notifikasi ke Pengaju (Admin PD)
            $id_pengaju = $this->db->select('id_user_created')
                ->where('id_usulan_raperbup', $id_usulan_raperbup)
                ->get('usulan_raperbup')
                ->row()->id_user_created;

            $data_notif = [
                'id_user_tujuan' => $id_pengaju,
                'id_usulan_raperbup' => $id_usulan_raperbup,
                'tipe_notif' => 'reject_final_kabag',
                'pesan' => 'Usulan Anda ditolak oleh Kabag Hukum: ' . $nama_peraturan . ' (Catatan: ' . $catatan . ')',
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif reject_final_kabag saved: ' . json_encode($data_notif));

            // Kirim notif ke Kasubbag untuk informasi
            if ($data_last_trx->id_user_created) {
                $data_notif_kasubbag = [
                    'id_user_tujuan' => $data_last_trx->id_user_created,
                    'id_usulan_raperbup' => $id_usulan_raperbup,
                    'tipe_notif' => 'info_tolak_kabag',
                    'pesan' => 'Usulan ditolak oleh Kabag Hukum, perlu revisi: ' . $nama_peraturan,
                ];
                $this->Notifikasi_model->simpan_notif($data_notif_kasubbag);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => $status]));
    }
}
