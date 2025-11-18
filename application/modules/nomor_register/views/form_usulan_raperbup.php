<style>
    .user-image-custom {
        margin-bottom: 10px;
    }

    /* .keputusan-field,
    .pasal-field,
    .bagian-field {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        animation: fadeInSlide 0.4s ease-out;
    }

    .bagian-field {
        background: #e8f0fe;
        border-left: 4px solid #007bff;
        margin-left: 40px;
    }

    .pasal-field {
        background: #fff8e1;
        border-left: 4px solid #ffc107;
        margin-left: 20px;
    } */

    /* BAB Container */
    .bab-field {
        border: 2px solid #2c3e50;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        position: relative;
        animation: fadeInSlide 0.5s ease-out;
    }

    .bab-field::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 8px;
        height: 100%;
        /* background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); */
        border-radius: 12px 0 0 12px;
    }

    /* BAGIAN Container */
    .bagian-field {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 2px solid #2196F3;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0 20px 30px;
        box-shadow: 0 3px 10px rgba(33, 150, 243, 0.15);
        position: relative;
        animation: fadeInSlide 0.4s ease-out;
    }

    .bagian-field::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, #2196F3 0%, #1976D2 100%);
        border-radius: 10px 0 0 10px;
    }

    /* PASAL Container */
    .pasal-field {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        border: 2px solid #FFA726;
        border-radius: 8px;
        padding: 18px;
        margin: 15px 0 15px 20px;
        box-shadow: 0 2px 8px rgba(255, 167, 38, 0.15);
        position: relative;
        transition: all 0.3s ease;
    }

    .pasal-field:hover {
        box-shadow: 0 4px 12px rgba(255, 167, 38, 0.25);
        transform: translateX(5px);
    }

    .pasal-field::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #FFA726 0%, #FB8C00 100%);
        border-radius: 8px 0 0 8px;
    }

    /* Header Styling */
    .field-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.08);
    }

    /* Nomor Field dengan Icon */
    .field-number {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.3em;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.5px;
    }

    .field-number::before {
        content: '📖';
        font-size: 1.2em;
    }

    .bagian-number {
        font-weight: 700;
        color: #1565C0;
        font-size: 1.1em;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.3px;
    }

    .bagian-number::before {
        content: '📑';
        font-size: 1em;
    }

    .pasal-number {
        font-weight: 700;
        color: #E65100;
        font-size: 1em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pasal-number::before {
        content: '📄';
        font-size: 0.9em;
    }

    /* Button Styling */
    .btn-add-field {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        color: white;
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-add-field:hover {
        background: linear-gradient(135deg, #45a049 0%, #388e3c 100%);
        box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        transform: translateY(-2px);
    }

    .btn-add-bagian {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(33, 150, 243, 0.3);
        transition: all 0.3s ease;
    }

    .btn-add-bagian:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        box-shadow: 0 5px 15px rgba(33, 150, 243, 0.4);
        transform: translateY(-2px);
    }

    .btn-add-pasal {
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        border: none;
        color: white;
        padding: 8px 18px;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        transition: all 0.3s ease;
    }

    .btn-add-pasal:hover {
        background: linear-gradient(135deg, #F57C00 0%, #E65100 100%);
        box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
        transform: translateY(-2px);
    }

    .btn-remove-field {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        border: none;
        color: white;
        padding: 6px 14px;
        font-weight: 600;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
        transition: all 0.3s ease;
        font-size: 0.9em;
    }

    .btn-remove-field:hover {
        background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        transform: translateY(-1px);
    }

    /* Container untuk Tombol Tambah */
    .add-field-container {
        text-align: center;
        margin: 25px 0;
        padding: 25px;
        border: 3px dashed #4CAF50;
        border-radius: 12px;
        background: linear-gradient(135deg, #f1f8e9 0%, #dcedc8 100%);
        transition: all 0.3s ease;
    }

    .add-field-container:hover {
        background: linear-gradient(135deg, #dcedc8 0%, #c5e1a5 100%);
        border-color: #388e3c;
    }

    .add-bagian-container {
        text-align: center;
        margin: 15px 0;
        padding: 20px;
        border: 3px dashed #2196F3;
        border-radius: 10px;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        transition: all 0.3s ease;
    }

    .add-bagian-container:hover {
        background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%);
        border-color: #1565C0;
    }

    .add-pasal-container {
        text-align: center;
        margin: 12px 0;
        padding: 18px;
        border: 3px dashed #FF9800;
        border-radius: 8px;
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        transition: all 0.3s ease;
    }

    .add-pasal-container:hover {
        background: linear-gradient(135deg, #ffecb3 0%, #ffe082 100%);
        border-color: #E65100;
    }

    /* Pasal Container dengan Visual Guide */
    .pasal-container {
        margin-left: 25px;
        padding-left: 20px;
        border-left: 3px solid #FFA726;
        position: relative;
    }

    .pasal-container::before {
        content: '';
        position: absolute;
        left: -3px;
        top: 0;
        width: 3px;
        height: 30px;
        background: linear-gradient(180deg, #FFA726 0%, transparent 100%);
    }

    /* Bagian Container */
    .bagian-container {
        position: relative;
    }

    /* Text Styling untuk Instructions */
    .add-field-container small,
    .add-bagian-container small,
    .add-pasal-container small {
        font-size: 0.85em;
        font-weight: 500;
        color: #546e7a;
        display: block;
        margin-top: 8px;
        font-style: italic;
    }

    /* Form Input Styling dalam Field */
    .bab-field .form-control,
    .bagian-field .form-control,
    .pasal-field .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.95em;
    }

    .bab-field .form-control:focus {
        border-color: #2c3e50;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.15);
    }

    .bagian-field .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.15);
    }

    .pasal-field .form-control:focus {
        border-color: #FF9800;
        box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.15);
    }

    /* Label Styling */
    .bab-field label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .bagian-field label {
        font-weight: 600;
        color: #1565C0;
        margin-bottom: 8px;
    }

    .pasal-field label {
        font-weight: 600;
        color: #E65100;
        margin-bottom: 8px;
    }

    /* Highlight untuk field yang baru ditambahkan */
    .newly-added {
        animation: highlightPulse 2s ease-in-out;
    }

    @keyframes highlightPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
        }

        50% {
            box-shadow: 0 0 20px 10px rgba(76, 175, 80, 0.4);
        }
    }

    /* Info Badge untuk panduan */
    .info-badge {
        display: inline-block;
        background: #2196F3;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75em;
        font-weight: 600;
        margin-left: 10px;
    }

    /* Animation */
    @keyframes fadeInSlide {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .bagian-field {
            margin-left: 15px;
            padding: 15px;
        }

        .pasal-field {
            margin-left: 10px;
            padding: 12px;
        }

        .field-number {
            font-size: 1.1em;
        }

        .bagian-number,
        .pasal-number {
            font-size: 0.95em;
        }

        .btn-add-field,
        .btn-add-bagian,
        .btn-add-pasal {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    /* Hierarchy Visual Indicator */
    .bab-field>.form-main-content {
        position: relative;
        padding-left: 5px;
    }

    /* Keputusan Field (untuk konsistensi) */
    .keputusan-field {
        border: 2px solid #9C27B0;
        border-radius: 8px;
        padding: 18px;
        margin-bottom: 20px;
        /* background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); */
        box-shadow: 0 3px 10px rgba(156, 39, 176, 0.15);
        animation: fadeInSlide 0.4s ease-out;
        position: relative;
    }

    .keputusan-field::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        /* background: linear-gradient(180deg, #9C27B0 0%, #7B1FA2 100%); */
        border-radius: 8px 0 0 8px;
    }

    /* Icon untuk tombol */
    .btn-add-field i,
    .btn-add-bagian i,
    .btn-add-pasal i,
    .btn-remove-field i {
        margin-right: 5px;
    }

    /* Breadcrumb Visual untuk Struktur */
    .bagian-field::after {
        content: '↳';
        position: absolute;
        left: -25px;
        top: 25px;
        font-size: 1.5em;
        color: #2196F3;
        font-weight: bold;
    }

    .pasal-field::after {
        content: '→';
        position: absolute;
        left: -18px;
        top: 20px;
        font-size: 1.2em;
        color: #FF9800;
        font-weight: bold;
    }

    /* custom list dengan style kurung */
    ol.list-decimal-parenthesis {
        list-style-type: none;
        counter-reset: item;
        padding-left: 0;
    }

    ol.list-decimal-parenthesis>li {
        counter-increment: item;
        margin-bottom: 5px;
        padding-left: 2em;
        position: relative;
    }

    ol.list-decimal-parenthesis>li:before {
        content: "(" counter(item) ") ";
        position: absolute;
        left: 0;
    }

    /* Nested list level 2 - gunakan lowercase alpha */
    ol.list-decimal-parenthesis ol {
        list-style-type: none;
        counter-reset: subitem;
    }

    ol.list-decimal-parenthesis ol>li {
        counter-increment: subitem;
    }

    ol.list-decimal-parenthesis ol>li:before {
        content: "(" counter(subitem, lower-alpha) ") ";
    }

    /* Nested list level 3 - gunakan roman */
    ol.list-decimal-parenthesis ol ol {
        counter-reset: subsubitem;
    }

    ol.list-decimal-parenthesis ol ol>li {
        counter-increment: subsubitem;
    }

    ol.list-decimal-parenthesis ol ol>li:before {
        content: "(" counter(subsubitem, lower-roman) ") ";
    }

    @keyframes fadeInSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .field-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .field-number {
        font-weight: bold;
        color: #495057;
        font-size: 1.1em;
    }

    .bagian-number {
        font-weight: bold;
        color: #007bff;
        font-size: 1em;
    }

    .pasal-number {
        font-weight: bold;
        color: #f57c00;
        font-size: 1em;
    }

    .btn-add-field {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        color: white;
    }

    .btn-add-bagian {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        color: white;
    }

    .btn-add-pasal {
        background: linear-gradient(45deg, #ffc107, #ffb300);
        border: none;
        color: #000;
    }

    .btn-remove-field {
        background: linear-gradient(45deg, #dc3545, #e74c3c);
        border: none;
        color: white;
    }

    .add-field-container {
        text-align: center;
        margin: 15px 0;
        padding: 15px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .add-bagian-container {
        text-align: center;
        margin: 10px 0;
        padding: 10px;
        border: 2px dashed #007bff;
        border-radius: 8px;
        background: #e8f0fe;
    }

    .add-pasal-container {
        text-align: center;
        margin: 10px 0;
        padding: 10px;
        border: 2px dashed #ffc107;
        border-radius: 8px;
        background: #fff8e1;
    }

    .pasal-container {
        margin-left: 20px;
        border-left: 2px solid #ffc107;
        padding-left: 15px;
    }

    .form-readonly-level6,
    .form-readonly-level6:focus {
        background-color: #f5f5f5 !important;
        cursor: not-allowed !important;
        opacity: 0.75;
        border-color: #ddd !important;
    }

    .select-readonly-level6 {
        background-color: #f5f5f5 !important;
        cursor: not-allowed !important;
        opacity: 0.75;
        pointer-events: none !important;
    }

    .btn-disabled-level6 {
        opacity: 0.5;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    .alert-level6-notice {
        border-left: 4px solid #ff9800;
        background-color: #fff3e0;
        color: #fff;
    }

    .alert-level6-notice i {
        font-size: 1.2em;
        margin-right: 8px;
    }

    /* CKEditor readonly styling */
    .cke_readonly {
        background-color: #f5f5f5 !important;
        opacity: 0.75;
    }

    .cke_readonly .cke_contents {
        background-color: #f5f5f5 !important;
    }

    /* Hide file input group untuk level 6 */
    body.user-level-6 .form-group.row:has(input[type="file"]) {
        display: none !important;
    }
</style>
<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">
                        <?php if (!empty($user_updated) && !empty($last_updated_at)): ?>
                            <small class="text-muted">
                                Terakhir diupdate oleh: <strong><?php echo $user_updated; ?></strong>
                                pada <strong><?php echo strftime('%d %B %Y %H:%M', strtotime($last_updated_at)); ?></strong>
                            </small>
                        <?php endif; ?>
                    </div>
                    <?php echo form_open_multipart('', ['id' => 'form-usulan']); ?>
                    <?php
                    $is_edit_mode = !empty($content) && !empty($content->id_usulan_raperbup);
                    ?>
                    <?php
                    if (!empty($this->session->flashdata('message'))) {
                        echo "<div class='alert " . ($this->session->flashdata('type-alert') == 'success' ? 'alert-success' : 'alert-danger') . " alert-dismissible show fade'>
                                <div class='alert-body'>
                                <button class='close' data-dismiss='alert'>
                                    <span>&times;</span>
                                </button>
                                " . $this->session->flashdata('message') . "
                                </div>
                            </div>";
                    }
                    ?>
                    <div class="form-group row">
                        <input type="hidden" name="kategori_usulan_hidden" value="<?php echo !empty($content) ? $content->kategori_usulan_id : ""; ?>" />
                        <label class="col-form-label col-lg-2">Kategori Usulan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <select class="form-control" name="kategori_usulan" onchange="check_lampiran()">
                                <?php
                                foreach ($kategori_usulan as $key => $value) {
                                    $selected = !empty($content) && $value->id_kategori_usulan == $content->kategori_usulan_id ? "selected" : "";
                                ?>
                                    <option <?php echo $selected; ?> value="<?php echo encrypt_data($value->id_kategori_usulan); ?>" data-id="<?php echo $value->id_kategori_usulan; ?>"><?php echo $value->nama_kategori; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Tentang <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input required type="text" class="form-control" name="nama_peraturan" value="<?php echo !empty($content) ? htmlspecialchars($content->nama_peraturan) : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Menimbang <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div class="form-with-revisi">
                                <div class="form-main-content">
                                    <textarea name="menimbang" id="menimbang"><?php echo !empty($content) ? htmlspecialchars($content->menimbang) : "<ol type='a'><li>.....</li><li>.....</li></ol>"; ?></textarea>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <div class="revisi-container" id="revisi-menimbang"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Mengingat <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div class="form-with-revisi">
                                <div class="form-main-content">
                                    <textarea name="mengingat" id="mengingat"><?php echo !empty($content) ? htmlspecialchars($content->mengingat) : "<ol><li>.....</li><li>.....</li></ol>"; ?></textarea>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <div class="revisi-container" id="revisi-mengingat"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Menetapkan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div class="form-with-revisi">
                                <div class="form-main-content">
                                    <textarea name="menetapkan" id="menetapkan"><?php echo !empty($content) ? htmlspecialchars($content->menetapkan) : "<ol><li>.....</li><li>.....</li></ol>"; ?></textarea>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <div class="revisi-container" id="revisi-menetapkan"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Peraturan Bupati -->
                    <div class="card card-primary" id="peraturan-bupati-section">
                        <div class="card-body">
                            <div id="bab-container">
                                <?php
                                // Parse bab_pasal_data dari JSON string
                                if (!empty($content->bab_pasal_data)) {
                                    $bab_data = json_decode($content->bab_pasal_data, true);
                                }

                                // Jika data kosong atau error parsing, gunakan default tanpa pasal
                                if (empty($bab_data)) {
                                    $bab_data = [
                                        "1" => [
                                            "judul" => "",
                                            "pasal" => [], // Kosongkan default pasal
                                            "bagian" => [] // Kosongkan default bagian
                                        ]
                                    ];
                                }

                                // Counter untuk tracking nomor pasal global
                                $global_pasal_counter = 0;

                                // Hitung total pasal untuk set global counter
                                foreach ($bab_data as $bab) {
                                    if (!empty($bab['pasal'])) {
                                        foreach ($bab['pasal'] as $pasal_num => $pasal) {
                                            if ($pasal_num > $global_pasal_counter) {
                                                $global_pasal_counter = $pasal_num;
                                            }
                                        }
                                    }
                                    if (!empty($bab['bagian'])) {
                                        foreach ($bab['bagian'] as $bagian) {
                                            if (!empty($bagian['pasal'])) {
                                                foreach ($bagian['pasal'] as $pasal_num => $pasal) {
                                                    if ($pasal_num > $global_pasal_counter) {
                                                        $global_pasal_counter = $pasal_num;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                // Loop setiap BAB
                                foreach ($bab_data as $bab_number => $bab) {
                                ?>
                                    <div class="bab-field" data-number="<?php echo $bab_number; ?>">
                                        <div class="field-header">
                                            <span class="field-number">BAB <?php echo $bab_number; ?></span>
                                            <button type="button" class="btn btn-sm btn-remove-field remove-bab"
                                                data-number="<?php echo $bab_number; ?>"
                                                <?php echo $bab_number == 1 ? 'style="display: none;"' : ''; ?>>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>

                                        <div class="form-with-revisi">
                                            <div class="form-main-content">
                                                <!-- Input Judul Bab -->
                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-2">Judul Bab <span class="text-danger">*</span></label>
                                                    <div class="col-lg-10">
                                                        <input type="text" class="form-control"
                                                            name="judul_bab[<?php echo $bab_number; ?>]"
                                                            placeholder="Masukkan Judul Bab <?php echo $bab_number; ?>"
                                                            value="<?php echo isset($bab['judul']) ? htmlspecialchars($bab['judul']) : ''; ?>">
                                                    </div>
                                                </div>

                                                <div class="bagian-container" data-bab="<?php echo $bab_number; ?>">
                                                    <?php
                                                    // Tampilkan BAGIAN jika ada
                                                    $bagian_counter = 0;
                                                    $bagian_names = [
                                                        '',
                                                        'Kesatu',
                                                        'Kedua',
                                                        'Ketiga',
                                                        'Keempat',
                                                        'Kelima',
                                                        'Keenam',
                                                        'Ketujuh',
                                                        'Kedelapan',
                                                        'Kesembilan',
                                                        'Kesepuluh'
                                                    ];

                                                    if (!empty($bab['bagian']) && is_array($bab['bagian'])) {
                                                        foreach ($bab['bagian'] as $bagian_number => $bagian) {
                                                            $bagian_counter++;
                                                            $bagian_name = $bagian_names[$bagian_number] ?? "Ke-$bagian_number";
                                                    ?>
                                                            <div class="bagian-field" data-bab="<?php echo $bab_number; ?>"
                                                                data-bagian="<?php echo $bagian_number; ?>">
                                                                <div class="field-header">
                                                                    <span class="bagian-number">Bagian <?php echo $bagian_name; ?></span>
                                                                    <button type="button" class="btn btn-sm btn-remove-field remove-bagian"
                                                                        data-bagian="<?php echo $bagian_number; ?>">
                                                                        <i class="fas fa-trash"></i> Hapus
                                                                    </button>
                                                                </div>

                                                                <!-- Input Judul Bagian -->
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"
                                                                        name="judul_bagian[<?php echo $bab_number; ?>][<?php echo $bagian_number; ?>]"
                                                                        placeholder="Masukkan Judul Bagian <?php echo $bagian_name; ?>"
                                                                        value="<?php echo isset($bagian['judul']) ? htmlspecialchars($bagian['judul']) : ''; ?>"
                                                                        required>
                                                                </div>

                                                                <!-- Pasal dalam Bagian -->
                                                                <div class="pasal-container" data-bab="<?php echo $bab_number; ?>"
                                                                    data-bagian="<?php echo $bagian_number; ?>">
                                                                    <?php
                                                                    $pasal_count_in_bagian = 0;
                                                                    if (!empty($bagian['pasal']) && is_array($bagian['pasal'])) {
                                                                        $pasal_count_in_bagian = count($bagian['pasal']);
                                                                        foreach ($bagian['pasal'] as $pasal_number => $pasal) {
                                                                            $textarea_id = "isi_pasal_$pasal_number";
                                                                    ?>
                                                                            <div class="pasal-field" data-bab="<?php echo $bab_number; ?>"
                                                                                data-pasal="<?php echo $pasal_number; ?>"
                                                                                data-bagian="<?php echo $bagian_number; ?>">
                                                                                <div class="field-header">
                                                                                    <span class="pasal-number">Pasal <?php echo $pasal_number; ?></span>
                                                                                    <button type="button" class="btn btn-sm btn-remove-field remove-pasal"
                                                                                        data-pasal="<?php echo $pasal_number; ?>"
                                                                                        <?php echo $pasal_count_in_bagian == 1 ? 'style="display: none;"' : ''; ?>>
                                                                                        <i class="fas fa-trash"></i> Hapus
                                                                                    </button>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <textarea name="isi_pasal[<?php echo $pasal_number; ?>]"
                                                                                        id="<?php echo $textarea_id; ?>"
                                                                                        class="form-control" rows="3"
                                                                                        placeholder="Masukkan isi Pasal <?php echo $pasal_number; ?>..."
                                                                                        required><?php echo isset($pasal['isi']) ? $pasal['isi'] : ''; ?></textarea>
                                                                                    <input type="hidden" name="pasal_bab_mapping[<?php echo $pasal_number; ?>]"
                                                                                        value="<?php echo $bab_number; ?>">
                                                                                    <input type="hidden" name="pasal_bagian_mapping[<?php echo $pasal_number; ?>]"
                                                                                        value="<?php echo $bagian_number; ?>">
                                                                                </div>
                                                                            </div>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <!-- Tombol Tambah Pasal dalam Bagian -->
                                                                    <div class="add-pasal-container">
                                                                        <button type="button" class="btn btn-sm btn-add-pasal add-pasal-btn"
                                                                            data-bab="<?php echo $bab_number; ?>"
                                                                            data-bagian="<?php echo $bagian_number; ?>">
                                                                            <i class="fas fa-plus"></i> Tambah Pasal
                                                                        </button>
                                                                        <br>
                                                                        <small class="text-muted mt-2 d-block">Klik untuk menambah pasal baru dalam bagian ini</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php
                                                        }
                                                    }

                                                    ?>

                                                    <!-- Tombol Tambah Bagian -->
                                                    <div class="add-bagian-container">
                                                        <button type="button" class="btn btn-sm btn-add-bagian add-bagian-btn"
                                                            data-bab="<?php echo $bab_number; ?>">
                                                            <i class="fas fa-plus"></i> Tambah Bagian
                                                        </button>
                                                        <br>
                                                        <small class="text-muted mt-2 d-block">Klik untuk menambah bagian baru dalam bab ini</small>
                                                    </div>

                                                    <!-- Pasal tanpa Bagian (langsung di Bab) -->
                                                    <div class="pasal-container" data-bab="<?php echo $bab_number; ?>" data-bagian="0">
                                                        <?php
                                                        $pasal_count_without_bagian = 0;
                                                        if (!empty($bab['pasal']) && is_array($bab['pasal'])) {
                                                            $pasal_count_without_bagian = count($bab['pasal']);
                                                            foreach ($bab['pasal'] as $pasal_number => $pasal) {
                                                                $textarea_id = "isi_pasal_$pasal_number";
                                                        ?>
                                                                <div class="pasal-field" data-bab="<?php echo $bab_number; ?>"
                                                                    data-pasal="<?php echo $pasal_number; ?>" data-bagian="0">
                                                                    <div class="field-header">
                                                                        <span class="pasal-number">Pasal <?php echo $pasal_number; ?></span>
                                                                        <button type="button" class="btn btn-sm btn-remove-field remove-pasal"
                                                                            data-pasal="<?php echo $pasal_number; ?>"
                                                                            <?php echo $pasal_count_without_bagian == 1 ? 'style="display: none;"' : ''; ?>>
                                                                            <i class="fas fa-trash"></i> Hapus
                                                                        </button>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <textarea name="isi_pasal[<?php echo $pasal_number; ?>]"
                                                                            id="<?php echo $textarea_id; ?>"
                                                                            class="form-control" rows="3"
                                                                            placeholder="Masukkan isi Pasal <?php echo $pasal_number; ?>..."
                                                                            required><?php echo isset($pasal['isi']) ? $pasal['isi'] : ''; ?></textarea>
                                                                        <input type="hidden" name="pasal_bab_mapping[<?php echo $pasal_number; ?>]"
                                                                            value="<?php echo $bab_number; ?>">
                                                                        <input type="hidden" name="pasal_bagian_mapping[<?php echo $pasal_number; ?>]"
                                                                            value="0">
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <!-- Tombol Tambah Pasal tanpa Bagian -->
                                                        <div class="add-pasal-container">
                                                            <button type="button" class="btn btn-sm btn-add-pasal add-pasal-btn"
                                                                data-bab="<?php echo $bab_number; ?>" data-bagian="0">
                                                                <i class="fas fa-plus"></i> Tambah Pasal
                                                            </button>
                                                            <br>
                                                            <small class="text-muted mt-2 d-block">Klik untuk menambah pasal baru tanpa bagian</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($is_edit_mode): ?>
                                                <div class="revisi-container" id="revisi-bab-<?php echo $bab_number; ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                        <hr>
                                    </div>
                                <?php
                                }
                                ?>

                                <!-- Tombol Tambah Bab -->
                                <div class="add-field-container">
                                    <button type="button" id="add-bab" class="btn btn-sm btn-add-field">
                                        <i class="fas fa-plus"></i> Tambah Bab
                                    </button>
                                    <br>
                                    <small class="text-muted mt-2 d-block">Klik untuk menambah bab baru</small>
                                </div>
                            </div>
                            <div class="form-group row" id="penjelasan-section">
                                <label class="col-form-label col-lg-2">Penjelasan <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="form-with-revisi">
                                        <div class="form-main-content">
                                            <textarea name="penjelasan" id="penjelasan"><?php echo !empty($content) ? htmlspecialchars($content->penjelasan) : ''; ?></textarea>
                                        </div>
                                        <?php if ($is_edit_mode): ?>
                                            <div class="revisi-container" id="revisi-penjelasan"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end Peraturan Bupati -->
                    <!-- Keputusan Bupati -->
                    <div class="form-group row" id="memutuskan-section">
                        <label class="col-form-label col-lg-2">Memutuskan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div id="keputusan-container">
                                <?php
                                $keputusan_data = !empty($content->memutuskan) ? json_decode($content->memutuskan, true) : [1 => ''];
                                $keputusan_names = ['', 'KESATU', 'KEDUA', 'KETIGA', 'KEEMPAT', 'KELIMA', 'KEENAM', 'KETUJUH', 'KEDELAPAN', 'KESEMBILAN', 'KESEPULUH', 'KESEBELAS', 'KEDUA BELAS', 'KETIGA BELAS', 'KEEMPAT BELAS', 'KELIMA BELAS', 'KEENAM BELAS', 'KETUJUH BELAS', 'KEDELAPAN BELAS', 'KESEMBILAN BELAS', 'KEDUA PULUH'];
                                $keputusan_counter = 0;

                                foreach ($keputusan_data as $keputusan_number => $keputusan) {
                                    $keputusan_counter++;
                                    $keputusan_name = $keputusan_names[$keputusan_number] ?? "KE-$keputusan_number";
                                    $textarea_id = $keputusan_number == 1 ? 'keputusan_1' : "keputusan_$keputusan_number";
                                ?>
                                    <div class="keputusan-field" data-number="<?php echo $keputusan_number; ?>">
                                        <div class="field-header">
                                            <span class="field-number"><?php echo $keputusan_name; ?></span>
                                            <button type="button" class="btn btn-sm btn-remove-field remove-keputusan" data-number="<?php echo $keputusan_number; ?>" <?php echo $keputusan_number == 1 ? 'style="display: none;"' : ''; ?>>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <div class="form-with-revisi">
                                            <div class="form-main-content">
                                                <textarea id="<?php echo $textarea_id; ?>" name="keputusan[<?php echo $keputusan_number; ?>]" class="form-control" rows="3" placeholder="Masukkan isi keputusan <?php echo strtolower($keputusan_name); ?>..." required><?php echo !empty($keputusan) ? htmlspecialchars($keputusan) : ''; ?></textarea>
                                            </div>
                                            <?php if ($is_edit_mode): ?>
                                                <div class="revisi-container" id="revisi-memutuskan-<?php echo $keputusan_number; ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="add-field-container">
                                <button type="button" id="add-keputusan" class="btn btn-sm btn-add-field">
                                    <i class="fas fa-plus"></i> Tambah Keputusan
                                </button>
                                <br>
                                <small class="text-muted mt-2 d-block">Klik untuk menambah keputusan kedua, ketiga, dan seterusnya</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" id="tembusan-section">
                        <label class="col-form-label col-lg-2">Tembusan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div class="form-main-content">
                                <textarea name="tembusan" id="tembusan"><?php echo !empty($content) ? htmlspecialchars($content->tembusan) : "Tembusan :<ol><li>.....</li><li>.....</li></ol>"; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- end Keputusan Bupati -->
                    <div class="form-group row lampiran-usulan-section">
                        <label class="col-form-label col-lg-2">Lampiran Usulan <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran_usulan" accept="application/pdf">
                            <small class="form-text text-muted">Max. Upload Size: 2 MB</small>
                            <small class="form-text text-muted">Type File: pdf</small>
                        </div>
                    </div>
                    <div class="form-group row is-show-lampiran-kepala-dinas">
                        <label class="col-form-label col-lg-2">Lampiran Surat Pengantar Kepala Dinas <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">Max. Upload Size: 2 MB</small>
                            <small class="form-text text-muted">Type File: doc, docx, & pdf</small>
                        </div>
                    </div>
                    <div class="form-group row is-show-lampiran-sk-tim">
                        <label class="col-form-label col-lg-2">Lampiran SK Tim <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran_sk_tim;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran_sk_tim" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">Max. Upload Size: 2 MB</small>
                            <small class="form-text text-muted">Type File: doc, docx, & pdf</small>
                        </div>
                    </div>
                    <div class="form-group row is-show-lampiran-daftar-hadir">
                        <label class="col-form-label col-lg-2">Lampiran Daftar Hadir <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran_daftar_hadir;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran_daftar_hadir" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">Max. Upload Size: 2 MB</small>
                            <small class="form-text text-muted">Type File: doc, docx, & pdf</small>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" id="preview-btn" class="btn btn-danger mr-2">Preview PDF <i class="fas fa-eye ml-2"></i></button>
                        <button type="submit" class="btn btn-primary">Simpan <i class="fas fa-paper-plane ml-2"></i></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="showFormDetail" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body isi-content"></div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
<script>
    // Konfigurasi CKEditor
    const ckeditorConfig = {
        toolbar: [{
            name: 'document',
            items: ['Undo', 'Redo']
        }, {
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Underline']
        }, {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList', '-', 'Styles', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Outdent', 'Indent']
        }, {
            name: 'maximize',
            items: ['Maximize']
        }],

        // Tambahan untuk custom list
        stylesSet: [{
            name: 'List (1) (2) (3)',
            element: 'ol',
            attributes: {
                'class': 'list-decimal-parenthesis'
            }
        }],

        extraAllowedContent: 'ol(list-decimal-parenthesis);li(*)',

        contentsCss: [
            CKEDITOR.getUrl('contents.css'),
            'data:text/css,' + encodeURIComponent(`
            ol.list-decimal-parenthesis {
                list-style-type: none;
                counter-reset: item;
                padding-left: 0;
            }
            ol.list-decimal-parenthesis > li {
                counter-increment: item;
                margin-bottom: 5px;
                padding-left: 2em;
                position: relative;
            }
            ol.list-decimal-parenthesis > li:before {
                content: "(" counter(item) ") ";
                position: absolute;
                left: 0;
            }
        `)
        ]
    };

    // Fungsi untuk inisialisasi CKEditor pada textarea tertentu
    function initCKEditor(textareaId) {
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy(true);
        }
        CKEDITOR.replace(textareaId, ckeditorConfig);
    }

    // Counter global untuk pasal, bagian, dan keputusan
    let globalPasalCounter = <?php echo $global_pasal_counter; ?>;
    let bagianCounter = <?php echo $bagian_counter ?? 0; ?>;
    let keputusanCounter = <?php echo $keputusan_counter ?? 0; ?>;
    let babCounter = <?php echo count($bab_data); ?>;

    // Data revisi dari controller
    const dataRevisi = <?php echo !empty($data_revisi) ? json_encode($data_revisi) : '[]'; ?>;

    // Fungsi untuk memformat tanggal ke format Indonesia
    function formatTanggalIndonesia(tanggal) {
        const options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        const date = new Date(tanggal);
        return date.toLocaleDateString('id-ID', options).replace('pukul', 'pkl.');
    }

    var isEditMode = <?php echo $is_edit_mode ? 'true' : 'false'; ?>;
    var currentUserId = <?php echo $this->session->userdata('id_user') ? $this->session->userdata('id_user') : 0; ?>;
    var currentUserLevel = <?php echo isset($level_user) ? $level_user : 0; ?>;
    var canManageRevisi = [4, 6, 7].includes(Number(currentUserLevel));
    console.log("Current User ID:", currentUserId, "Level:", currentUserLevel, "Can Manage Revisi:", canManageRevisi);

    // Fungsi untuk menampilkan revisi
    function tampilkanRevisi() {

        // Definisikan SEMUA kolom yang memiliki revisi (wajib ada)
        const kolomDenganRevisi = [
            'menimbang',
            'mengingat',
            'menetapkan',
            'penjelasan'
        ];

        // Tambahkan revisi untuk setiap BAB
        <?php foreach ($bab_data as $bab_number => $bab) { ?>
            kolomDenganRevisi.push('bab-<?php echo $bab_number; ?>');
        <?php } ?>

        // Grouping revisi berdasarkan kolom_tujuan
        const revisiMap = {};

        // Inisialisasi SEMUA kolom dengan array kosong dulu
        kolomDenganRevisi.forEach(kolom => {
            revisiMap[kolom] = [];
        });

        // Tambahkan data revisi yang ada dari database
        dataRevisi.forEach(revisi => {
            let key = revisi.kolom_tujuan;

            // Untuk memutuskan, tambahkan nomor keputusan jika ada
            if (revisi.kolom_tujuan === 'memutuskan' && revisi.nomor_keputusan) {
                key = `memutuskan-${revisi.nomor_keputusan}`;
            }

            if (!revisiMap[key]) {
                revisiMap[key] = [];
            }
            revisiMap[key].push(revisi);
        });

        // Render revisi untuk SEMUA kolom (baik ada data atau tidak)
        Object.keys(revisiMap).forEach(key => {
            const revisiList = revisiMap[key];
            const containerId = `revisi-${key}`;
            const container = $(`#${containerId}`);

            // console.log(`Processing ${containerId}:`, container.length, 'Revisi count:', revisiList.length);

            if (container.length === 0) {
                console.warn(`Container ${containerId} not found`);
                return;
            }

            renderRevisiContainer(container, key, revisiList);
        });

        // Render revisi untuk keputusan yang dinamis
        $('.keputusan-field').each(function() {
            const keputusanNumber = $(this).data('number');
            const key = `memutuskan-${keputusanNumber}`;
            const containerId = `revisi-${key}`;
            const container = $(`#${containerId}`);

            if (container.length === 0) {
                console.warn(`Container ${containerId} not found`);
                return;
            }

            const revisiList = revisiMap[key] || [];
            renderRevisiContainer(container, key, revisiList);
        });

        // Render revisi untuk BAB dinamis
        $('.bab-field').each(function() {
            const babNumber = $(this).data('number');
            const key = `bab-${babNumber}`;
            const containerId = `revisi-${key}`;
            const container = $(`#${containerId}`);

            if (container.length === 0) return;

            const revisiList = revisiMap[key] || [];
            renderRevisiContainer(container, key, revisiList);
        });
    }

    // Fungsi helper untuk render container revisi
    function renderRevisiContainer(container, key, revisiList) {
        let html = `
            <div class="revisi-header">
                <i class="fas fa-comments"></i> Catatan Revisi (${revisiList.length})
            </div>
        `;

        // Tampilkan list revisi jika ada
        if (revisiList.length > 0) {
            html += `<div class="revisi-accordion" id="accordion-${key}">`;

            revisiList.forEach((revisi, index) => {
                const isOwner = parseInt(revisi.id_user) === parseInt(currentUserId);
                const canEdit = canManageRevisi && isOwner;
                const namaUser = revisi.nama_user || `User ${revisi.id_user}`;
                const tanggalUpdate = formatTanggalIndonesia(revisi.updated_at);
                const collapseId = `collapse-${key}-${index}`;
                const isExpanded = index === 0 ? 'show' : '';

                html += `
                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#${collapseId}">
                        <h6>
                            <span>
                                <i class="fas fa-user-circle"></i> ${namaUser}
                                ${isOwner ? '<span class="badge badge-primary badge-user ml-2">Anda</span>' : ''}
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </h6>
                    </div>
                    <div id="${collapseId}" class="collapse ${isExpanded}" data-parent="#accordion-${key}">
                        <div class="card-body">
                            <div class="revisi-content" id="revisi-content-${revisi.id_revisi}">
                                ${escapeHtml(revisi.catatan_revisi)}
                            </div>
                            <div class="revisi-info">
                                <i class="far fa-clock"></i> ${tanggalUpdate}
                            </div>
                            ${canEdit ? `
                            <div class="revisi-actions">
                                <button type="button" class="btn btn-sm btn-warning btn-edit-revisi" 
                                        data-id="${revisi.id_revisi}" 
                                        data-kolom="${revisi.kolom_tujuan}"
                                        data-catatan="${escapeHtml(revisi.catatan_revisi)}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-delete-revisi" 
                                        data-id="${revisi.id_revisi}" 
                                        data-kolom="${revisi.kolom_tujuan}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            });

            html += `</div>`;
        } else {
            // Tampilkan pesan jika belum ada revisi
            html += `<div class="no-revisi">Belum ada catatan revisi</div>`;
        }

        // PENTING: Form tambah revisi SELALU ditampilkan untuk level 6 (tidak peduli ada data atau tidak)
        if (canManageRevisi) {
            html += `
            <div class="form-add-revisi">
                <textarea class="form-control input-revisi" 
                          data-kolom="${key}" 
                          placeholder="Tulis catatan revisi..."
                          rows="3"></textarea>
                <button type="button" class="btn btn-sm btn-success btn-block btn-simpan-revisi" 
                        data-kolom="${key}">
                    <i class="fas fa-save"></i> Simpan Catatan Revisi
                </button>
            </div>
        `;
        } else {
            // Tampilkan info untuk user yang tidak punya akses
            html += `
            <div class="alert alert-info alert-info-revisi">
                <i class="fas fa-info-circle"></i> Anda hanya dapat melihat catatan revisi.
            </div>
        `;
        }

        container.html(html);
        console.log(`Rendered container: ${key}, Has form: ${canManageRevisi}`);
    }


    // Fungsi untuk escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    $(document).on('click', '.btn-simpan-revisi', function() {
        // Double check level user
        if (!canManageRevisi) {
            alert('Anda tidak memiliki akses untuk menambah catatan revisi!');
            return;
        }

        const kolom = $(this).data('kolom');
        const catatan = $(`.input-revisi[data-kolom="${kolom}"]`).val().trim();

        if (!catatan) {
            alert('Catatan revisi tidak boleh kosong!');
            return;
        }

        // Ambil id_usulan_raperbup dari form atau hidden input
        const idUsulanRaperbup = '<?php echo !empty($content) ? $content->id_usulan_raperbup : ""; ?>';

        if (!idUsulanRaperbup) {
            alert('ID Usulan tidak ditemukan. Silakan simpan usulan terlebih dahulu.');
            return;
        }

        $.ajax({
            url: '<?= base_url("nomor_register/simpan_revisi") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_usulan_raperbup: idUsulanRaperbup,
                kolom_tujuan: kolom,
                catatan_revisi: catatan
            },
            beforeSend: function() {
                $(`.btn-simpan-revisi[data-kolom="${kolom}"]`).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Tambahkan revisi baru ke dataRevisi
                    dataRevisi.push(response.data);

                    // Refresh tampilan revisi
                    tampilkanRevisi();

                    // Kosongkan textarea
                    $(`.input-revisi[data-kolom="${kolom}"]`).val('');

                    // Notifikasi sukses
                    alert('Catatan revisi berhasil disimpan!');
                } else {
                    alert(response.message || 'Gagal menyimpan revisi!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan revisi!');
            },
            complete: function() {
                $(`.btn-simpan-revisi[data-kolom="${kolom}"]`).prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Catatan Revisi');
            }
        });
    });

    // Event: Edit revisi - HANYA untuk level 6 DAN owner
    $(document).on('click', '.btn-edit-revisi', function() {
        // Double check level user
        if (!canManageRevisi) {
            alert('Anda tidak memiliki akses untuk mengedit catatan revisi!');
            return;
        }

        const idRevisi = $(this).data('id');
        const kolom = $(this).data('kolom');
        const catatanLama = $(this).data('catatan');

        const catatanBaru = prompt('Edit catatan revisi:', catatanLama);

        if (catatanBaru === null) return; // User cancel

        if (!catatanBaru.trim()) {
            alert('Catatan revisi tidak boleh kosong!');
            return;
        }

        $.ajax({
            url: '<?= base_url("nomor_register/update_revisi") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_revisi: idRevisi,
                catatan_revisi: catatanBaru
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update dataRevisi
                    const index = dataRevisi.findIndex(r => r.id_revisi == idRevisi);
                    if (index !== -1) {
                        dataRevisi[index].catatan_revisi = catatanBaru;
                        dataRevisi[index].updated_at = response.data.updated_at;
                    }

                    // Refresh tampilan
                    tampilkanRevisi();

                    alert('Catatan revisi berhasil diupdate!');
                } else {
                    alert(response.message || 'Gagal mengupdate revisi!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate revisi!');
            }
        });
    });

    // Event: Hapus revisi - HANYA untuk level 6 DAN owner
    $(document).on('click', '.btn-delete-revisi', function() {
        // Double check level user
        if (!canManageRevisi) {
            alert('Anda tidak memiliki akses untuk menghapus catatan revisi!');
            return;
        }

        if (!confirm('Apakah Anda yakin ingin menghapus catatan revisi ini?')) {
            return;
        }

        const idRevisi = $(this).data('id');
        const kolom = $(this).data('kolom');

        $.ajax({
            url: '<?= base_url("nomor_register/hapus_revisi") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_revisi: idRevisi
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Hapus dari dataRevisi
                    const index = dataRevisi.findIndex(r => r.id_revisi == idRevisi);
                    if (index !== -1) {
                        dataRevisi.splice(index, 1);
                    }

                    // Refresh tampilan
                    tampilkanRevisi();

                    alert('Catatan revisi berhasil dihapus!');
                } else {
                    alert(response.message || 'Gagal menghapus revisi!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus revisi!');
            }
        });
    });

    function setFormAccess() {
        console.log('Setting form access for level:', currentUserLevel);

        if (currentUserLevel == 6) {
            console.log('Setting readonly mode for level 6');

            // Untuk INPUT dan TEXTAREA - gunakan readonly instead of disabled
            $('#form-usulan').find('input:not(.input-revisi), textarea:not(.input-revisi)').each(function() {
                // Jangan readonly yang ada di dalam revisi-container
                if ($(this).closest('.revisi-container').length === 0 &&
                    $(this).closest('.form-add-revisi').length === 0) {

                    // Gunakan readonly untuk input text, textarea
                    if ($(this).is('input[type="text"], input[type="hidden"], textarea')) {
                        $(this).prop('readonly', true);
                        $(this).addClass('form-readonly-level6');

                        // Prevent mouse events untuk feel disabled
                        $(this).on('mousedown', function(e) {
                            e.preventDefault();
                            return false;
                        });
                    }

                    // Untuk input file, hidden agar tidak bisa diubah
                    if ($(this).is('input[type="file"]')) {
                        $(this).prop('disabled', true);
                        $(this).closest('.form-group').hide(); // Hide file upload untuk level 6
                    }
                }
            });

            // Untuk SELECT - gunakan pointer-events dan styling
            $('#form-usulan').find('select').each(function() {
                if ($(this).closest('.revisi-container').length === 0) {
                    $(this).addClass('select-readonly-level6');
                    $(this).css('pointer-events', 'none');

                    // Prevent change
                    $(this).on('change', function(e) {
                        e.preventDefault();
                        return false;
                    });
                }
            });

            // Disable semua tombol KECUALI preview dan revisi
            $('#form-usulan').find('button').each(function() {
                if (!$(this).hasClass('btn-simpan-revisi') &&
                    !$(this).hasClass('btn-edit-revisi') &&
                    !$(this).hasClass('btn-delete-revisi') &&
                    !$(this).is('#preview-btn') &&
                    !$(this).hasClass('close') && // Untuk modal close button
                    !$(this).attr('data-dismiss')) { // Untuk modal dismiss button

                    $(this).prop('disabled', true).addClass('btn-disabled-level6');
                    $(this).css('pointer-events', 'none');
                }
            });

            // Set CKEditor readonly
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].setReadOnly(true);
            }

            // Tambahkan notice di atas form
            const notice = `
            <div class="alert alert-warning alert-level6-notice" style="margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i> 
                <strong>Mode Reviewer:</strong> Anda hanya dapat melihat dan memberikan catatan revisi. 
                Form tidak dapat diedit.
            </div>
        `;

            if ($('.alert-level6-notice').length === 0) {
                $('#form-usulan').prepend(notice);
            }

            // Hide tombol submit
            $('#form-usulan button[type="submit"]').hide();

            // Prevent form submission
            $('#form-usulan').on('submit', function(e) {
                e.preventDefault();
                alert('Anda tidak memiliki akses untuk menyimpan form ini.');
                return false;
            });

        } else {
            console.log('Form editable for level:', currentUserLevel);
        }
    }

    // Inisialisasi CKEditor saat dokumen siap
    $(document).ready(function() {
        initCKEditor('menimbang');
        initCKEditor('mengingat');
        initCKEditor('menetapkan');
        initCKEditor('tembusan');
        initCKEditor('penjelasan');
        initCKEditor('keputusan_1');

        <?php
        foreach ($bab_data as $bab_number => $bab) {
            if (!empty($bab['bagian']) && is_array($bab['bagian'])) {
                foreach ($bab['bagian'] as $bagian_number => $bagian) {
                    if (!empty($bagian['pasal']) && is_array($bagian['pasal'])) {
                        foreach ($bagian['pasal'] as $pasal_number => $pasal) {
                            $textarea_id = "isi_pasal_$pasal_number";
                            echo "initCKEditor('$textarea_id');\n";
                        }
                    }
                }
            }
            if (!empty($bab['pasal']) && is_array($bab['pasal'])) {
                foreach ($bab['pasal'] as $pasal_number => $pasal) {
                    $textarea_id = "isi_pasal_$pasal_number";
                    echo "initCKEditor('$textarea_id');\n";
                }
            }
        }
        foreach ($keputusan_data as $keputusan_number => $keputusan) {
            $textarea_id = $keputusan_number == 1 ? 'keputusan_1' : "keputusan_$keputusan_number";
            echo "initCKEditor('$textarea_id');";
            if (!empty($keputusan)) {
                echo "CKEDITOR.instances['$textarea_id'].setData(" . json_encode($keputusan) . ");";
            }
        }
        ?>

        $(".is-show-lampiran-kepala-dinas").hide();
        $(".is-show-lampiran-sk-tim").hide();
        $(".is-show-lampiran-daftar-hadir").hide();
        $('#peraturan-bupati-section').hide();
        $('#memutuskan-section').hide();
        $('#tembusan-section').hide();

        check_lampiran();
        tampilkanRevisi();

        setTimeout(function() {
            setFormAccess();
        }, 500);

        $('.bab-field').each(function() {
            const babNumber = $(this).data('number');
            updateRemovePasalButtons(babNumber);
            updateAddPasalButtonVisibility(babNumber); // Tambahkan ini
        });

        function smoothScrollToElement(element, offset = 100) {
            $('html, body').animate({
                scrollTop: $(element).offset().top - offset
            }, 600, 'swing');
        }

        // Tambahkan tooltip untuk buttons
        $('[data-toggle="tooltip"]').tooltip();

        // Highlight effect saat field baru ditambahkan
        function highlightNewField(element) {
            $(element).addClass('newly-added');
            setTimeout(() => {
                $(element).removeClass('newly-added');
            }, 2000);
        }

        // Event handler untuk tombol Preview
        $('#preview-btn').on('click', function() {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            var kategori_usulan = $('select[name="kategori_usulan"]').val();
            var kategori_usulan_id = $('select[name="kategori_usulan"] option:selected').data('id');
            var nama_peraturan = $('input[name="nama_peraturan"]').val();
            var menimbang = CKEDITOR.instances['menimbang'] ? CKEDITOR.instances['menimbang'].getData() : '';
            var mengingat = CKEDITOR.instances['mengingat'] ? CKEDITOR.instances['mengingat'].getData() : '';
            var menetapkan = CKEDITOR.instances['menetapkan'] ? CKEDITOR.instances['menetapkan'].getData() : '';
            var keputusan_1 = CKEDITOR.instances['keputusan_1'] ? CKEDITOR.instances['keputusan_1'].getData() : '';
            var tembusan = CKEDITOR.instances['tembusan'] ? CKEDITOR.instances['tembusan'].getData() : '';
            var judul_bab_1 = $('input[name="judul_bab[1]"]').val();

            if (!kategori_usulan || !nama_peraturan) {
                alert('Harap isi Kategori Usulan dan Tentang sebelum preview.');
                return;
            }

            if (kategori_usulan_id == 3) {
                if (!menimbang || !mengingat || !menetapkan || !keputusan_1 || !tembusan) {
                    alert('Harap isi semua field wajib (Menimbang, Mengingat, Menetapkan, Keputusan Kesatu, dan Tembusan) untuk Keputusan Bupati.');
                    return;
                }
            } else if (kategori_usulan_id == 1 || kategori_usulan_id == 2) {
                if (!menimbang || !mengingat || !judul_bab_1) {
                    alert('Harap isi semua field wajib (Menimbang, Mengingat, Judul Bab) untuk Peraturan Bupati.');
                    return;
                }
                if ($('.pasal-field').length === 0) {
                    alert('Harap tambahkan setidaknya satu pasal untuk Peraturan Bupati.');
                    return;
                }
            }

            var formData = new FormData($('#form-usulan')[0]);
            console.log('Form Data prepared for preview:', formData);

            $.ajax({
                url: '<?= base_url('nomor_register/preview_pdf_raperbup') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response, status, xhr) {
                    var contentType = xhr.getResponseHeader('Content-Type');
                    if (contentType === 'application/json') {
                        response.text().then(function(text) {
                            var json = JSON.parse(text);
                            alert(json.error || 'Gagal menghasilkan preview PDF');
                        });
                    } else {
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        var url = window.URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error, xhr);
                    alert('Gagal menghasilkan preview PDF: ' + error);
                }
            });
        });
    });

    // Event handler untuk tombol tambah bagian
    $(document).on('click', '.add-pasal-btn', function() {
        const babNumber = $(this).data('bab');
        const bagianNumber = $(this).data('bagian') || 0;
        addPasalField(babNumber, bagianNumber);

        // Reorganisasi setelah tambah
        setTimeout(function() {
            reorganizePasalFields();
            tampilkanRevisi(); // Perbarui revisi setelah menambah pasal
        }, 300);
    });

    // Event handler untuk tombol hapus pasal
    $(document).on('click', '.remove-pasal', function() {
        const pasalNumber = $(this).data('pasal');
        const pasalField = $(`.pasal-field[data-pasal="${pasalNumber}"]`);
        const babNumber = pasalField.data('bab');
        const babField = $(`.bab-field[data-number="${babNumber}"]`);
        const totalPasalInBab = babField.find('.pasal-field').length;

        if (totalPasalInBab === 1) {
            alert('Setidaknya satu pasal harus tetap ada di setiap bab.');
            return;
        }

        const textareaId = `isi_pasal_${pasalNumber}`;
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy(true);
        }

        pasalField.fadeOut(300, function() {
            $(this).remove();
            reorganizePasalFields();
            updateRemovePasalButtons(babNumber); // Tambahkan ini
            tampilkanRevisi();
        });
    });

    // Event handler untuk tombol tambah bagian
    $(document).on('click', '.add-bagian-btn', function() {
        const babNumber = $(this).data('bab');
        bagianCounter++;
        addBagianField(babNumber, bagianCounter);

        setTimeout(function() {
            reorganizeBagianFields();
            reorganizePasalFields();
            updateAddPasalButtonVisibility(babNumber); // Tambahkan ini
            tampilkanRevisi();
        }, 300);
    });

    // Event handler untuk tombol hapus bagian
    $(document).on('click', '.remove-bagian', function() {
        const bagianNumber = $(this).data('bagian');
        const bagianField = $(`.bagian-field[data-bagian="${bagianNumber}"]`);
        const babNumber = bagianField.data('bab');

        bagianField.find('.pasal-field').each(function() {
            const pasalNumber = $(this).data('pasal');
            const pasalTextareaId = `isi_pasal_${pasalNumber}`;
            if (CKEDITOR.instances[pasalTextareaId]) {
                CKEDITOR.instances[pasalTextareaId].destroy(true);
            }
        });

        bagianField.fadeOut(300, function() {
            $(this).remove();
            reorganizeBagianFields();
            reorganizePasalFields();
            updateAddPasalButtonVisibility(babNumber); // Tambahkan ini
            tampilkanRevisi();
        });
    });

    // Fungsi untuk menambah field bagian
    function addBagianField(babNumber, bagianNumber) {
        globalPasalCounter++;
        const firstPasalNumber = globalPasalCounter;
        const pasalTextareaId = `isi_pasal_${firstPasalNumber}`;
        const bagianNames = ['', 'Kesatu', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh'];
        const bagianName = bagianNames[bagianNumber] || `Ke-${bagianNumber}`;

        const newBagianField = `
            <div class="bagian-field" data-bab="${babNumber}" data-bagian="${bagianNumber}">
                <div class="field-header">
                    <span class="bagian-number">Bagian ${bagianName}</span>
                    <button type="button" class="btn btn-sm btn-remove-field remove-bagian" data-bagian="${bagianNumber}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="judul_bagian[${babNumber}][${bagianNumber}]" placeholder="Masukkan Judul Bagian ${bagianName}" required>
                </div>
                <div class="pasal-container" data-bab="${babNumber}" data-bagian="${bagianNumber}">
                    <div class="pasal-field" data-bab="${babNumber}" data-pasal="${firstPasalNumber}" data-bagian="${bagianNumber}">
                        <div class="field-header">
                            <span class="pasal-number">Pasal ${firstPasalNumber}</span>
                            <button type="button" class="btn btn-sm btn-remove-field remove-pasal" data-pasal="${firstPasalNumber}" style="display: none;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="form-group">
                            <textarea name="isi_pasal[${firstPasalNumber}]" id="${pasalTextareaId}" class="form-control" rows="3" placeholder="Masukkan isi Pasal ${firstPasalNumber}..." required></textarea>
                            <input type="hidden" name="pasal_bab_mapping[${firstPasalNumber}]" value="${babNumber}">
                            <input type="hidden" name="pasal_bagian_mapping[${firstPasalNumber}]" value="${bagianNumber}">
                        </div>
                    </div>
                    <div class="add-pasal-container">
                        <button type="button" class="btn btn-sm btn-add-pasal add-pasal-btn" data-bab="${babNumber}" data-bagian="${bagianNumber}">
                            <i class="fas fa-plus"></i> Tambah Pasal
                        </button>
                        <br>
                        <small class="text-muted mt-2 d-block">Klik untuk menambah pasal baru dalam bagian ini</small>
                    </div>
                </div>
            </div>
        `;

        $(`.bagian-container[data-bab="${babNumber}"] .add-bagian-container`).before(newBagianField);

        setTimeout(function() {
            initCKEditor(pasalTextareaId);
            updateRemovePasalButtons(babNumber);
            updateAddPasalButtonVisibility(babNumber); // Tambahkan ini
            setFormAccess();
        }, 100);

        $('html, body').animate({
            scrollTop: $(`.bagian-field[data-bagian="${bagianNumber}"]`).offset().top - 100
        }, 500);
    }

    // Fungsi untuk menghapus field bagian
    function removeBagianField(bagianNumber) {
        const bagianField = $(`.bagian-field[data-bagian="${bagianNumber}"]`);
        const babNumber = bagianField.data('bab');

        bagianField.find('.pasal-field').each(function() {
            const pasalNumber = $(this).data('pasal');
            const pasalTextareaId = `isi_pasal_${pasalNumber}`;
            if (CKEDITOR.instances[pasalTextareaId]) {
                CKEDITOR.instances[pasalTextareaId].destroy(true);
            }
        });

        bagianField.fadeOut(300, function() {
            $(this).remove();
            reorganizeBagianFields();
            reorganizePasalFields();
            updateAddPasalButtonVisibility(babNumber); // Tambahkan ini
        });
    }

    // Fungsi untuk menambah field pasal
    function addPasalField(babNumber, bagianNumber = 0) {
        globalPasalCounter++;
        const pasalNumber = globalPasalCounter;
        const textareaId = `isi_pasal_${pasalNumber}`;

        const newPasalField = `
            <div class="pasal-field" data-bab="${babNumber}" data-pasal="${pasalNumber}" data-bagian="${bagianNumber}">
                <div class="field-header">
                    <span class="pasal-number">Pasal ${pasalNumber}</span>
                    <button type="button" class="btn btn-sm btn-remove-field remove-pasal" data-pasal="${pasalNumber}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <div class="form-group">
                    <textarea name="isi_pasal[${pasalNumber}]" id="${textareaId}" class="form-control" rows="3" placeholder="Masukkan isi Pasal ${pasalNumber}..." required></textarea>
                    <input type="hidden" name="pasal_bab_mapping[${pasalNumber}]" value="${babNumber}">
                    <input type="hidden" name="pasal_bagian_mapping[${pasalNumber}]" value="${bagianNumber}">
                </div>
            </div>
        `;

        const containerSelector = bagianNumber === 0 ?
            `.pasal-container[data-bab="${babNumber}"][data-bagian="0"]` :
            `.pasal-container[data-bab="${babNumber}"][data-bagian="${bagianNumber}"]`;

        $(`${containerSelector} .add-pasal-container`).before(newPasalField);

        setTimeout(function() {
            initCKEditor(textareaId);
            setFormAccess();
            updateRemovePasalButtons(babNumber); // Tambahkan ini
        }, 100);

        $('html, body').animate({
            scrollTop: $(`.pasal-field[data-pasal="${pasalNumber}"]`).offset().top - 100
        }, 500);
    }

    // Fungsi untuk update tombol hapus pasal berdasarkan jumlah total pasal di bab
    function updateRemovePasalButtons(babNumber) {
        const babField = $(`.bab-field[data-number="${babNumber}"]`);
        const allPasalInBab = babField.find('.pasal-field');
        const totalPasal = allPasalInBab.length;

        if (totalPasal <= 1) {
            // Jika hanya ada 1 pasal di seluruh bab, sembunyikan tombol hapus
            allPasalInBab.find('.remove-pasal').hide();
        } else {
            // Jika ada lebih dari 1 pasal, tampilkan semua tombol hapus
            allPasalInBab.find('.remove-pasal').show();
        }
    }

    // Fungsi untuk menghapus field pasal
    function removePasalField(pasalNumber) {
        const pasalField = $(`.pasal-field[data-pasal="${pasalNumber}"]`);
        const babNumber = pasalField.data('bab');
        const babField = $(`.bab-field[data-number="${babNumber}"]`);
        const totalPasalInBab = babField.find('.pasal-field').length;

        if (totalPasalInBab === 1) {
            alert('Setidaknya satu pasal harus tetap ada di setiap bab.');
            return;
        }

        const textareaId = `isi_pasal_${pasalNumber}`;
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy(true);
        }

        pasalField.fadeOut(300, function() {
            $(this).remove();
            reorganizePasalFields();
            updateRemovePasalButtons(babNumber); // Tambahkan ini
        });
    }

    // Fungsi untuk reorganisasi penomoran pasal
    function reorganizePasalFields() {
        const allPasalFields = [];

        $('.bab-field').each(function() {
            const babNumber = $(this).data('number');

            $(this).find('.bagian-field').each(function() {
                $(this).find('.pasal-field').each(function() {
                    allPasalFields.push(this);
                });
            });

            $(this).find('.pasal-container[data-bagian="0"] > .pasal-field').each(function() {
                allPasalFields.push(this);
            });
        });

        let newPasalCounter = 0;

        allPasalFields.forEach(function(field) {
            newPasalCounter++;
            const oldPasalNumber = $(field).data('pasal');
            const babNumber = $(field).data('bab');
            const bagianNumber = $(field).data('bagian');
            const oldTextareaId = `isi_pasal_${oldPasalNumber}`;
            const newTextareaId = `isi_pasal_${newPasalCounter}`;

            let content = '';
            if (CKEDITOR.instances[oldTextareaId]) {
                content = CKEDITOR.instances[oldTextareaId].getData();
                CKEDITOR.instances[oldTextareaId].destroy(true);
            }

            $(field).attr('data-pasal', newPasalCounter);
            $(field).find('.pasal-number').text(`Pasal ${newPasalCounter}`);

            const textarea = $(field).find('textarea');
            textarea.attr('name', `isi_pasal[${newPasalCounter}]`);
            textarea.attr('id', newTextareaId);
            textarea.attr('placeholder', `Masukkan isi Pasal ${newPasalCounter}...`);

            const mappingFieldBab = $(field).find('input[name^="pasal_bab_mapping"]');
            mappingFieldBab.attr('name', `pasal_bab_mapping[${newPasalCounter}]`);
            mappingFieldBab.val(babNumber);

            const mappingFieldBagian = $(field).find('input[name^="pasal_bagian_mapping"]');
            mappingFieldBagian.attr('name', `pasal_bagian_mapping[${newPasalCounter}]`);
            mappingFieldBagian.val(bagianNumber);

            const removeBtn = $(field).find('.remove-pasal');
            removeBtn.attr('data-pasal', newPasalCounter);

            // HAPUS LOGIKA LAMA INI:
            // const pasalCountInContainer = $(field).closest('.pasal-container').find('.pasal-field').length;
            // if (pasalCountInContainer === 1) {
            //     removeBtn.hide();
            // } else {
            //     removeBtn.show();
            // }

            setTimeout(function() {
                initCKEditor(newTextareaId);
                if (content && CKEDITOR.instances[newTextareaId]) {
                    CKEDITOR.instances[newTextareaId].setData(content);
                }
            }, 200);
        });

        globalPasalCounter = newPasalCounter;

        // Update tombol hapus untuk setiap bab
        $('.bab-field').each(function() {
            const babNumber = $(this).data('number');
            updateRemovePasalButtons(babNumber);
        });

        tampilkanRevisi();
    }

    // Fungsi untuk reorganisasi penomoran bagian
    function reorganizeBagianFields() {
        $('.bab-field').each(function() {
            const babNumber = $(this).data('number');
            let newBagianCounter = 0;
            const bagianNames = ['', 'Kesatu', 'Kedua', 'Ketiga', 'Keempat', 'Kelima',
                'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh'
            ];

            $(this).find('.bagian-field').each(function() {
                newBagianCounter++;
                const oldBagianNumber = $(this).data('bagian');
                const bagianName = bagianNames[newBagianCounter] || `Ke-${newBagianCounter}`;

                $(this).attr('data-bagian', newBagianCounter);
                $(this).find('.bagian-number').text(`Bagian ${bagianName}`);

                const inputJudul = $(this).find('input[name^="judul_bagian"]');
                inputJudul.attr('name', `judul_bagian[${babNumber}][${newBagianCounter}]`);
                inputJudul.attr('placeholder', `Masukkan Judul Bagian ${bagianName}`);

                const pasalContainer = $(this).find('.pasal-container');
                pasalContainer.attr('data-bagian', newBagianCounter);

                const addPasalBtn = $(this).find('.add-pasal-btn');
                addPasalBtn.attr('data-bagian', newBagianCounter);

                const removeBtn = $(this).find('.remove-bagian');
                removeBtn.attr('data-bagian', newBagianCounter);

                $(this).find('.pasal-field').each(function() {
                    $(this).attr('data-bagian', newBagianCounter);
                    $(this).find('input[name^="pasal_bagian_mapping"]').val(newBagianCounter);
                });
            });
        });
        tampilkanRevisi(); // Perbarui revisi setelah reorganisasi
    }

    // Array untuk nama keputusan
    const keputusanNames = [
        '', 'KESATU', 'KEDUA', 'KETIGA', 'KEEMPAT', 'KELIMA',
        'KEENAM', 'KETUJUH', 'KEDELAPAN', 'KESEMBILAN', 'KESEPULUH',
        'KESEBELAS', 'KEDUA BELAS', 'KETIGA BELAS', 'KEEMPAT BELAS', 'KELIMA BELAS',
        'KEENAM BELAS', 'KETUJUH BELAS', 'KEDELAPAN BELAS', 'KESEMBILAN BELAS', 'KEDUA PULUH'
    ];

    $(document).on('click', '#add-keputusan', function() {
        if (keputusanCounter < 20) {
            keputusanCounter++;
            addKeputusanField(keputusanCounter);
        } else {
            alert('Maksimal 20 keputusan yang dapat ditambahkan.');
        }
    });

    $(document).on('click', '.remove-keputusan', function() {
        const fieldNumber = $(this).data('number');
        removeKeputusanField(fieldNumber);
    });

    function addKeputusanField(number) {
        const keputusanName = keputusanNames[number] || `KE-${number}`;
        const textareaId = `keputusan_${number}`;

        // Revisi container hanya muncul jika edit mode
        const revisiContainerHtml = isEditMode ? `<div class="revisi-container" id="revisi-memutuskan-${number}"></div>` : '';

        const newField = `
        <div class="keputusan-field" data-number="${number}">
            <div class="field-header">
                <span class="field-number">${keputusanName}</span>
                <button type="button" class="btn btn-sm btn-remove-field remove-keputusan" data-number="${number}">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
            <div class="form-with-revisi">
                <div class="form-main-content">
                    <textarea id="${textareaId}" name="keputusan[${number}]" class="form-control" rows="3" placeholder="Masukkan isi keputusan ${keputusanName.toLowerCase()}..." required></textarea>
                </div>
                ${revisiContainerHtml}
            </div>
        </div>
    `;

        $('#keputusan-container').append(newField);

        setTimeout(function() {
            initCKEditor(textareaId);
            if (isEditMode) {
                tampilkanRevisi(); // Inisialisasi revisi untuk field baru hanya di edit mode
            }
            setFormAccess(); // Apply form access untuk field baru
        }, 100);

        $('html, body').animate({
            scrollTop: $(`.keputusan-field[data-number="${number}"]`).offset().top - 100
        }, 500);
    }

    function removeKeputusanField(number) {
        if (number === 1) {
            alert('Keputusan KESATU tidak dapat dihapus.');
            return;
        }

        const textareaId = `keputusan_${number}`;
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy(true);
        }

        $(`.keputusan-field[data-number="${number}"]`).fadeOut(300, function() {
            $(this).remove();
            reorganizeKeputusanFields();
        });
    }

    function reorganizeKeputusanFields() {
        const fields = $('.keputusan-field').get();
        let tempKeputusanCounter = 0;

        fields.forEach(function(field, index) {
            const newNumber = index + 1;
            const oldNumber = $(field).attr('data-number');
            tempKeputusanCounter = newNumber;

            const keputusanName = keputusanNames[newNumber] || `KE-${newNumber}`;
            const oldTextareaId = newNumber === 1 ? 'keputusan_1' : `keputusan_${oldNumber}`;
            const newTextareaId = newNumber === 1 ? 'keputusan_1' : `keputusan_${newNumber}`;

            let content = '';
            if (CKEDITOR.instances[oldTextareaId]) {
                content = CKEDITOR.instances[oldTextareaId].getData();
                CKEDITOR.instances[oldTextareaId].destroy(true);
            }

            $(field).attr('data-number', newNumber);
            $(field).find('.field-number').text(keputusanName);

            const textarea = $(field).find('textarea');
            textarea.attr('name', `keputusan[${newNumber}]`);
            textarea.attr('id', newTextareaId);
            textarea.attr('placeholder', `Masukkan isi keputusan ${keputusanName.toLowerCase()}...`);

            const removeBtn = $(field).find('.remove-keputusan');
            removeBtn.attr('data-number', newNumber);
            if (newNumber === 1) {
                removeBtn.hide();
            } else {
                removeBtn.show();
            }

            setTimeout(function() {
                initCKEditor(newTextareaId);
                if (content && CKEDITOR.instances[newTextareaId]) {
                    CKEDITOR.instances[newTextareaId].setData(content);
                }
            }, 200);
        });

        keputusanCounter = tempKeputusanCounter;
        tampilkanRevisi(); // Perbarui revisi setelah reorganisasi
    }

    $(document).on('click', '#add-bab', function() {
        if (babCounter < 10) {
            babCounter++;
            addBabField(babCounter);

            // Reorganisasi setelah tambah
            setTimeout(function() {
                reorganizeBabFields();
            }, 300);
        } else {
            alert('Maksimal 10 bab yang dapat ditambahkan.');
        }
    });

    // Event handler untuk tombol hapus bab
    $(document).on('click', '.remove-bab', function() {
        const fieldNumber = $(this).data('number');

        if (fieldNumber === 1) {
            alert('Bab pertama tidak dapat dihapus.');
            return;
        }

        const babField = $(`.bab-field[data-number="${fieldNumber}"]`);

        babField.find('.pasal-field').each(function() {
            const pasalNumber = $(this).data('pasal');
            const pasalTextareaId = `isi_pasal_${pasalNumber}`;
            if (CKEDITOR.instances[pasalTextareaId]) {
                CKEDITOR.instances[pasalTextareaId].destroy(true);
            }
        });

        babField.fadeOut(300, function() {
            $(this).remove();
            reorganizeBabFields();
            reorganizeBagianFields();
            reorganizePasalFields();
        });
    });

    function addBabField(number) {
        const revisiContainerHtml = isEditMode ?
            `<div class="revisi-container" id="revisi-bab-${number}"></div>` : '';

        const newField = `
            <div class="bab-field" data-number="${number}">
                <div class="field-header">
                    <span class="field-number">BAB ${number}</span>
                    <button type="button" class="btn btn-sm btn-remove-field remove-bab" data-number="${number}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                
                <div class="form-with-revisi">
                    <div class="form-main-content">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Judul Bab <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="judul_bab[${number}]" 
                                    placeholder="Masukkan Judul Bab ${number}" required>
                            </div>
                        </div>
                        <div class="bagian-container" data-bab="${number}">
                            <div class="add-bagian-container">
                                <button type="button" class="btn btn-sm btn-add-bagian add-bagian-btn" data-bab="${number}">
                                    <i class="fas fa-plus"></i> Tambah Bagian
                                </button>
                                <br>
                                <small class="text-muted mt-2 d-block">Klik untuk menambah bagian baru dalam bab ini</small>
                            </div>
                            <div class="pasal-container" data-bab="${number}" data-bagian="0">
                                <div class="add-pasal-container">
                                    <button type="button" class="btn btn-sm btn-add-pasal add-pasal-btn" 
                                            data-bab="${number}" data-bagian="0">
                                        <i class="fas fa-plus"></i> Tambah Pasal
                                    </button>
                                    <br>
                                    <small class="text-muted mt-2 d-block">Klik untuk menambah pasal baru tanpa bagian</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    ${revisiContainerHtml}
                </div>
                <hr>
            </div>
        `;

        $('#bab-container .add-field-container').before(newField);

        setTimeout(function() {
            if (isEditMode) {
                tampilkanRevisi(); // Inisialisasi revisi untuk BAB baru
            }
            setFormAccess();
        }, 100);

        $('html, body').animate({
            scrollTop: $(`.bab-field[data-number="${number}"]`).offset().top - 100
        }, 500);
    }

    function removeBabField(number) {
        if (number === 1) {
            alert('Bab pertama tidak dapat dihapus.');
            return;
        }

        const babField = $(`.bab-field[data-number="${number}"]`);

        babField.find('.pasal-field').each(function() {
            const pasalNumber = $(this).data('pasal');
            const pasalTextareaId = `isi_pasal_${pasalNumber}`;
            if (CKEDITOR.instances[pasalTextareaId]) {
                CKEDITOR.instances[pasalTextareaId].destroy(true);
            }
        });

        babField.fadeOut(300, function() {
            $(this).remove();
            reorganizeBabFields();
            reorganizePasalFields();
        });
    }

    function reorganizeBabFields() {
        const fields = $('.bab-field').get();
        let tempBabCounter = 0;

        fields.forEach(function(field, index) {
            const newNumber = index + 1;
            tempBabCounter = newNumber;

            $(field).attr('data-number', newNumber);
            $(field).find('.field-number').first().text(`BAB ${newNumber}`);

            // Update revisi container ID
            const revisiContainer = $(field).find('.revisi-container');
            if (revisiContainer.length > 0) {
                revisiContainer.attr('id', `revisi-bab-${newNumber}`);
            }

            $(field).find('.bagian-container').attr('data-bab', newNumber);
            $(field).find('.add-bagian-btn').attr('data-bab', newNumber);

            $(field).find('.pasal-container').each(function() {
                $(this).attr('data-bab', newNumber);
            });

            $(field).find('.add-pasal-btn').attr('data-bab', newNumber);

            const judulInput = $(field).find('input[name^="judul_bab"]').first();
            judulInput.attr('name', `judul_bab[${newNumber}]`);
            judulInput.attr('placeholder', `Masukkan Judul Bab ${newNumber}`);

            const removeBtn = $(field).find('.remove-bab');
            removeBtn.attr('data-number', newNumber);
            if (newNumber === 1) {
                removeBtn.hide();
            } else {
                removeBtn.show();
            }

            $(field).find('.pasal-field').each(function() {
                $(this).attr('data-bab', newNumber);
                $(this).find('input[name^="pasal_bab_mapping"]').val(newNumber);
            });

            $(field).find('.bagian-field').each(function() {
                $(this).attr('data-bab', newNumber);
                $(this).find('input[name^="judul_bagian"]').each(function() {
                    const bagianNum = $(this).closest('.bagian-field').data('bagian');
                    $(this).attr('name', `judul_bagian[${newNumber}][${bagianNum}]`);
                });
            });
        });

        babCounter = tempBabCounter;
        tampilkanRevisi(); // Refresh semua revisi termasuk BAB
    }

    $('form').on('submit', function() {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });

    function view_detail(file, ekstensi) {
        let file_extension = ["pdf", "jpg", "jpeg", "png"];
        $("#showFormDetail").modal("show");

        if (file_extension.indexOf(ekstensi) >= 0) {
            if (ekstensi === "pdf") {
                $(".isi-content").html("<div class='embed-responsive embed-responsive-16by9'>" +
                    "<iframe class='embed-responsive-item' src='" + file + "' allowfullscreen></iframe>" +
                    "</div>");
            } else {
                $(".isi-content").html("<img class='img-fluid' src='" + file + "' alt='File Preview' />");
            }
        } else {
            $(".isi-content").html(
                "<div class='text-center'>" +
                "<img height='300px' src='" + base_url + "assets/img/drawkit/drawkit-full-stack-man-colour.svg' alt='image'>" +
                "<h6>Dokumen file tidak bisa dilihat karena ekstensi file tidak didukung untuk ditampilkan di browser.</h6>" +
                "<a class='btn btn-success' download href='" + file + "'>Download File</a>" +
                "</div>"
            );
        }
    }

    function check_lampiran() {
        $("input[name='file_lampiran']").prop("required", false);
        $("input[name='file_lampiran_sk_tim']").prop("required", false);
        $("input[name='file_lampiran_daftar_hadir']").prop("required", false);

        const kategori_usulan_hidden = $("input[name='kategori_usulan_hidden']").val();
        const selectedKategori = $("select[name='kategori_usulan'] option:selected");
        const kategori_usulan_id = selectedKategori.data("id");

        const $peraturanBupatiSection = $('#peraturan-bupati-section');
        const $memutuskanSection = $('#memutuskan-section');
        const $tembusanSection = $('#tembusan-section');
        const $lampiranKepalaDinas = $(".is-show-lampiran-kepala-dinas");
        const $lampiranSkTim = $(".is-show-lampiran-sk-tim");
        const $lampiranDaftarHadir = $(".is-show-lampiran-daftar-hadir");
        const $penjelasanSection = $("#penjelasan-section");
        const $lampiranUsulanSection = $(".lampiran-usulan-section");

        if (kategori_usulan_id === 1 || kategori_usulan_id === 2) {
            $peraturanBupatiSection.show();
            $memutuskanSection.hide();
            $tembusanSection.hide();
            $lampiranUsulanSection.hide();
        } else if (kategori_usulan_id === 3) {
            $peraturanBupatiSection.hide();
            $memutuskanSection.show();
            $tembusanSection.show();
            $lampiranUsulanSection.show();
        } else {
            $peraturanBupatiSection.hide();
            $memutuskanSection.hide();
            $tembusanSection.hide();
            $lampiranUsulanSection.hide();
        }

        if (kategori_usulan_id === 1) {
            $penjelasanSection.show();
        } else {
            $penjelasanSection.hide();
        }

        if (kategori_usulan_id === 1 || kategori_usulan_id === 2) {
            $lampiranKepalaDinas.show();
            $lampiranSkTim.show();
            $lampiranDaftarHadir.show();
            if (kategori_usulan_hidden === "") {
                $("input[name='file_lampiran']").prop("required", true);
                $("input[name='file_lampiran_sk_tim']").prop("required", true);
                $("input[name='file_lampiran_daftar_hadir']").prop("required", true);
            }
        } else if (kategori_usulan_id === 3) {
            $lampiranKepalaDinas.show();
            $lampiranSkTim.hide();
            $lampiranDaftarHadir.hide();
            if (kategori_usulan_hidden === "") {
                $("input[name='file_lampiran']").prop("required", true);
                $("input[name='file_lampiran_sk_tim']").prop("required", false);
                $("input[name='file_lampiran_daftar_hadir']").prop("required", false);
            }
        } else {
            $lampiranKepalaDinas.hide();
            $lampiranSkTim.hide();
            $lampiranDaftarHadir.hide();
            $("input[name='file_lampiran']").prop("required", false);
            $("input[name='file_lampiran_sk_tim']").prop("required", false);
            $("input[name='file_lampiran_daftar_hadir']").prop("required", false);
        }
    }

    // Fungsi untuk update visibilitas tombol tambah pasal di luar bagian
    function updateAddPasalButtonVisibility(babNumber) {
        const babField = $(`.bab-field[data-number="${babNumber}"]`);
        const bagianCount = babField.find('.bagian-field').length;
        const addPasalContainerOutsideBagian = babField.find('.pasal-container[data-bagian="0"] > .add-pasal-container');

        if (bagianCount > 0) {
            // Jika ada bagian, sembunyikan tombol tambah pasal di luar bagian
            addPasalContainerOutsideBagian.hide();
        } else {
            // Jika tidak ada bagian, tampilkan tombol tambah pasal di luar bagian
            addPasalContainerOutsideBagian.show();
        }
    }
</script>
<style>
    /* Layout untuk form dengan revisi */
    .form-with-revisi {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .form-main-content {
        flex: 1;
        min-width: 0;
        /* Penting untuk flex item dengan overflow */
    }

    .revisi-container {
        flex-shrink: 0;
        /* Tidak mengecil */
        width: 380px;
        min-width: 380px;
        max-width: 380px;
        max-height: 700px;
        overflow-y: auto;
        padding: 15px;
        background-color: #f8f9fa;
        /* border-left: 4px solid #007bff;
        border-radius: 8px; */
        position: sticky;
        top: 20px;
        /* Sticky saat scroll */
    }

    /* Scrollbar custom untuk revisi container */
    .revisi-container::-webkit-scrollbar {
        width: 6px;
    }

    .revisi-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .revisi-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .revisi-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .revisi-header {
        font-weight: bold;
        color: #495057;
        margin-bottom: 15px;
        font-size: 0.95em;
        border-bottom: 2px solid #007bff;
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .revisi-accordion .card {
        margin-bottom: 8px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    .revisi-accordion .card-header {
        padding: 8px 12px;
        background-color: #e9ecef;
        cursor: pointer;
        border-radius: 6px 6px 0 0;
        transition: background-color 0.2s;
    }

    .revisi-accordion .card-header:hover {
        background-color: #dee2e6;
    }

    .revisi-accordion .card-header h6 {
        margin: 0;
        font-size: 0.85em;
        color: #495057;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .revisi-accordion .card-body {
        padding: 10px;
        font-size: 0.85em;
        background-color: #fff;
    }

    .revisi-content {
        line-height: 1.6;
        word-wrap: break-word;
    }

    .revisi-info {
        font-size: 0.75em;
        color: #6c757d;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .revisi-actions {
        margin-top: 8px;
        text-align: right;
        display: flex;
        gap: 5px;
        justify-content: flex-end;
    }

    .revisi-actions button {
        font-size: 0.75em;
        padding: 4px 10px;
    }

    .form-add-revisi {
        background-color: #fff;
        padding: 12px;
        border-radius: 6px;
        border: 2px dashed #28a745;
        margin-top: 15px;
    }

    .form-add-revisi textarea {
        font-size: 0.85em;
        resize: vertical;
        min-height: 80px;
    }

    .form-add-revisi button {
        font-size: 0.85em;
        margin-top: 8px;
        width: 100%;
    }

    .badge-user {
        font-size: 0.75em;
        padding: 3px 8px;
    }

    .no-revisi {
        color: #6c757d;
        font-size: 0.85em;
        font-style: italic;
        text-align: center;
        padding: 20px;
        background-color: #fff;
        border-radius: 6px;
    }

    .alert-info-revisi {
        font-size: 0.85em;
        margin-top: 15px;
        padding: 10px;
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        border-radius: 6px;
        color: #0c5460;
        text-align: center;
    }

    .alert-info-revisi i {
        margin-right: 5px;
    }

    /* Responsive untuk tablet */
    @media (max-width: 1200px) {
        .revisi-container {
            width: 320px;
            min-width: 320px;
            max-width: 320px;
        }
    }

    /* Responsive untuk mobile - stack vertical */
    @media (max-width: 768px) {
        .form-with-revisi {
            flex-direction: column;
        }

        .revisi-container {
            width: 100%;
            min-width: 100%;
            max-width: 100%;
            position: relative;
            top: 0;
            margin-top: 15px;
        }
    }
</style>