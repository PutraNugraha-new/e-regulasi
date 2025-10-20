<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Keputusan Bupati Katingan</title>
    <style>
        body {
            font-family: 'Bookman Old Style', serif;
            font-size: 12pt;
            margin: 20mm;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .title {
            text-transform: uppercase;
            margin-bottom: 10px;

        }

        .content {
            text-align: justify;
        }

        .content p {
            margin: 5px 0;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* Style untuk menimbang tanpa tabel - compatible dengan mPDF */
        .menimbang-section {
            margin: 10px 0;
        }

        .menimbang-label {
            float: left;
            width: 100px;
        }

        .menimbang-colon {
            float: left;
            width: 40px;
        }


        .menimbang-content ol {
            margin: 0;
            padding-left: 20px;
        }

        .menimbang-content p {
            line-height: normal;
        }

        .menimbang-content ol li {
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.5;
        }
    </style>

</head>

<body>
    <div class="header">
        <!-- Jika ada logo, gunakan path absolut -->
        <img src="<?php echo FCPATH; ?>assets/garuda.jpg" width="80" alt="Logo Kabupaten Katingan">
        <p class="uppercase">BUPATI KATINGAN <br> PROVINSI KALIMANTAN TENGAH</p>
        <p class="title">KEPUTUSAN BUPATI KATINGAN <br>
            NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TAHUN
            2026</p>
        <p class="uppercase">TENTANG</p>
        <!-- cara paling reliable untuk mPDF -->
        <table align="center" style="width:450px; border-collapse:collapse;">
            <tr>
                <td
                    style="text-align:center; white-space:normal; word-break:break-all; font-family:'Bookman Old Style', serif; font-size:12pt;">
                    <?php echo $nama_peraturan; ?>
                </td>
            </tr>
        </table>

    </div>

    <div class="content">
        <p style="text-align: center;">BUPATI KATINGAN,</p>

        <div class="menimbang-section">
            <div class="menimbang-label">Menimbang</div>
            <div class="menimbang-colon">:</div>
            <div class="menimbang-content">
                <?php echo $menimbang; ?>
            </div>
        </div>

        <div class="menimbang-section">
            <div class="menimbang-label">Mengingat</div>
            <div class="menimbang-colon">:</div>
            <div class="menimbang-content">
                <?php echo $mengingat; ?>
            </div>
        </div>

        <p style="text-align: center;">MEMUTUSKAN :</p>

        <div class="menimbang-section">
            <div class="menimbang-label">Menetapkan</div>
            <div class="menimbang-colon">:</div>
            <div class="menimbang-content">
                <?php echo $menetapkan; ?>
            </div>
        </div>

        <?php
        // Fungsi untuk membersihkan HTML content
        function clean_html_content($content)
        {
            // Menghapus \r\n dan \t (carriage return, newline, tab)
            $content = str_replace(['\r\n', '\r', '\n', '\t'], '', $content);
            // Menghapus extra whitespace
            $content = preg_replace('/\s+/', ' ', $content);
            // Trim whitespace di awal dan akhir
            $content = trim($content);
            return $content;
        }

        // Array untuk konversi angka ke kata
        $angka_kata = [
            "1" => "KESATU",
            "2" => "KEDUA",
            "3" => "KETIGA",
            "4" => "KEEMPAT",
            "5" => "KELIMA",
            "6" => "KEENAM",
            "7" => "KETUJUH",
            "8" => "KEDELAPAN",
            "9" => "KESEMBILAN",
            "10" => "KESEPULUH"
        ];

        // Loop untuk menampilkan setiap diktum
        if ($memutuskan && is_array($memutuskan)) {
            foreach ($memutuskan as $nomor => $konten) {
                $label = isset($angka_kata[$nomor]) ? $angka_kata[$nomor] : "KE-" . $nomor;
                // Bersihkan konten HTML
                $clean_content = clean_html_content($konten);

                echo '<div class="menimbang-section">';
                echo '<div class="menimbang-label">' . $label . '</div>';
                echo '<div class="menimbang-colon">:</div>';
                echo '<div class="menimbang-content">';
                echo $clean_content;
                echo '</div>';
                echo '</div>';
            }
        }
        ?>

        <!-- Tabel untuk tempat, tanggal, dan jabatan -->
        <div style="margin-top: 40px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 65%; border: none;">&nbsp;</td>
                    <td style="width: 35%; border: none;">
                        <p style="margin: 5px 0; text-align: left;">Ditetapkan di Kasongan</p>
                        <p style="margin: 5px 0; text-align: left;">pada tanggal</p>
                        <br>
                        <p style="margin: 0 0; text-transform: uppercase; text-align: center;">BUPATI KATINGAN,</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tabel terpisah untuk nama -->
        <div style="margin-top: 80px; margin-bottom: 20px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 55%; border: none;">&nbsp;</td>
                    <td style="width: 55%; border: none; text-align: center;">
                        SAIFUL
                    </td>
                </tr>
            </table>
        </div>
        <br><br>

        <?php echo $tembusan; ?>


    </div>
</body>

</html>