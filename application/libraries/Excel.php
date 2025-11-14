<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        // Load PhpSpreadsheet via autoload
        require_once APPPATH . '../vendor/autoload.php';
    }

    public function export($data, $filename = 'export.xlsx', $title = 'Laporan')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = array_keys($data[0] ?? []);
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Data
        $row = 2;
        foreach ($data as $item) {
            $col = 'A';
            foreach ($item as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Style Header
        $sheet->getStyle('A1:' . ($col ?? 'A') . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . ($col ?? 'A') . '1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCCCC');

        // Auto size
        foreach (range('A', $col ?? 'A') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_final_regulasi($data, $tahun = null, $is_contoh = false)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // === HEADER SURAT - RATA TENGAH ===
        $sheet->setCellValue('A1', 'PEMERINTAH KABUPATEN KATINGAN');
        $sheet->setCellValue('A2', 'DAFTAR PERATURAN DAERAH');
        $sheet->setCellValue('A3', 'YANG SUDAH FINAL DAN DIPUBLIKASIKAN');

        // MERGE SESUAI LEBAR TABEL (6 kolom: A sampai F)
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        // Style: rata tengah, bold, ukuran pas
        $sheet->getStyle('A1:F3')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
        $sheet->getStyle('A2:A3')->getFont()->setSize(12)->setBold(true);

        // Tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // === PERIODE & TANGGAL ===
        $periode = $tahun ? "Tahun $tahun" : "Semua Tahun";
        $sheet->setCellValue('A5', "Periode: $periode");
        $sheet->setCellValue('A6', 'Dicetak pada: ' . date('d F Y, H:i'));
        $sheet->mergeCells('A5:F5');
        $sheet->mergeCells('A6:F6');
        $sheet->getStyle('A5')->getFont()->setItalic(true);
        $sheet->getStyle('A6')->getFont()->setSize(10);

        // === TABEL HEADER ===
        $headers = ['No', 'Tahun', 'Nomor Register', 'Nama Peraturan', 'Jenis Peraturan', 'SKPD Pengusul'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i); // A, B, C...
            $cell = $col . '8';
            $sheet->setCellValue($cell, $h);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8E8E8');
        }

        // === DATA ===
        $startRow = 9;
        foreach ($data as $i => $row) {
            $rowNum = $startRow + $i;
            $sheet->setCellValue('A' . $rowNum, $i + 1);
            $sheet->setCellValue('B' . $rowNum, $row['tahun'] ?? '-');
            $sheet->setCellValue('C' . $rowNum, $row['nomor_register'] ?? '-');
            $sheet->setCellValue('D' . $rowNum, $row['nama_peraturan'] ?? '-');
            $sheet->setCellValue('E' . $rowNum, $row['nama_kategori'] ?? '-');
            $sheet->setCellValue('F' . $rowNum, $row['nama_skpd'] ?? '-');

            // Center kolom No
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // === TOTAL ===
        $totalRow = $startRow + count($data);
        $sheet->setCellValue('A' . $totalRow, 'TOTAL');
        $sheet->mergeCells("A$totalRow:B$totalRow");
        $sheet->setCellValue('C' . $totalRow, count($data) . ' Peraturan');
        $sheet->mergeCells("C$totalRow:F$totalRow");
        $sheet->getStyle("A$totalRow:F$totalRow")->getFont()->setBold(true);
        $sheet->getStyle("A$totalRow:F$totalRow")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF0F0F0');
        $sheet->getStyle("A$totalRow:F$totalRow")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // === AUTO SIZE KOLOM (INI YANG PENTING!) ===
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // === BORDER SEMUA ===
        $lastRow = $totalRow;
        $sheet->getStyle("A8:F$lastRow")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // === FREEZE HEADER ===
        $sheet->freezePane('A9');

        // === CONTOH DATA ===
        if ($is_contoh) {
            $noteRow = $lastRow + 2;
            $sheet->setCellValue('A' . $noteRow, '* Contoh data. Tidak ada data final.');
            $sheet->mergeCells("A$noteRow:F$noteRow");
            $sheet->getStyle("A$noteRow")->getFont()->setItalic(true)->getColor()->setARGB('FFFF0000');
        }

        // === OUTPUT ===
        $filename = "Daftar_Peraturan_Final_" . ($tahun ?: "Semua") . "_" . date('Ymd') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}