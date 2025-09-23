<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Peraturan Bupati Katingan</title>
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

        /* Style untuk bagian */
        .bagian-label {
            float: left;
            width: 100px;
        }

        .bagian-colon {
            float: left;
            width: 40px;
        }

        .bagian-content ol {
            margin: 0;
            padding-left: 20px;
        }

        .bagian-content p {
            line-height: normal;
        }

        .bagian-content ol li {
            margin-bottom: 10px;
            text-align: justify;
            line-height: normal;
        }

        /* Style untuk pasal */
        .pasal-label,
        .pasal-colon {
            visibility: hidden;
        }

        .pasal-label {
            float: left;
            width: 100px;
        }

        .pasal-colon {
            float: left;
            width: 40px;
        }

        .pasal-content ol {
            margin: 0;
            padding-left: 20px;
        }

        .pasal-content p {
            line-height: normal;
        }

        .pasal-content ol li {
            margin-bottom: 10px;
            text-align: justify;
            line-height: normal;
        }

        /* Style untuk judul yang rata tengah dari konten menggunakan margin offset */
        .centered-title-container {
            margin: 20px 0;
            margin-left: 140px;
            /* Offset sebesar lebar label + colon (100px + 40px) */
        }

        .centered-title-content {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- Jika ada logo, gunakan path absolut -->
        <img src="<?php echo FCPATH; ?>assets/garuda.jpg" width="80" alt="Logo Kabupaten Katingan">
        <p class="uppercase">BUPATI KATINGAN <br> PROVINSI KALIMANTAN TENGAH</p>
        <p class="title">PERATURAN BUPATI KATINGAN <br>
            NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TAHUN
            2026</p>
        <p class="uppercase">TENTANG</p>
        <!-- cara paling reliable untuk mPDF -->
        <table align="center" style="width:450px; border-collapse:collapse;">
            <tr>
                <td style="text-align:center; white-space:normal; word-break:break-all; font-family:'Bookman Old Style', serif; font-size:12pt;">
                    <?php echo htmlspecialchars($nama_peraturan); ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p style="text-align: center;">DENGAN RAHMAT TUHAN YANG MAHA ESA</p>
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

        <!-- Judul MEMUTUSKAN rata tengah dari konten -->
        <div class="centered-title-container">
            <div class="centered-title-content">
                <p style="margin: 0;">MEMUTUSKAN :</p>
            </div>
        </div>

        <div class="menimbang-section">
            <div class="menimbang-label">Menetapkan</div>
            <div class="menimbang-colon">:</div>
            <div class="menimbang-content">
                <?php echo $menetapkan; ?>
            </div>
        </div>

        <!-- Bagian Bab, Bagian, dan Pasal -->
        <?php if (!empty($bab_pasal_data) && is_array($bab_pasal_data) || !empty($judul_bagian) && is_array($judul_bagian)): ?>
            <?php
            // Function terbilang untuk angka Romawi
            function romawi($angka)
            {
                $romawi = array(
                    1 => 'I',
                    2 => 'II',
                    3 => 'III',
                    4 => 'IV',
                    5 => 'V',
                    6 => 'VI',
                    7 => 'VII',
                    8 => 'VIII',
                    9 => 'IX',
                    10 => 'X',
                    11 => 'XI',
                    12 => 'XII',
                    13 => 'XIII',
                    14 => 'XIV',
                    15 => 'XV',
                    16 => 'XVI',
                    17 => 'XVII',
                    18 => 'XVIII',
                    19 => 'XIX',
                    20 => 'XX'
                );
                return isset($romawi[$angka]) ? $romawi[$angka] : $angka;
            }

            // Function terbilang untuk nama bagian
            function terbilang_bagian($angka)
            {
                $terbilang = array(
                    1 => 'Kesatu',
                    2 => 'Kedua',
                    3 => 'Ketiga',
                    4 => 'Keempat',
                    5 => 'Kelima',
                    6 => 'Keenam',
                    7 => 'Ketujuh',
                    8 => 'Kedelapan',
                    9 => 'Kesembilan',
                    10 => 'Kesepuluh',
                    11 => 'Kesebelas',
                    12 => 'Kedua Belas',
                    13 => 'Ketiga Belas',
                    14 => 'Keempat Belas',
                    15 => 'Kelima Belas',
                    16 => 'Keenam Belas',
                    17 => 'Ketujuh Belas',
                    18 => 'Kedelapan Belas',
                    19 => 'Kesembilan Belas',
                    20 => 'Kedua Puluh'
                );
                return isset($terbilang[$angka]) ? $terbilang[$angka] : "Ke-$angka";
            }

            // Mengelompokkan pasal berdasarkan bab dan bagian
            $grouped_data = [];
            foreach ($bab_pasal_data as $bab_number => $bab) {
                $grouped_data[$bab_number] = [
                    'judul' => $bab['judul'],
                    'bagian' => [],
                    'pasal_tanpa_bagian' => []
                ];
                // Inisialisasi bagian jika ada
                if (!empty($judul_bagian[$bab_number]) && is_array($judul_bagian[$bab_number])) {
                    foreach ($judul_bagian[$bab_number] as $bagian_number => $judul_bagian) {
                        $grouped_data[$bab_number]['bagian'][$bagian_number] = [
                            'judul' => $judul_bagian,
                            'pasal' => []
                        ];
                    }
                }
            }

            // Mengelompokkan pasal ke dalam bab dan bagian
            foreach ($bab_pasal_data as $bab_number => $bab) {
                if (!empty($bab['pasal']) && is_array($bab['pasal'])) {
                    foreach ($bab['pasal'] as $pasal_number => $pasal) {
                        $bagian_number = isset($pasal['bagian']) ? $pasal['bagian'] : 0;
                        if ($bagian_number == 0) {
                            // Pasal tanpa bagian
                            $grouped_data[$bab_number]['pasal_tanpa_bagian'][$pasal_number] = [
                                'isi' => $pasal['isi']
                            ];
                        } else {
                            // Pasal dalam bagian
                            if (isset($grouped_data[$bab_number]['bagian'][$bagian_number])) {
                                $grouped_data[$bab_number]['bagian'][$bagian_number]['pasal'][$pasal_number] = [
                                    'isi' => $pasal['isi']
                                ];
                            }
                        }
                    }
                }
            }
            ?>

            <?php foreach ($grouped_data as $bab_number => $bab): ?>
                <!-- Judul Bab rata tengah dari konten -->
                <div class="centered-title-container">
                    <div class="centered-title-content">
                        <p style="margin:0;">BAB <?php echo romawi($bab_number); ?></p>
                        <p style="margin:0;text-transform: uppercase;"><?php echo htmlspecialchars($bab['judul']); ?></p>
                    </div>
                </div>

                <!-- Bagian dalam Bab -->
                <?php if (!empty($bab['bagian']) && is_array($bab['bagian'])): ?>
                    <?php foreach ($bab['bagian'] as $bagian_number => $bagian): ?>
                        <!-- Judul Bagian rata tengah dari konten -->
                        <div class="centered-title-container">
                            <div class="centered-title-content">
                                <p style="margin:0;">Bagian <?php echo terbilang_bagian($bagian_number); ?></p>
                                <p style="margin:0;text-transform: uppercase;"><?php echo htmlspecialchars($bagian['judul']); ?></p>
                            </div>
                        </div>

                        <!-- Pasal-pasal dalam Bagian -->
                        <?php if (!empty($bagian['pasal']) && is_array($bagian['pasal'])): ?>
                            <?php foreach ($bagian['pasal'] as $pasal_number => $pasal): ?>
                                <!-- Judul Pasal rata tengah dari konten -->
                                <div class="centered-title-container">
                                    <div class="centered-title-content">
                                        <p style="margin:0;">Pasal <?php echo $pasal_number; ?></p>
                                    </div>
                                </div>

                                <div class="menimbang-section">
                                    <div class="pasal-label">Pasal</div>
                                    <div class="pasal-colon">:</div>
                                    <div class="pasal-content">
                                        <?php echo $pasal['isi']; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Pasal-pasal tanpa Bagian -->
                <?php if (!empty($bab['pasal_tanpa_bagian']) && is_array($bab['pasal_tanpa_bagian'])): ?>
                    <?php foreach ($bab['pasal_tanpa_bagian'] as $pasal_number => $pasal): ?>
                        <!-- Judul Pasal rata tengah dari konten -->
                        <div class="centered-title-container">
                            <div class="centered-title-content">
                                <p style="margin:0;">Pasal <?php echo $pasal_number; ?></p>
                            </div>
                        </div>

                        <div class="menimbang-section">
                            <div class="pasal-label">Pasal</div>
                            <div class="pasal-colon">:</div>
                            <div class="pasal-content">
                                <?php echo $pasal['isi']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada data bab atau bagian yang tersedia.</p>
        <?php endif; ?>

        <!-- Penjelasan (jika ada) -->
        <?php if (!empty($penjelasan)): ?>
            <!-- Judul Penjelasan rata tengah dari konten -->
            <div class="centered-title-container">
                <div class="centered-title-content">
                    <p style="margin:0;">PENJELASAN</p>
                    <p style="margin:0;text-transform: uppercase;">PERATURAN BUPATI KATINGAN NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TAHUN 2026</p>
                    <p style="margin:0;text-transform: uppercase;">TENTANG <?php echo htmlspecialchars($nama_peraturan); ?></p>
                </div>
            </div>

            <div class="menimbang-section">
                <div class="menimbang-content">
                    <?php echo $penjelasan; ?>
                </div>
            </div>
        <?php endif; ?>

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

        <div style="margin-top: 40px;">
            <table style="border: none;">
                <tr>
                    <td style="border: none;">
                        <p style="margin: 5px 0; text-align: left;">Diundangkan di Kasongan</p>
                        <p style="margin: 5px 0; text-align: left;">pada tanggal</p>
                        <br>
                        <p style="margin: 0 0; text-transform: uppercase; text-align: center;">SEKRETARIS DAERAH <br>
                            KABUPATEN KATINGAN,
                        </p>
                        <br><br><br>
                        <p style="margin: 0 0; text-transform: uppercase; text-align: center;">
                            PRANSANG
                        </p>
                        <br>
                        <p style="margin: 0 0; text-transform: uppercase; text-align: center;">
                            BERITA DAERAH KABUPATEN KATINGAN TAHUN 2026 NOMOR
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>