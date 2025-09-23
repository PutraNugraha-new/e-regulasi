<style>
    .user-image-custom {
        margin-bottom: 10px;
    }

    .keputusan-field,
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
</style>
<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <?php echo form_open_multipart('', ['id' => 'form-usulan']); ?>
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
                                    $selected = "";
                                    if (!empty($content)) {
                                        if ($value->id_kategori_usulan == $content->kategori_usulan_id) {
                                            $selected = "selected";
                                        }
                                    }
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
                            <input required type="text" class="form-control" name="nama_peraturan" value="<?php echo !empty($content) ? $content->nama_peraturan : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Menimbang <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="menimbang" id="menimbang">
                                <ol type="a">
                                    <li>.....</li>
                                    <li>.....</li>
                                </ol>
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Mengingat <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="mengingat" id="mengingat">
                                <ol>
                                    <li>.....</li>
                                    <li>.....</li>
                                </ol>
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Menetapkan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="menetapkan" id="menetapkan"></textarea>
                        </div>
                    </div>

                    <!-- Peraturan Bupati -->
                    <div class="card card-primary" id="peraturan-bupati-section">
                        <div class="card-body">
                            <div id="bab-container">
                                <div class="bab-field" data-number="1">
                                    <div class="field-header">
                                        <span class="field-number">BAB 1</span>
                                        <button type="button" class="btn btn-sm btn-remove-field remove-bab" data-number="1" style="display: none;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">Judul Bab <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" name="judul_bab[1]" placeholder="Masukkan Judul Bab 1" value="<?php echo !empty($bab_data[1]['judul']) ? htmlspecialchars($bab_data[1]['judul']) : ''; ?>">
                                        </div>
                                    </div>

                                    <!-- Container untuk Bagian dan Pasal -->
                                    <div class="bagian-container" data-bab="1">
                                        <!-- Tombol Tambah Bagian -->
                                        <div class="add-bagian-container">
                                            <button type="button" class="btn btn-sm btn-add-bagian add-bagian-btn" data-bab="1">
                                                <i class="fas fa-plus"></i> Tambah Bagian
                                            </button>
                                            <br>
                                            <small class="text-muted mt-2 d-block">Klik untuk menambah bagian baru dalam bab ini</small>
                                        </div>
                                        <!-- Pasal langsung tanpa bagian -->
                                        <div class="pasal-container" data-bab="1" data-bagian="0">
                                            <div class="add-pasal-container">
                                                <button type="button" class="btn btn-sm btn-add-pasal add-pasal-btn" data-bab="1" data-bagian="0">
                                                    <i class="fas fa-plus"></i> Tambah Pasal
                                                </button>
                                                <br>
                                                <small class="text-muted mt-2 d-block">Klik untuk menambah pasal baru tanpa bagian</small>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>

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
                                <textarea name="penjelasan" id="penjelasan"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- end Peraturan bupati -->
                    <!-- Keputusan Bupati -->
                    <div class="form-group row" id="memutuskan-section">
                        <label class="col-form-label col-lg-2">Memutuskan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div id="keputusan-container">
                                <div class="keputusan-field" data-number="1">
                                    <div class="field-header">
                                        <span class="field-number">KESATU</span>
                                        <button type="button" class="btn btn-sm btn-remove-field remove-keputusan" data-number="1" style="display: none;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <textarea name="keputusan[1]" id="keputusan_1" class="form-control" rows="3" placeholder="Masukkan isi keputusan kesatu..." required><?php echo !empty($content) ? $content->keputusan_1 : ""; ?></textarea>
                                </div>
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
                            <textarea name="tembusan" id="tembusan">
                                Tembusan :
                                <ol>
                                    <li>.....</li>
                                    <li>.....</li>
                                </ol>
                            </textarea>
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
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : pdf
                            </small>
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
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
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
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
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
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" id="preview-btn" class="btn btn-secondary mr-2">Preview PDF <i class="fas fa-eye ml-2"></i></button>
                        <button type="submit" class="btn btn-primary">Simpan & Download <i class="fas fa-paper-plane ml-2"></i></button>
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
            <div class="modal-body isi-content">
            </div>
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
            items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Outdent', 'Indent']
        }, {
            name: 'maximize',
            items: ['Maximize']
        }]
    };

    // Fungsi untuk inisialisasi CKEditor pada textarea tertentu
    function initCKEditor(textareaId) {
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy(true);
        }
        CKEDITOR.replace(textareaId, ckeditorConfig);
    }

    // Counter global untuk pasal dan bagian
    let globalPasalCounter = 0;
    let bagianCounter = 0;

    // Inisialisasi CKEditor saat dokumen siap
    $(document).ready(function() {
        initCKEditor('menimbang');
        initCKEditor('mengingat');
        initCKEditor('menetapkan');
        initCKEditor('tembusan');
        initCKEditor('penjelasan');
        initCKEditor('keputusan_1');

        $(".is-show-lampiran-kepala-dinas").hide();
        $(".is-show-lampiran-sk-tim").hide();
        $(".is-show-lampiran-daftar-hadir").hide();
        $('#peraturan-bupati-section').hide();
        $('#memutuskan-section').hide();
        $('#tembusan-section').hide();

        check_lampiran();

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
                // Validasi setidaknya satu pasal ada
                if ($('.pasal-field').length === 0) {
                    alert('Harap tambahkan setidaknya satu pasal untuk Peraturan Bupati.');
                    return;
                }
            }

            var formData = new FormData($('#form-usulan')[0]);

            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                url: '<?= base_url('usulan_raperbup/preview_pdf_raperbup') ?>',
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
    $(document).on('click', '.add-bagian-btn', function() {
        const babNumber = $(this).data('bab');
        bagianCounter++;
        addBagianField(babNumber, bagianCounter);
    });

    // Event handler untuk tombol hapus bagian
    $(document).on('click', '.remove-bagian', function() {
        const bagianNumber = $(this).data('bagian');
        removeBagianField(bagianNumber);
    });

    // Event handler untuk tombol tambah pasal
    $(document).on('click', '.add-pasal-btn', function() {
        const babNumber = $(this).data('bab');
        const bagianNumber = $(this).data('bagian') || 0;
        addPasalField(babNumber, bagianNumber);
    });

    // Event handler untuk tombol hapus pasal
    $(document).on('click', '.remove-pasal', function() {
        const pasalNumber = $(this).data('pasal');
        removePasalField(pasalNumber);
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
        }, 100);

        $('html, body').animate({
            scrollTop: $(`.bagian-field[data-bagian="${bagianNumber}"]`).offset().top - 100
        }, 500);
    }

    // Fungsi untuk menghapus field bagian
    function removeBagianField(bagianNumber) {
        const bagianField = $(`.bagian-field[data-bagian="${bagianNumber}"]`);

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
        }, 100);

        $('html, body').animate({
            scrollTop: $(`.pasal-field[data-pasal="${pasalNumber}"]`).offset().top - 100
        }, 500);
    }

    // Fungsi untuk menghapus field pasal
    function removePasalField(pasalNumber) {
        const pasalField = $(`.pasal-field[data-pasal="${pasalNumber}"]`);
        const pasalCountInContainer = pasalField.closest('.pasal-container').find('.pasal-field').length;

        if (pasalCountInContainer === 1) {
            alert('Setidaknya satu pasal harus tetap ada di setiap bagian atau bab.');
            return;
        }

        const textareaId = `isi_pasal_${pasalNumber}`;
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy(true);
        }

        pasalField.fadeOut(300, function() {
            $(this).remove();
            reorganizePasalFields();
        });
    }

    // Fungsi untuk reorganisasi penomoran pasal
    function reorganizePasalFields() {
        const allPasalFields = $('.pasal-field').get().sort(function(a, b) {
            return parseInt($(a).data('pasal')) - parseInt($(b).data('pasal'));
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
            const pasalCountInContainer = $(field).closest('.pasal-container').find('.pasal-field').length;
            if (pasalCountInContainer === 1) {
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

        globalPasalCounter = newPasalCounter;
    }

    // Fungsi untuk reorganisasi penomoran bagian
    function reorganizeBagianFields() {
        const allBagianFields = $('.bagian-field').get().sort(function(a, b) {
            return parseInt($(a).data('bagian')) - parseInt($(b).data('bagian'));
        });

        let newBagianCounter = 0;
        const bagianNames = ['', 'Kesatu', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh'];

        allBagianFields.forEach(function(field) {
            newBagianCounter++;
            const oldBagianNumber = $(field).data('bagian');
            const babNumber = $(field).data('bab');
            const bagianName = bagianNames[newBagianCounter] || `Ke-${newBagianCounter}`;

            $(field).attr('data-bagian', newBagianCounter);
            $(field).find('.bagian-number').text(`Bagian ${bagianName}`);

            const inputJudul = $(field).find('input[name^="judul_bagian"]');
            inputJudul.attr('name', `judul_bagian[${babNumber}][${newBagianCounter}]`);
            inputJudul.attr('placeholder', `Masukkan Judul Bagian ${bagianName}`);

            const pasalContainer = $(field).find('.pasal-container');
            pasalContainer.attr('data-bagian', newBagianCounter);

            const addPasalBtn = $(field).find('.add-pasal-btn');
            addPasalBtn.attr('data-bagian', newBagianCounter);

            const removeBtn = $(field).find('.remove-bagian');
            removeBtn.attr('data-bagian', newBagianCounter);
        });

        bagianCounter = newBagianCounter;
    }

    // Array untuk nama keputusan
    const keputusanNames = [
        '', 'KESATU', 'KEDUA', 'KETIGA', 'KEEMPAT', 'KELIMA',
        'KEENAM', 'KETUJUH', 'KEDELAPAN', 'KESEMBILAN', 'KESEPULUH',
        'KESEBELAS', 'KEDUA BELAS', 'KETIGA BELAS', 'KEEMPAT BELAS', 'KELIMA BELAS',
        'KEENAM BELAS', 'KETUJUH BELAS', 'KEDELAPAN BELAS', 'KESEMBILAN BELAS', 'KEDUA PULUH'
    ];

    let keputusanCounter = 1;

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
        const newField = `
            <div class="keputusan-field" data-number="${number}">
                <div class="field-header">
                    <span class="field-number">${keputusanName}</span>
                    <button type="button" class="btn btn-sm btn-remove-field remove-keputusan" data-number="${number}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <textarea id="${textareaId}" name="keputusan[${number}]" class="form-control" rows="3" placeholder="Masukkan isi keputusan ${keputusanName.toLowerCase()}..." required></textarea>
            </div>
        `;

        $('#keputusan-container').append(newField);

        setTimeout(function() {
            initCKEditor(textareaId);
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
    }

    let babCounter = 1;

    $(document).on('click', '#add-bab', function() {
        if (babCounter < 10) {
            babCounter++;
            addBabField(babCounter);
        } else {
            alert('Maksimal 10 bab yang dapat ditambahkan.');
        }
    });

    $(document).on('click', '.remove-bab', function() {
        const fieldNumber = $(this).data('number');
        removeBabField(fieldNumber);
    });

    function addBabField(number) {
        const newField = `
            <div class="bab-field" data-number="${number}">
                <div class="field-header">
                    <span class="field-number">BAB ${number}</span>
                    <button type="button" class="btn btn-sm btn-remove-field remove-bab" data-number="${number}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Judul Bab <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="judul_bab[${number}]" placeholder="Masukkan Judul Bab ${number}" required>
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
                            <button type="button" class="btn btn-sm btn-add-pasal add-pasal-btn" data-bab="${number}" data-bagian="0">
                                <i class="fas fa-plus"></i> Tambah Pasal
                            </button>
                            <br>
                            <small class="text-muted mt-2 d-block">Klik untuk menambah pasal baru tanpa bagian</small>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        `;

        $('#bab-container').append(newField);

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
            $(field).find('.field-number').text(`BAB ${newNumber}`);

            $(field).find('.pasal-container').attr('data-bab', newNumber);
            $(field).find('.add-pasal-btn').attr('data-bab', newNumber);
            $(field).find('.bagian-container').attr('data-bab', newNumber);
            $(field).find('.add-bagian-btn').attr('data-bab', newNumber);

            const judulInput = $(field).find('input[name^="judul_bab"]');
            judulInput.attr('name', `judul_bab[${newNumber}]`);
            judulInput.attr('placeholder', `Masukkan Judul Bab ${newNumber}`);

            const removeBtn = $(field).find('.remove-bab');
            removeBtn.attr('data-number', newNumber);
            if (newNumber === 1) {
                removeBtn.hide();
            } else {
                removeBtn.show();
            }
        });

        babCounter = tempBabCounter;
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
</script>