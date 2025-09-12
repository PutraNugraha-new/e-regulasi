<style>
    .user-image-custom {
        margin-bottom: 10px;
    }

    .keputusan-field {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        animation: fadeInSlide 0.4s ease-out;
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

    .btn-add-field {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        color: white;
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
</style>
<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <?php echo form_open_multipart(); ?>
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
                                <option value="">-- PILIH KATEGORI</option>
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

                    <!-- Peratruan Bupati -->
                    <div class="card card-primary" id="peraturan-bupati-section">
                        <div class="card-body">
                            <div id="bab-container">
                                <div class="bab-field" data-number="1">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">Judul Bab <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" name="judul_bab[1]" placeholder="Masukkan Judul Bab 1" value="<?php echo !empty($bab_data[1]['judul']) ? htmlspecialchars($bab_data[1]['judul']) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">Isi Bab <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <textarea name="isi_bab[1]" id="isi_bab_1"><?php echo !empty($bab_data[1]['isi']) ? $bab_data[1]['isi'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div class="add-field-container">
                                <button type="button" id="add-bab" class="btn btn-sm btn-add-field">
                                    <i class="icon-plus"></i> Tambah Bab
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
                                    </div>
                                    <textarea name="keputusan[1]" class="form-control" rows="3" placeholder="Masukkan isi keputusan kesatu..." required><?php echo !empty($content) ? $content->keputusan_1 : ""; ?></textarea>
                                </div>
                            </div>

                            <div class="add-field-container">
                                <button type="button" id="add-keputusan" class="btn btn-sm btn-add-field">
                                    <i class="icon-plus"></i> Tambah Keputusan
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

                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">File Usulan <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_usulan;
                            }
                            ?>
                            <input <?php echo !empty($content) ? "" : "required"; ?> type="file" class="form-control" name="file_upload" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
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
                        <button type="submit" class="btn btn-primary">Simpan <i class="icon-paperplane ml-2"></i></button>
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
        // Pastikan CKEditor belum ada pada textarea ini
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy();
        }
        CKEDITOR.replace(textareaId, ckeditorConfig);
    }

    // Inisialisasi CKEditor saat dokumen siap
    $(document).ready(function() {
        // CKEditor untuk form utama
        initCKEditor('menimbang');
        initCKEditor('mengingat');
        initCKEditor('menetapkan');
        initCKEditor('tembusan');
        initCKEditor('penjelasan');

        // CKEditor untuk setiap bab peraturan bupati
        <?php for ($i = 1; $i <= 6; $i++): ?>
            initCKEditor('isi_bab_<?php echo $i; ?>');
        <?php endfor; ?>

        // CKEditor untuk keputusan pertama
        initCKEditor('keputusan[1]');

        // Sembunyikan section yang tidak relevan berdasarkan kategori awal
        $(".is-show-lampiran-kepala-dinas").hide();
        $(".is-show-lampiran-sk-tim").hide();
        $(".is-show-lampiran-daftar-hadir").hide();
        $('#peraturan-bupati-section').hide(); // Sembunyikan di awal, akan ditampilkan berdasarkan kategori
        $('#memutuskan-section').hide();
        $('#tembusan-section').hide();

        // Panggil check_lampiran() untuk mengatur visibilitas awal berdasarkan kategori yang mungkin sudah terisi
        check_lampiran();
    });

    // Array untuk menyimpan nama keputusan dalam Bahasa Indonesia
    const keputusanNames = [
        '', 'KESATU', 'KEDUA', 'KETIGA', 'KEEMPAT', 'KELIMA',
        'KEENAM', 'KETUJUH', 'KEDELAPAN', 'KESEMBILAN', 'KESEPULUH',
        'KESEBELAS', 'KEDUA BELAS', 'KETIGA BELAS', 'KEEMPAT BELAS', 'KELIMA BELAS',
        'KEENAM BELAS', 'KETUJUH BELAS', 'KEDELAPAN BELAS', 'KESEMBILAN BELAS', 'KEDUA PULUH'
    ];

    let keputusanCounter = 1; // Dimulai dari 1 karena KESATU sudah ada

    // Event listener untuk tombol "Tambah Keputusan"
    $(document).on('click', '#add-keputusan', function() {
        if (keputusanCounter < 20) { // Batasi hingga 20 keputusan
            keputusanCounter++;
            addKeputusanField(keputusanCounter);
        } else {
            alert('Maksimal 20 keputusan yang dapat ditambahkan.');
        }
    });

    // Event listener untuk tombol hapus keputusan (menggunakan delegasi event)
    $(document).on('click', '.remove-keputusan', function() {
        const fieldNumber = $(this).data('number');
        removeKeputusanField(fieldNumber);
    });

    // Fungsi untuk menambah field keputusan
    function addKeputusanField(number) {
        const keputusanName = keputusanNames[number] || `KE-${number}`; // Gunakan nama dari array atau format KE-N
        const textareaId = `keputusan_${number}`; // ID unik untuk textarea CKEditor
        const newField = `
            <div class="keputusan-field" data-number="${number}">
                <div class="field-header">
                    <span class="field-number">${keputusanName}</span>
                    <button type="button" class="btn btn-sm btn-remove-field remove-keputusan" data-number="${number}">
                        <i class="icon-trash"></i> Hapus
                    </button>
                </div>
                <textarea id="${textareaId}" name="keputusan[${number}]" class="form-control" rows="3" placeholder="Masukkan isi keputusan ${keputusanName.toLowerCase()}..." required></textarea>
            </div>
        `;

        $('#keputusan-container').append(newField);

        // Inisialisasi CKEditor untuk textarea yang baru ditambahkan, setelah elemen ditambahkan ke DOM
        setTimeout(function() {
            initCKEditor(textareaId);
        }, 100); // Delay singkat untuk memastikan elemen sudah siap

        // Scroll ke field yang baru ditambahkan
        $('html, body').animate({
            scrollTop: $(`.keputusan-field[data-number="${number}"]`).offset().top - 100
        }, 500);
    }

    // Fungsi untuk menghapus field keputusan
    function removeKeputusanField(number) {
        if (number === 1) {
            alert('Keputusan KESATU tidak dapat dihapus.');
            return;
        }

        // Hancurkan instance CKEditor sebelum menghapus elemen DOM
        const textareaId = `keputusan_${number}`;
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy();
        }

        // Animasi fade out sebelum menghapus elemen
        $(`.keputusan-field[data-number="${number}"]`).fadeOut(300, function() {
            $(this).remove();
            reorganizeKeputusanFields(); // Atur ulang nomor dan nama setelah penghapusan
        });
    }

    // Fungsi untuk mengatur ulang nomor, nama, dan atribut setelah penghapusan field
    function reorganizeKeputusanFields() {
        const fields = $('.keputusan-field').get(); // Dapatkan semua elemen .keputusan-field
        let tempKeputusanCounter = 0; // Counter sementara untuk re-organisasi

        fields.forEach(function(field, index) {
            const newNumber = index + 1; // Nomor baru dimulai dari 1
            const oldNumber = $(field).attr('data-number'); // Nomor lama
            tempKeputusanCounter = newNumber; // Update counter utama

            const keputusanName = keputusanNames[newNumber] || `KE-${newNumber}`; // Dapatkan nama keputusan yang sesuai

            // ID CKEditor lama dan baru
            const oldTextareaId = newNumber === 1 ? 'keputusan[1]' : `keputusan_${oldNumber}`;
            const newTextareaId = newNumber === 1 ? 'keputusan[1]' : `keputusan_${newNumber}`;

            // Simpan konten CKEditor sebelum destroy
            let content = '';
            if (CKEDITOR.instances[oldTextareaId]) {
                content = CKEDITOR.instances[oldTextareaId].getData();
                CKEDITOR.instances[oldTextareaId].destroy();
            }

            // Update atribut elemen field
            $(field).attr('data-number', newNumber);
            $(field).find('.field-number').text(keputusanName); // Update teks nomor keputusan

            // Update elemen textarea
            const textarea = $(field).find('textarea');
            textarea.attr('name', `keputusan[${newNumber}]`); // Update name atribut untuk pengiriman form
            textarea.attr('id', newTextareaId); // Update id untuk inisialisasi CKEditor
            textarea.attr('placeholder', `Masukkan isi keputusan ${keputusanName.toLowerCase()}...`); // Update placeholder

            // Update tombol hapus
            const removeBtn = $(field).find('.remove-keputusan');
            removeBtn.attr('data-number', newNumber); // Update data-number pada tombol hapus
            if (newNumber === 1) {
                removeBtn.hide(); // Sembunyikan tombol hapus untuk KESATU
            } else {
                removeBtn.show(); // Tampilkan tombol hapus untuk yang lain
            }

            // Re-inisialisasi CKEditor dengan konten yang tersimpan, setelah atribut diupdate
            setTimeout(function() {
                initCKEditor(newTextareaId);
                if (content && CKEDITOR.instances[newTextareaId]) {
                    CKEDITOR.instances[newTextareaId].setData(content); // Set kembali konten
                }
            }, 200); // Sedikit delay untuk memastikan semua update DOM selesai
        });

        keputusanCounter = tempKeputusanCounter; // Update counter global setelah re-organisasi
    }

    // Tambahkan variabel counter untuk bab
    let babCounter = 1; // Dimulai dari 1 karena Bab 1 sudah ada

    // Tambahkan setelah bagian inisialisasi CKEditor dalam $(document).ready()
    $(document).ready(function() {
        // ... kode inisialisasi CKEditor yang sudah ada ...

        // Event listener untuk tombol "Tambah Bab"
        $(document).on('click', '#add-bab', function() {
            if (babCounter < 10) { // Batasi hingga 10 bab
                babCounter++;
                addBabField(babCounter);
            } else {
                alert('Maksimal 10 bab yang dapat ditambahkan.');
            }
        });

        // Event listener untuk tombol hapus bab (menggunakan delegasi event)
        $(document).on('click', '.remove-bab', function() {
            const fieldNumber = $(this).data('number');
            removeBabField(fieldNumber);
        });
    });

    // Fungsi untuk menambah field bab
    function addBabField(number) {
        const textareaId = `isi_bab_${number}`; // ID unik untuk textarea CKEditor
        const newField = `
        <div class="bab-field" data-number="${number}">
            <div class="field-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span class="field-number" style="font-weight: bold; color: #495057; font-size: 1.1em;">BAB ${number}</span>
                <button type="button" class="btn btn-sm btn-remove-field remove-bab" data-number="${number}" style="background: linear-gradient(45deg, #dc3545, #e74c3c); border: none; color: white;">
                    <i class="icon-trash"></i> Hapus
                </button>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Judul Bab <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name="judul_bab[${number}]" placeholder="Masukkan Judul Bab ${number}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Isi Bab <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <textarea name="isi_bab[${number}]" id="${textareaId}"></textarea>
                </div>
            </div>
            <hr>
        </div>
    `;

        $('#bab-container').append(newField);

        // Inisialisasi CKEditor untuk textarea yang baru ditambahkan
        setTimeout(function() {
            initCKEditor(textareaId);
        }, 100); // Delay singkat untuk memastikan elemen sudah siap

        // Scroll ke field yang baru ditambahkan
        $('html, body').animate({
            scrollTop: $(`.bab-field[data-number="${number}"]`).offset().top - 100
        }, 500);
    }

    // Fungsi untuk menghapus field bab
    function removeBabField(number) {
        if (number === 1) {
            alert('Bab pertama tidak dapat dihapus.');
            return;
        }

        // Hancurkan instance CKEditor sebelum menghapus elemen DOM
        const textareaId = `isi_bab_${number}`;
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy();
        }

        // Animasi fade out sebelum menghapus elemen
        $(`.bab-field[data-number="${number}"]`).fadeOut(300, function() {
            $(this).remove();
            reorganizeBabFields(); // Atur ulang nomor setelah penghapusan
        });
    }

    // Fungsi untuk mengatur ulang nomor dan atribut setelah penghapusan field bab
    function reorganizeBabFields() {
        const fields = $('.bab-field').get(); // Dapatkan semua elemen .bab-field
        let tempBabCounter = 0; // Counter sementara untuk re-organisasi

        fields.forEach(function(field, index) {
            const newNumber = index + 1; // Nomor baru dimulai dari 1
            const oldNumber = $(field).attr('data-number'); // Nomor lama
            tempBabCounter = newNumber; // Update counter utama

            // ID CKEditor lama dan baru
            const oldTextareaId = `isi_bab_${oldNumber}`;
            const newTextareaId = `isi_bab_${newNumber}`;

            // Simpan konten CKEditor sebelum destroy
            let content = '';
            if (CKEDITOR.instances[oldTextareaId]) {
                content = CKEDITOR.instances[oldTextareaId].getData();
                CKEDITOR.instances[oldTextareaId].destroy();
            }

            // Update atribut elemen field
            $(field).attr('data-number', newNumber);
            $(field).find('.field-number').text(`BAB ${newNumber}`); // Update teks nomor bab

            // Update elemen input judul bab
            const judulInput = $(field).find('input[name^="judul_bab"]');
            judulInput.attr('name', `judul_bab[${newNumber}]`);
            judulInput.attr('placeholder', `Masukkan Judul Bab ${newNumber}`);

            // Update elemen textarea
            const textarea = $(field).find('textarea');
            textarea.attr('name', `isi_bab[${newNumber}]`);
            textarea.attr('id', newTextareaId);

            // Update tombol hapus
            const removeBtn = $(field).find('.remove-bab');
            removeBtn.attr('data-number', newNumber);
            if (newNumber === 1) {
                removeBtn.hide(); // Sembunyikan tombol hapus untuk BAB 1
            } else {
                removeBtn.show(); // Tampilkan tombol hapus untuk yang lain
            }

            // Re-inisialisasi CKEditor dengan konten yang tersimpan
            setTimeout(function() {
                initCKEditor(newTextareaId);
                if (content && CKEDITOR.instances[newTextareaId]) {
                    CKEDITOR.instances[newTextareaId].setData(content);
                }
            }, 200);
        });

        babCounter = tempBabCounter; // Update counter global
    }

    // Pastikan CKEditor terupdate sebelum form disubmit
    $('form').on('submit', function() {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });

    // Fungsi untuk melihat detail file
    function view_detail(file, ekstensi) {
        let file_extension = ["pdf", "jpg", "jpeg", "png"]; // Ekstensi yang bisa ditampilkan inline
        $("#showFormDetail").modal("show"); // Tampilkan modal

        if (file_extension.indexOf(ekstensi) >= 0) {
            if (ekstensi === "pdf") {
                $(".isi-content").html("<div class='embed-responsive embed-responsive-16by9'>" + // Gunakan aspect ratio yang sesuai
                    "<iframe class='embed-responsive-item' src='" + file + "' allowfullscreen></iframe>" +
                    "</div>");
            } else { // Gambar
                $(".isi-content").html("<img class='img-fluid' src='" + file + "' alt='File Preview' />"); // img-fluid agar responsif
            }
        } else { // File tidak bisa ditampilkan
            $(".isi-content").html(
                "<div class='text-center'>" +
                "<img height='300px' src='" + base_url + "assets/img/drawkit/drawkit-full-stack-man-colour.svg' alt='image'>" + // Ganti dengan path aset Anda
                "<h6>Dokumen file tidak bisa dilihat karena ekstensi file tidak didukung untuk ditampilkan di browser.</h6>" +
                "<a class='btn btn-success' download href='" + file + "'>Download File</a>" +
                "</div>"
            );
        }
    }

    // Fungsi untuk mengatur visibilitas dan requirement lampiran berdasarkan kategori usulan
    function check_lampiran() {
        // Reset required attribute terlebih dahulu
        $("input[name='file_lampiran']").prop("required", false);
        $("input[name='file_lampiran_sk_tim']").prop("required", false);
        $("input[name='file_lampiran_daftar_hadir']").prop("required", false);

        const kategori_usulan_hidden = $("input[name='kategori_usulan_hidden']").val();
        const selectedKategori = $("select[name='kategori_usulan'] option:selected");
        const kategori_usulan_id = selectedKategori.data("id"); // Ambil data-id dari option yang dipilih

        const $peraturanBupatiSection = $('#peraturan-bupati-section');
        const $memutuskanSection = $('#memutuskan-section');
        const $tembusanSection = $('#tembusan-section');
        const $lampiranKepalaDinas = $(".is-show-lampiran-kepala-dinas");
        const $lampiranSkTim = $(".is-show-lampiran-sk-tim");
        const $lampiranDaftarHadir = $(".is-show-lampiran-daftar-hadir");
        const $penjelasanSection = $("#penjelasan-section");

        // Logika untuk menampilkan/menyembunyikan section "Peraturan Bupati", "Memutuskan", dan "Tembusan"
        if (kategori_usulan_id === 1 || kategori_usulan_id === 2) { // Kategori yang menggunakan Peraturan Bupati
            $peraturanBupatiSection.show();
            $memutuskanSection.hide();
            $tembusanSection.hide();
        } else if (kategori_usulan_id === 3) { // Kategori yang menggunakan Keputusan Bupati
            $peraturanBupatiSection.hide();
            $memutuskanSection.show();
            $tembusanSection.show();
        } else { // Kategori lain atau pilihan default
            $peraturanBupatiSection.hide();
            $memutuskanSection.hide();
            $tembusanSection.hide();
        }

        if (kategori_usulan_id === 1) {
            $penjelasanSection.show();
        } else {
            $penjelasanSection.hide();
        }

        // Logika untuk menampilkan/menyembunyikan dan mengatur 'required' untuk lampiran
        if (kategori_usulan_id === 1 || kategori_usulan_id === 2) {
            $lampiranKepalaDinas.show();
            $lampiranSkTim.show();
            $lampiranDaftarHadir.show();
            // Jika ini adalah form edit (ada value di hidden input), lampiran tidak 'required'
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
                $("input[name='file_lampiran_sk_tim']").prop("required", false); // Tidak required
                $("input[name='file_lampiran_daftar_hadir']").prop("required", false); // Tidak required
            }
        } else { // Kategori tidak dipilih atau kategori lain yang tidak memerlukan lampiran khusus
            $lampiranKepalaDinas.hide();
            $lampiranSkTim.hide();
            $lampiranDaftarHadir.hide();
            // Reset required jika section disembunyikan
            $("input[name='file_lampiran']").prop("required", false);
            $("input[name='file_lampiran_sk_tim']").prop("required", false);
            $("input[name='file_lampiran_daftar_hadir']").prop("required", false);
        }
    }
</script>