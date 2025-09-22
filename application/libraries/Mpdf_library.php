<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpdf_library
{
    public $mpdf;

    public function __construct()
    {
        require_once FCPATH . 'vendor/autoload.php'; // Memuat autoloader Composer

        $this->mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', // Encoding UTF-8 untuk mendukung karakter khusus
            'format' => [215, 330], // Ukuran kertas kustom: 21,5 cm x 33 cm (dalam mm)
            'orientation' => 'P', // Orientasi Portrait
            'margin_left' => 20, // Margin kiri: 2 cm = 20 mm
            'margin_right' => 25, // Margin kanan: 2,5 cm = 25 mm
            'margin_top' => 20, // Margin atas: 2 cm = 20 mm
            'margin_bottom' => 20, // Margin bawah: 2 cm = 20 mm
            'default_font' => 'bookman', // Font default
            'fontDir' => [FCPATH . 'application/third_party/mpdf/ttfonts'], // Direktori font khusus
            'fontdata' => [
                'bookman' => [
                    'R' => 'BOOKOS.ttf', // File font reguler
                ]
            ],
            'autoScriptToLang' => true, // Dukungan otomatis untuk bahasa
            'autoLangToFont' => true, // Pemilihan font otomatis berdasarkan bahasa
            'setAutoTopMargin' => 'stretch', // Menyesuaikan margin atas
            'setAutoBottomMargin' => 'stretch', // Menyesuaikan margin bawah
            'use_kwt' => true, // Mengaktifkan rendering lebih baik untuk tabel dan daftar
            'ignore_invalid_utf8' => false, // Memastikan UTF-8 diproses dengan benar
            'list_auto_mode' => 'browser'
        ]);
    }


    // Fungsi untuk menggabungkan beberapa file PDF
    public function merge_pdfs($pdf_files, $output_path)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [215, 330],
            'orientation' => 'P',
            'margin_left' => 20,
            'margin_right' => 25,
            'margin_top' => 20,
            'margin_bottom' => 20
        ]);

        foreach ($pdf_files as $file) {
            if (file_exists($file)) {
                $pagecount = $mpdf->SetSourceFile($file);
                for ($i = 1; $i <= $pagecount; $i++) {
                    $mpdf->AddPage();
                    $tplId = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($tplId);
                }
            } else {
                log_message('error', "File PDF tidak ditemukan: $file");
                return false;
            }
        }

        $mpdf->Output($output_path, 'F');
        return $output_path;
    }
}
