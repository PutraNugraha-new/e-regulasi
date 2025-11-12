<div class="main-content">
    <section class="section">
        <input type="hidden" name="id_usulan_raperbup" value="<?php echo $id_usulan_raperbup; ?>" />
        <?php echo $breadcrumb_main; ?>

        <!-- Flash Message -->
        <?php if (!empty($this->session->flashdata('message'))): ?>
            <div
                class="alert <?php echo ($this->session->flashdata('type-alert') == 'success' ? 'alert-success' : 'alert-danger'); ?> alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>×</span></button>
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <!-- CARD LAMPIRAN + TOMBOL PERBAIKI -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="lampiran mb-0"></h4>
                            <div class="card-header-action">
                                <?php if ($status_upload_perbaikan): ?>
                                    <a href="<?php echo base_url('usulan_raperbup/edit_usulan_raperbup/' . $id_usulan_raperbup); ?>"
                                        class="btn btn-warning btn-icon">
                                        Perbaiki
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- AKTIVITAS + BANNER SEDANG DIPROSES -->
                    <div class="activities p-3">
                        <!-- Banner akan muncul otomatis di sini -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Upload Perbaikan -->
<div id="showPanelUploadPerbaikan" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Perbaikan</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="get_detail_usulan_raperbup()">
                    <span>×</span>
                </button>
            </div>
            <?php echo form_open_multipart(base_url() . "usulan_raperbup/upload_perbaikan"); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="hidden" name="id_usulan_raperbup_modal" />
                        <input required type="file" class="form-control" name="file_upload"
                            accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        <small class="form-text text-muted">Max. Upload Size : 2 MB</small>
                        <small class="form-text text-muted">Type File : doc, docx, & pdf</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="get_detail_usulan_raperbup()">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Upload Hasil Rapat -->
<div id="showPanelUploadPerbaikanHasilRapat" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Hasil Rapat</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="get_detail_usulan_raperbup()">
                    <span>×</span>
                </button>
            </div>
            <?php echo form_open_multipart(base_url() . "usulan_raperbup/upload_perbaikan_hasil_rapat"); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="hidden" name="id_usulan_raperbup_modal" />
                        <input required type="file" class="form-control" name="file_upload"
                            accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        <small class="form-text text-muted">Max. Upload Size : 2 MB</small>
                        <small class="form-text text-muted">Type File : doc, docx, & pdf</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="get_detail_usulan_raperbup()">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal View File -->
<div id="showFormDetail" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body isi-content"></div>
        </div>
    </div>
</div>

<style>
    .banner-processing-opd {
        border-left: 8px solid #3498db !important;
        background: linear-gradient(90deg, #ebf3fd 0%, #d6e6fc 100%) !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 30px rgba(52, 152, 219, 0.2) !important;
        animation: pulse-blue 2s infinite;
    }

    @keyframes pulse-blue {
        0% {
            box-shadow: 0 8px 30px rgba(52, 152, 219, 0.2);
        }

        50% {
            box-shadow: 0 8px 45px rgba(52, 152, 219, 0.35);
        }

        100% {
            box-shadow: 0 8px 30px rgba(52, 152, 219, 0.2);
        }
    }
</style>

<script>
    // LOAD SEMUA DATA SEKALIGUS SAAT HALAMAN DIBUKA
    $(document).ready(function () {
        get_detail_usulan_raperbup();
        get_file_lampiran();
    });

    function get_detail_usulan_raperbup() {
        let id = $("input[name='id_usulan_raperbup']").val();
        if (!id) return;

        $.ajax({
            url: base_url + 'usulan_raperbup/request/get_detail_usulan_raperbup',
            data: { id_usulan_raperbup: id },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
                $(".activities").html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted"></i></div>');
            },
            success: function (response) {
                let html = "";

                if (response.length > 0) {
                    let statusText = response[0].status_terakhir || '';
                    let textLower = statusText.toLowerCase();

                    // === LOGIKA LENGKAP: SEDANG DIPROSES (termasuk Usulan Baru) ===
                    let isProcessing = textLower.includes('sedang diproses oleh') ||
                        textLower.includes('menunggu review jft') ||
                        textLower.includes('usulan baru');

                    // === STATUS FINAL (TOLAK, SETUJU, PUBLISH, PERBAIKAN) ===
                    let isFinal = textLower.includes('usulan perbaikan') ||
                        textLower.includes('tidak disetujui') ||
                        textLower.includes('ditolak') ||
                        textLower.includes('ditangguhkan') ||
                        textLower.includes('di publish') ||
                        textLower.includes('disetujui');

                    // === TAMPILKAN BANNER ===
                    if (isProcessing && !isFinal) {
                        let oleh = 'Admin Hukum'; // default untuk Usulan Baru
                        let sejak = response[0].tanggal_custom || 'Tanggal tidak diketahui';

                        // Kalau "Sedang diproses oleh [Nama]"
                        if (textLower.includes('sedang diproses oleh')) {
                            let match = statusText.match(/Sedang diproses oleh (.*?)(<\/div>|$)/i);
                            oleh = match ? match[1].trim() : 'Bagian Hukum';
                        }
                        // Kalau "Menunggu review JFT"
                        else if (textLower.includes('menunggu review jft')) {
                            oleh = 'JFT';
                        }

                        html += `
            <div class="alert banner-processing-opd mb-4 animate__animated animate__fadeIn" 
                 style="border-left: 5px solid #007bff; background: #f0f8ff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px;">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-cog fa-spin fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1 text-primary font-weight-bold" style="font-size: 1.1rem;">
                            SEDANG DIPROSES
                        </h5>
                        <p class="mb-1 small text-muted">
                            <strong>Sedang diproses oleh:</strong> <span class="text-primary font-weight-bold">${oleh}</span><br>
                            <strong>Sejak:</strong> ${sejak}
                        </p>
                        <span class="badge badge-primary badge-pill px-3 py-2 font-weight-bold">
                            MENUNGGU TINDAK LANJUT
                        </span>
                    </div>
                </div>
            </div>`;
                    }
                }

                // === TAMPILKAN SEMUA AKTIVITAS ===
                $.each(response, function (i, v) {
                    html += `
        <div class="activity animate__animated animate__fadeInUp" style="animation-delay:${i * 100}ms">
            <div class="activity-icon bg-primary text-white shadow-primary">
                <i class="fas fa-thumbtack"></i>
            </div>
            <div class="activity-detail">
                <div class="mb-2">
                    <span class="text-job text-primary font-weight-bold">${v.tanggal_custom}</span>
                </div>
                <p class="mb-0">${v.status_terakhir}</p>
            </div>
        </div>`;
                });

                $(".activities").html(html);
            },
            complete: function () { HoldOn.close(); }
        });
    }

    function get_file_lampiran() {
        let id = $("input[name='id_usulan_raperbup']").val();
        if (!id) return;

        $.ajax({
            url: base_url + 'usulan_raperbup/request/get_file_lampiran',
            data: { id_usulan_raperbup: id },
            type: 'GET',
            beforeSend: function () { HoldOn.open(optionsHoldOn); },
            success: function (response) {
                if (response) {
                    let html = "<table class='table table-sm table-borderless'>";

                    html += `<tr><td><strong>Lampiran Kepala Dinas</strong></td><td>:</td><td>${response.lampiran_kepala_dinas}</td></tr>`;

                    if (response.lampiran_sk_tim) {
                        html += `<tr><td><strong>Lampiran SK Tim</strong></td><td>:</td><td>${response.lampiran_sk_tim}</td></tr>`;
                    }
                    if (response.lampiran_daftar_hadir) {
                        html += `<tr><td><strong>Lampiran Daftar Hadir</strong></td><td>:</td><td>${response.lampiran_daftar_hadir}</td></tr>`;
                    }
                    html += "</table>";

                    $(".lampiran").html(html);
                }
            },
            complete: function () { HoldOn.close(); }
        });
    }

    // Modal Upload
    function panel_upload_perbaikan() {
        $("#showPanelUploadPerbaikan").modal("show");
        $("input[name='id_usulan_raperbup_modal']").val($("input[name='id_usulan_raperbup']").val());
    }

    function panel_upload_perbaikan_hasil_rapat() {
        $("#showPanelUploadPerbaikanHasilRapat").modal("show");
        $("input[name='id_usulan_raperbup_modal']").val($("input[name='id_usulan_raperbup']").val());
    }

    // View File
    function view_detail(file, ekstensi) {
        let allowed = ["pdf", "jpg", "jpeg", "png"];
        $("#showFormDetail").modal("show");

        if (allowed.includes(ekstensi)) {
            if (ekstensi === "pdf") {
                $(".isi-content").html(`
                    <div class="embed-responsive embed-responsive-1by1">
                        <iframe class="embed-responsive-item" src="${file}"></iframe>
                    </div>
                `);
            } else {
                $(".isi-content").html(`<img src="${file}" class="img-fluid" />`);
            }
        } else {
            $(".isi-content").html(`
                <div class="text-center p-5">
                    <img height="200" src="${base_url}assets/img/drawkit/drawkit-full-stack-man-colour.svg" alt="Not supported">
                    <h6 class="mt-3">File tidak bisa ditampilkan di browser</h6>
                    <a class="btn btn-success" href="${file}" download>Download File</a>
                </div>
            `);
        }
    }

    // Refresh setelah upload
    $('#showPanelUploadPerbaikan form, #showPanelUploadPerbaikanHasilRapat form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let fd = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            data: fd,
            type: 'POST',
            contentType: false,
            processData: false,
            beforeSend: function () { HoldOn.open(optionsHoldOn); },
            success: function (res) {
                form.closest('.modal').modal('hide');
                get_detail_usulan_raperbup();
                get_file_lampiran();
                swal('Berhasil', 'File perbaikan berhasil diupload!', 'success');
            },
            error: function () {
                swal('Gagal', 'Terjadi kesalahan saat upload', 'error');
            },
            complete: function () { HoldOn.close(); }
        });
    });
</script>