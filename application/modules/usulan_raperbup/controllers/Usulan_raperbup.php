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
        $this->load->library('mpdf_library'); // Memuat library mPDF
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
            if (in_array(decrypt_data($this->ipost("kategori_usulan")), array("1", "2"))) {
                // Perda & Perbup
                $input_name_lampiran = "file_lampiran";
                $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc,pdf");

                if (!isset($upload_file_lampiran['error'])) {
                    $input_name_lampiran_sk_tim = "file_lampiran_sk_tim";
                    $upload_file_lampiran_sk_tim = $this->upload_file($input_name_lampiran_sk_tim, $this->config->item('file_lampiran'), "", "doc,pdf");

                    if (!isset($upload_file_lampiran_sk_tim['error'])) {
                        $input_name_lampiran_daftar_hadir = "file_lampiran_daftar_hadir";
                        $upload_file_lampiran_daftar_hadir = $this->upload_file($input_name_lampiran_daftar_hadir, $this->config->item('file_lampiran'), "", "doc,pdf");

                        if (!isset($upload_file_lampiran_daftar_hadir['error'])) {
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
                                "bab_pasal_data" => json_encode($bab_pasal_data), // ENCODE JSON
                                "penjelasan" => $this->input->post("penjelasan"),
                                "lampiran" => $upload_file_lampiran['data']['file_name'],
                                "lampiran_sk_tim" => $upload_file_lampiran_sk_tim['data']['file_name'],
                                "lampiran_daftar_hadir" => $upload_file_lampiran_daftar_hadir['data']['file_name'],
                                "kategori_usulan_id" => decrypt_data($this->ipost("kategori_usulan")),
                                'created_at' => $this->datetime(),
                                "id_user_created" => $this->session->userdata("id_user")
                            );

                            $status = $this->usulan_raperbup_model->save($data);
                            if ($status) {
                                $data_trx = array(
                                    "usulan_raperbup_id" => $status,
                                    "level_user_id_status" => $this->session->userdata("level_user_id"),
                                    "status_tracking" => "1",
                                    'created_at' => $this->datetime(),
                                    "id_user_created" => $this->session->userdata("id_user")
                                );

                                $trx_id = $this->trx_raperbup_model->save($data_trx);
                                // Generate PDF tanpa download - hanya simpan file
                                $this->generate_pdf_raperbup($status, $trx_id, 'F');
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
                // Kepbup
                $input_name_lampiran = "file_lampiran";
                $input_name_lampiran_usulan = "file_lampiran_usulan";

                // Upload file lampiran (pendukung, bisa doc atau pdf)
                $upload_file_lampiran = $this->upload_file($input_name_lampiran, $this->config->item('file_lampiran'), "", "doc,pdf");

                // Upload file lampiran_usulan (untuk digabung, harus pdf)
                $upload_file_lampiran_usulan = $this->upload_file($input_name_lampiran_usulan, $this->config->item('file_lampiran'), "", "pdf");

                if (!isset($upload_file_lampiran['error']) && !isset($upload_file_lampiran_usulan['error'])) {
                    $keputusan = $this->input->post("keputusan");
                    $keputusan_string = is_array($keputusan) ? json_encode($keputusan) : $keputusan;
                    $data = array(
                        "nama_peraturan" => $this->ipost("nama_peraturan"),
                        "menimbang" => $this->input->post("menimbang"),
                        "mengingat" => $this->input->post("mengingat"),
                        "menetapkan" => $this->input->post("menetapkan"),
                        "memutuskan" => $keputusan_string,
                        "tembusan" => $this->input->post("tembusan"),
                        "lampiran" => $upload_file_lampiran['data']['file_name'],
                        "lampiran_usulan" => $upload_file_lampiran_usulan['data']['file_name'],
                        "kategori_usulan_id" => decrypt_data($this->ipost("kategori_usulan")),
                        'created_at' => $this->datetime(),
                        "id_user_created" => $this->session->userdata("id_user")
                    );
                    $status = $this->usulan_raperbup_model->save($data);
                    if ($status) {
                        $data_trx = array(
                            "usulan_raperbup_id" => $status,
                            "level_user_id_status" => $this->session->userdata("level_user_id"),
                            "status_tracking" => "1",
                            'created_at' => $this->datetime(),
                            "id_user_created" => $this->session->userdata("id_user")
                        );

                        $trx_id = $this->trx_raperbup_model->save($data_trx);
                        // Generate PDF tanpa download - hanya simpan file
                        $this->generate_pdf_raperbup($status, $trx_id, 'F');
                        $this->session->set_flashdata('message', 'Data baru berhasil ditambahkan');
                        $this->session->set_flashdata('type-alert', 'success');
                        redirect('usulan_raperbup');
                    } else {
                        $this->session->set_flashdata('message', 'Data baru gagal ditambahkan');
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('usulan_raperbup');
                    }
                } else {
                    $error_message = isset($upload_file_lampiran['error']) ? $upload_file_lampiran['error'] : $upload_file_lampiran_usulan['error'];
                    $this->session->set_flashdata('message', $error_message);
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('usulan_raperbup/tambah_usulan_raperbup');
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
                        'pasal' => array()
                    );
                }

                // Distribusikan pasal ke bab dan bagian yang sesuai berdasarkan mapping
                if (!empty($isi_pasal) && !empty($pasal_bab_mapping)) {
                    foreach ($isi_pasal as $pasal_number => $isi) {
                        $bab_number = isset($pasal_bab_mapping[$pasal_number]) ? $pasal_bab_mapping[$pasal_number] : null;
                        $bagian_number = isset($pasal_bagian_mapping[$pasal_number]) ? $pasal_bagian_mapping[$pasal_number] : 0;
                        if ($bab_number && isset($bab_pasal_data[$bab_number])) {
                            $bab_pasal_data[$bab_number]['pasal'][$pasal_number] = array(
                                'isi' => $isi,
                                'bagian' => $bagian_number
                            );
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
                'judul_bagian' => $judul_bagian,
                'pasal_bab_mapping' => $pasal_bab_mapping,
                'pasal_bagian_mapping' => $pasal_bagian_mapping,
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

        var_dump($data_master);
        die;

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
