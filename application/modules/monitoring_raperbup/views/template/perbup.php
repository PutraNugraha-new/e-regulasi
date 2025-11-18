<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Peraturan Bupati Katingan</title>
    <style>
        body {
            font-family: 'Bookman Old Style', serif;
            font-size: 12pt;
            /* margin: 20mm; */
            line-height: 1.3;
        }

        /* Atur margin untuk header */
        @page {
            margin-top: 30mm;
            margin-bottom: 20mm;
            margin-left: 20mm;
            margin-right: 20mm;

            header: html_myheader;
        }

        /* Halaman pertama tanpa header */
        @page :first {
            margin-top: 20mm;
            header: html_myheaderfirst;
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
            line-height: 1.3;
        }

        .menimbang-content ol li {
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.3;
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
            line-height: 1.3;
        }

        .bagian-content ol li {
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.3;
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
            line-height: 1.3;
        }

        .pasal-content ol li {
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.3;
        }

        /* Style untuk judul yang rata tengah dari konten menggunakan margin offset */
        .centered-title-container {
            margin-left: 140px;
            /* Offset sebesar lebar label + colon (100px + 40px) */
        }

        .centered-title-content {
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header kosong untuk halaman pertama -->
    <htmlpageheader name="myheaderfirst">
    </htmlpageheader>

    <!-- Header dengan nomor halaman untuk halaman 2 dst -->
    <htmlpageheader name="myheader">
        <div style="margin-left: 140px; font-family: 'Bookman Old Style', serif; font-size: 12pt;">
            <div style="text-align: center;">- {PAGENO} -</div>
        </div>
    </htmlpageheader>

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
        <?php
        // Deteksi mode: preview atau generate
        // Preview: ada $judul_bagian dari POST
        // Generate: ada $bab_pasal_data dari database yang sudah di-decode
        $is_preview = isset($judul_bagian) && is_array($judul_bagian);
        $is_generate = isset($bab_pasal_data) && is_array($bab_pasal_data) && !$is_preview;

        if ($is_preview || $is_generate):
        ?>
            <?php
            // Function terbilang untuk angka Romawi
            if (!function_exists('romawi')) {
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
            }

            // Function terbilang untuk nama bagian
            if (!function_exists('terbilang_bagian')) {
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
            }

            // ========== MODE PREVIEW (dari POST form) ==========
            if ($is_preview) {
                // Mengelompokkan data dari POST
                $grouped_data = [];

                // Inisialisasi bab dari $judul_bab
                if (isset($judul_bab) && is_array($judul_bab)) {
                    foreach ($judul_bab as $bab_number => $judul_bab_text) {
                        $grouped_data[$bab_number] = [
                            'judul' => $judul_bab_text,
                            'bagian' => [],
                            'pasal_tanpa_bagian' => []
                        ];
                    }
                }

                // Tambahkan bagian dari $judul_bagian
                if (isset($judul_bagian) && is_array($judul_bagian)) {
                    foreach ($judul_bagian as $bab_number => $bagian_array) {
                        if (is_array($bagian_array)) {
                            foreach ($bagian_array as $bagian_number => $judul_bagian_text) {
                                if (isset($grouped_data[$bab_number])) {
                                    $grouped_data[$bab_number]['bagian'][$bagian_number] = [
                                        'judul' => $judul_bagian_text,
                                        'pasal' => []
                                    ];
                                }
                            }
                        }
                    }
                }

                // Distribusikan pasal dari $bab_pasal_data (yang dibuat di controller preview)
                if (isset($bab_pasal_data) && is_array($bab_pasal_data)) {
                    foreach ($bab_pasal_data as $bab_number => $bab) {
                        if (!isset($grouped_data[$bab_number])) {
                            continue;
                        }

                        // Proses pasal langsung di bab (tanpa bagian)
                        if (isset($bab['pasal']) && is_array($bab['pasal'])) {
                            foreach ($bab['pasal'] as $pasal_number => $pasal) {
                                $grouped_data[$bab_number]['pasal_tanpa_bagian'][$pasal_number] = [
                                    'isi' => $pasal['isi']
                                ];
                            }
                        }

                        // Proses bagian dan pasal di dalam bagian
                        if (isset($bab['bagian']) && is_array($bab['bagian'])) {
                            foreach ($bab['bagian'] as $bagian_number => $bagian) {
                                // Pastikan bagian sudah diinisialisasi dari judul_bagian
                                if (!isset($grouped_data[$bab_number]['bagian'][$bagian_number])) {
                                    // Jika belum ada (edge case), inisialisasi dulu
                                    $grouped_data[$bab_number]['bagian'][$bagian_number] = [
                                        'judul' => isset($bagian['judul']) ? $bagian['judul'] : '',
                                        'pasal' => []
                                    ];
                                }

                                // Proses pasal dalam bagian
                                if (isset($bagian['pasal']) && is_array($bagian['pasal'])) {
                                    foreach ($bagian['pasal'] as $pasal_number => $pasal) {
                                        $grouped_data[$bab_number]['bagian'][$bagian_number]['pasal'][$pasal_number] = [
                                            'isi' => $pasal['isi']
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // ========== MODE GENERATE (dari database) ==========
            else if ($is_generate) {
                $grouped_data = [];

                foreach ($bab_pasal_data as $bab_number => $bab) {
                    $grouped_data[$bab_number] = [
                        'judul' => isset($bab['judul']) ? $bab['judul'] : '',
                        'bagian' => isset($bab['bagian']) && is_array($bab['bagian']) ? $bab['bagian'] : [],
                        'pasal_tanpa_bagian' => isset($bab['pasal']) && is_array($bab['pasal']) ? $bab['pasal'] : []
                    ];
                }
            } else {
                $grouped_data = [];
            }
            ?>

            <!-- Loop untuk menampilkan Bab, Bagian, dan Pasal -->
            <?php if (!empty($grouped_data) && is_array($grouped_data)): ?>
                <?php foreach ($grouped_data as $bab_number => $bab): ?>
                    <!-- Judul Bab -->
                    <div class="centered-title-container">
                        <div class="centered-title-content">
                            <p style="margin:0;">BAB <?php echo romawi($bab_number); ?></p>
                            <p style="margin:0;text-transform: uppercase;"><?php echo htmlspecialchars($bab['judul']); ?></p>
                        </div>
                    </div>

                    <!-- Bagian dalam Bab -->
                    <?php if (!empty($bab['bagian']) && is_array($bab['bagian'])): ?>
                        <?php foreach ($bab['bagian'] as $bagian_number => $bagian): ?>
                            <!-- Judul Bagian -->
                            <div class="centered-title-container">
                                <div class="centered-title-content">
                                    <p style="margin:0;">Bagian <?php echo terbilang_bagian($bagian_number); ?></p>
                                    <p style="margin:0;text-transform: uppercase;"><?php echo htmlspecialchars($bagian['judul']); ?></p>
                                </div>
                            </div>

                            <!-- Pasal dalam Bagian -->
                            <?php if (!empty($bagian['pasal']) && is_array($bagian['pasal'])): ?>
                                <?php foreach ($bagian['pasal'] as $pasal_number => $pasal): ?>
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

                    <!-- Pasal tanpa Bagian -->
                    <?php if (!empty($bab['pasal_tanpa_bagian']) && is_array($bab['pasal_tanpa_bagian'])): ?>
                        <?php foreach ($bab['pasal_tanpa_bagian'] as $pasal_number => $pasal): ?>
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