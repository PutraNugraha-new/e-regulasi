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

        $leve_user = $this->db->select('id_level_user')
            ->from('level_user')
            ->where('id_level_user', $this->session->userdata("level_user_id"))
            ->get()
            ->row();
        $data['level_user'] = $leve_user ? $leve_user->id_level_user : null;

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

            if ($status) {
                $id_usulan_raperbup_decrypted = decrypt_data($this->ipost("id_usulan_raperbup"));
                $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup_decrypted)->nama_peraturan ?: 'Usulan Tanpa Nama';

                // Notif ke Kasubbag
                $kasubbag_id = decrypt_data($this->ipost("id_kasubbag"));
                if ($kasubbag_id) {
                    $data_notif = [
                        'id_user_tujuan' => $kasubbag_id,
                        'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                        'tipe_notif' => 'disposisi',
                        'pesan' => 'Usulan disetujui oleh Admin Hukum dan diteruskan untuk koreksi: ' . $nama_peraturan . ($this->ipost("catatan_disposisi") ? ' (Catatan: ' . $this->ipost("catatan_disposisi") . ')' : ''),
                        'created_at' => $this->datetime()
                    ];
                    $this->Notifikasi_model->simpan_notif($data_notif);
                    log_message('debug', 'Notif disposisi saved for Kasubbag: ' . json_encode($data_notif));
                } else {
                    log_message('error', 'No Kasubbag selected for usulan: ' . $id_usulan_raperbup_decrypted);
                }

                // Notif ke Admin PD
                $id_pengaju = $this->db->select('id_user_created')
                    ->where('id_usulan_raperbup', $id_usulan_raperbup_decrypted)
                    ->get('usulan_raperbup')
                    ->row()->id_user_created;
                $data_notif = [
                    'id_user_tujuan' => $id_pengaju,
                    'id_usulan_raperbup' => $id_usulan_raperbup_decrypted,
                    'tipe_notif' => 'disposisi',
                    'pesan' => 'Usulan Anda disetujui oleh Admin Hukum dan diteruskan ke Kasubbag: ' . $nama_peraturan . ($this->ipost("catatan_disposisi") ? ' (Catatan: ' . $this->ipost("catatan_disposisi") . ')' : ''),
                    'created_at' => $this->datetime()
                ];
                $this->Notifikasi_model->simpan_notif($data_notif);
                log_message('debug', 'Notif disposisi saved for Admin PD: ' . json_encode($data_notif));
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

        if ($status) {
            // Ambil nama peraturan untuk pesan notifikasi
            $nama_peraturan = $this->usulan_raperbup_model->get_by($id_usulan_raperbup)->nama_peraturan ?: 'Usulan Tanpa Nama';

            // Trigger notifikasi ke Admin Perangkat Daerah (pengaju)
            $id_pengaju = $this->db->select('id_user_created')
                ->where('id_usulan_raperbup', $id_usulan_raperbup)
                ->get('usulan_raperbup')
                ->row()->id_user_created;
            $data_notif = [
                'id_user_tujuan' => $id_pengaju,
                'id_usulan_raperbup' => $id_usulan_raperbup,
                'tipe_notif' => 'usulan_dibatalkan',
                'pesan' => 'Usulan "' . $nama_peraturan . '" dibatalkan oleh Admin Hukum. Catatan: ' . $catatan_pembatalan,
            ];
            $this->Notifikasi_model->simpan_notif($data_notif);
            log_message('debug', 'Notif usulan_dibatalkan saved: ' . json_encode($data_notif));
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => $status, 'message' => $status ? 'Usulan berhasil dibatalkan' : 'Gagal membatalkan usulan']));
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

            // Data untuk template Peraturan Bupati
            $data = array(
                'nama_peraturan' => $nama_peraturan,
                'menimbang' => $menimbang,
                'mengingat' => $mengingat,
                'menetapkan' => $menetapkan,
                'bab_pasal_data' => $bab_pasal_data,
                'judul_bab' => $judul_bab,
                'judul_bagian' => $judul_bagian,
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
            // Atur header dengan nomor halaman untuk halaman genap dan ganjil
            $header_html = '- {PAGENO} -';
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'E', true);
            $this->mpdf_library->mpdf->SetHTMLHeader($header_html, 'O', true);

            // Paksa header di halaman pertama menjadi kosong
            $this->mpdf_library->mpdf->SetHTMLHeader('', 'first');

            // Tambahkan konten utama
            $this->mpdf_library->mpdf->WriteHTML($html);
        } else {
            $this->mpdf_library->mpdf->WriteHTML($html);
        }

        // Nama file PDF sementara untuk preview
        $pdf_file_name = 'Preview_Peraturan_Bupati_' . time() . '.pdf';

        // Bersihkan output buffer sebelum mengirim header PDF
        ob_clean();

        // Output PDF untuk preview (inline)
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $pdf_file_name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        $this->mpdf_library->mpdf->Output($pdf_file_name, 'I');
        exit; // Pastikan tidak ada kode lain yang dieksekusi setelah ini
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

        $pdf_file_name = str_replace(' ', '_', $data_usulan->nama_peraturan) . '_' . time() . '.pdf';
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
            'file_usulan_raperbup' => $pdf_file_name,
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
                        redirect('nomor_register');
                    } else {
                        $input_name = "file_upload";
                        $upload_file = $this->upload_file($input_name, $this->config->item('file_usulan'), "", "doc");
                        if (isset($upload_file['error'])) {
                            $this->session->set_flashdata('message', $upload_file['error']);
                            $this->session->set_flashdata('type-alert', 'danger');
                            redirect('nomor_register/edit_usulan_raperbup/' . $id_usulan_raperbup);
                        }

                        $nama_file_usulan = $upload_file['data']['file_name'];
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
                            redirect('nomor_register/edit_usulan_raperbup/' . $id_usulan_raperbup);
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
                            redirect('nomor_register/edit_usulan_raperbup/' . $id_usulan_raperbup);
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
                            redirect('nomor_register/edit_usulan_raperbup/' . $id_usulan_raperbup);
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
                            redirect('nomor_register/edit_usulan_raperbup/' . $id_usulan_raperbup);
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
                            redirect('nomor_register/edit_usulan_raperbup/' . $id_usulan_raperbup);
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

                    // Trigger notifikasi ke Admin Perangkat Daerah (pengaju)
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
                    redirect('nomor_register');
                } else {
                    $this->session->set_flashdata('message', 'Data gagal diubah');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('nomor_register');
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
        if (!in_array($level_user->id_level_user, [4, 6, 7])) {
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
        if (!in_array($level_user->id_level_user, [4, 6, 7])) {
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
        if (!in_array($level_user->id_level_user, [4, 6, 7])) {
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
}
