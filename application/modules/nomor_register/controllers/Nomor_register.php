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
        $this->load->model('Kategori_usulan_model', 'kategori_usulan_model');
        $this->load->model('Usulan_revisi_model');
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

        $data['kategori_usulan'] = $this->kategori_usulan_model->get_all();
        $data['tahun'] = $this->usulan_raperbup_model->get_tahun_unik();

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

        if ($data_last_trx && $data_last_trx->status_tracking == "3") {
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
                $data_trx = array(
                    "catatan_ditolak" => $this->ipost("catatan_disposisi"),
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $this->trx_raperbup_model->edit($cek_disposisi->id_trx_raperbup, $data_trx);
            } else {
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

    public function cancel_usulan()
    {
        $id_usulan_raperbup = decrypt_data($this->ipost("id_usulan_raperbup"));
        $catatan_pembatalan = $this->ipost("catatan_pembatalan");

        // Ambil level user dari database
        $level_user = $this->db->select('nama_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();

        if (!$level_user || $level_user->nama_level_user != 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Hanya Admin Hukum yang dapat membatalkan usulan']));
            return;
        }

        // Cek status terakhir
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

        if ($data_last_trx && in_array($data_last_trx->status_tracking, ['5', '6'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Usulan sudah dipublish atau dibatalkan']));
            return;
        }

        // Simpan transaksi pembatalan
        $data_trx = array(
            "usulan_raperbup_id" => $id_usulan_raperbup,
            "file_usulan_raperbup" => $data_last_trx ? $data_last_trx->file_usulan_raperbup : NULL,
            "catatan_ditolak" => $catatan_pembatalan,
            "level_user_id_status" => $this->session->userdata("level_user_id"),
            "status_tracking" => "6",
            'created_at' => $this->datetime(),
            "id_user_created" => $this->session->userdata("id_user")
        );

        $status = $this->trx_raperbup_model->save($data_trx);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => $status, 'message' => $status ? 'Usulan berhasil dibatalkan' : 'Gagal membatalkan usulan']));
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
            // Untuk Perda & Perbup - decode bab_pasal_data
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

            // Tambahkan penjelasan hanya untuk Perda (kategori_usulan_id = 1)
            if ($data_usulan->kategori_usulan_id == 1) {
                $data['penjelasan'] = isset($data_usulan->penjelasan) ? $data_usulan->penjelasan : '';
            }
        } else {
            // Untuk Kepbup
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

        // Konfigurasi header khusus untuk Perda & Perbup (seperti di preview)
        if ($data_usulan->kategori_usulan_id == 1 || $data_usulan->kategori_usulan_id == 2) {
            // Header dengan nomor halaman untuk halaman genap dan ganjil
            $header_html = '- {PAGENO} -';
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'E', true);
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'O', true);

            // Paksa header di halaman pertama menjadi kosong
            $this->mpdf_library->mpdf->SetHTMLHeader('', 'first');
        }

        $this->mpdf_library->mpdf->WriteHTML($html);

        // Nama file PDF sementara untuk dokumen utama
        $pdf_file_name = 'Keputusan_Bupati_' . str_replace(' ', '_', $data_usulan->nama_peraturan) . '_' . time() . '.pdf';
        $pdf_path = FCPATH . 'assets/file_usulan/' . $pdf_file_name;

        // Simpan PDF dokumen utama
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

        // Jika kategori adalah Kepbup (kategori_usulan_id = 3), gabungkan dengan lampiran_usulan
        if ($data_usulan->kategori_usulan_id == 3 && !empty($data_usulan->lampiran_usulan)) {
            $lampiran_path = FCPATH . $this->config->item('file_lampiran') . '/' . $data_usulan->lampiran_usulan;
            $merged_pdf_path = FCPATH . 'assets/file_usulan/merged_' . $pdf_file_name;

            // Validasi file
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

            // Gabungkan PDF
            $pdf_files = [$pdf_path, $lampiran_path];
            $merge_result = $this->mpdf_library->merge_pdfs($pdf_files, $merged_pdf_path);

            if ($merge_result) {
                // Hapus file PDF sementara (dokumen utama)
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

        // Update data transaksi dengan nama file PDF
        $this->trx_raperbup_model->edit($trx_id, array(
            'file_usulan_raperbup' => $pdf_file_name,
            'updated_at' => $this->datetime(),
            'id_user_updated' => $this->session->userdata("id_user")
        ));

        // Jika output_mode adalah untuk preview atau download khusus, tampilkan PDF
        if ($output_mode === 'I' || $output_mode === 'D') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: ' . ($output_mode === 'D' ? 'attachment' : 'inline') . '; filename="' . $pdf_file_name . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            // Jika kategori adalah Kepbup, kirim file yang sudah digabung
            if ($data_usulan->kategori_usulan_id == 3 && !empty($data_usulan->lampiran_usulan)) {
                // Langsung kirim file hasil penggabungan dari disk
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
                // Untuk kategori lain (Perda & Perbup), baca file dari disk karena sudah disimpan
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
        // Jika output_mode adalah 'F' (File), hanya simpan file tanpa output ke browser
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

        $data_revisi = $this->Usulan_revisi_model->get_all_revisi(decrypt_data($id_usulan_raperbup));
        $data['data_revisi'] = $data_revisi;

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

                $data['url_preview_lampiran_usulan'] = "";
                if ($data_master[0]->lampiran_usulan) {
                    $ekstensi_file_lampiran_usulan = explode(".", $data_master[0]->lampiran_usulan);
                    $data['url_preview_lampiran_usulan'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_lampiran") . "/" . $data_master[0]->lampiran_usulan . "','" . $ekstensi_file_lampiran_usulan[1] . "')\">View</button>";
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

                // Cek kategori usulan untuk menentukan field yang perlu diupdate
                $kategori_id = decrypt_data($this->ipost("kategori_usulan"));

                if (in_array($kategori_id, array("1", "2"))) {
                    // Perda & Perbup
                    $nama_file_lampiran = $data_master[0]->lampiran;
                    if (!empty($_FILES['file_lampiran']['name'])) {
                        $input_name_lampiran = "file_lampiran";
                        $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc,pdf");
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
                        $upload_file_lampiran_sk_tim = $this->upload_file($input_name_lampiran_sk_tim, $this->config->item('file_lampiran'), "", "doc,pdf");
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
                        $upload_file_lampiran_daftar_hadir = $this->upload_file($input_name_lampiran_daftar_hadir, $this->config->item('file_lampiran'), "", "doc,pdf");
                        if (isset($upload_file_lampiran_daftar_hadir['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran_daftar_hadir['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_lampiran_daftar_hadir = $upload_file_lampiran_daftar_hadir['data']['file_name'];
                    }

                    // Ambil data bab dan pasal dari form
                    $judul_bab = $this->input->post("judul_bab"); // array
                    $isi_pasal = $this->input->post("isi_pasal"); // array
                    $pasal_bab_mapping = $this->input->post("pasal_bab_mapping"); // array mapping pasal ke bab

                    // Buat struktur JSON untuk bab_pasal_data
                    $bab_pasal_data = array();

                    if (!empty($judul_bab)) {
                        // Inisialisasi struktur bab
                        foreach ($judul_bab as $bab_number => $judul) {
                            $bab_pasal_data[$bab_number] = array(
                                'judul' => $judul,
                                'pasal' => array()
                            );
                        }

                        // Distribusikan pasal ke bab yang sesuai berdasarkan mapping
                        if (!empty($isi_pasal) && !empty($pasal_bab_mapping)) {
                            foreach ($isi_pasal as $pasal_number => $isi) {
                                $bab_number = $pasal_bab_mapping[$pasal_number];
                                if (isset($bab_pasal_data[$bab_number])) {
                                    $bab_pasal_data[$bab_number]['pasal'][$pasal_number] = array(
                                        'isi' => $isi
                                    );
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
                    // Kepbup
                    $nama_file_lampiran = $data_master[0]->lampiran;
                    if (!empty($_FILES['file_lampiran']['name'])) {
                        $input_name_lampiran = "file_lampiran";
                        $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc,pdf");
                        if (isset($upload_file_lampiran['error'])) {
                            $this->session->set_flashdata('message', $upload_file_lampiran['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
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
                            redirect('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup);
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

                $this->usulan_raperbup_model->edit(decrypt_data($id_usulan_raperbup), $data);

                $data_trx = array(
                    "file_usulan_raperbup" => $nama_file_usulan,
                    'updated_at' => $this->datetime(),
                    "id_user_updated" => $this->session->userdata("id_user")
                );

                $status = $this->trx_raperbup_model->edit($data_master[0]->id_trx_raperbup, $data_trx);
                if ($status) {
                    // Regenerate PDF setelah edit
                    $this->generate_pdf_raperbup(decrypt_data($id_usulan_raperbup), $data_master[0]->id_trx_raperbup, 'F');

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
}
