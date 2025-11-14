<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">SKPD</label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="skpd" onchange="get_data_peraturan()">
                                        <option value="">-- PILIH SKPD --</option>
                                        <?php
                                        foreach ($skpd as $key => $value) {
                                            ?>
                                            <option value="<?php echo $value->id_master_satker; ?>">
                                                <?php echo $value->nama; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Filter</label>
                                <div class="col-lg-10">
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="filter" value="all" class="selectgroup-input"
                                                onclick="get_data_peraturan()">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> All</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="filter" value="belum" class="selectgroup-input"
                                                checked="" onclick="get_data_peraturan()">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-times"></i> Belum Diperiksa</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="filter" value="sudah" class="selectgroup-input"
                                                onclick="get_data_peraturan()">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-clipboard-check"></i> Sudah Diperiksa</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <input type="hidden" name="usulan_peraturan" />
                            <h4>Peraturan :</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column list-peraturan">
                                <li>Belum Ada Peraturan</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-4 col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h4><span class='last_file'></span></h4>
                            <div class="card-header-action">
                                <a href="#disetujui" onclick="change_status('1')" class="btn btn-info">Disetujui</a>
                                <a href="#tidakDisetujui" onclick="change_status('2')" class="btn btn-info">Tidak
                                    Disetujui</a>
                            </div>
                        </div>
                        <div class="card-body" style="background-color: #f4f6f9;">
                            <div class="activities list-activites">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="showPanelDitolak" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Ditolak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Catatan</label>
                    <div class="col-lg-10">
                        <textarea style="height: 100px;" class="form-control" name="catatan"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control" name="file_upload"
                            accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        <small class="form-text text-muted">
                            Max. Upload Size : 2 MB
                        </small>
                        <small class="form-text text-muted">
                            Type File : doc, docx, & pdf
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_ditolak()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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

<style>
    .border-left-warning {
        border-left: 6px solid #f39c12 !important;
        background: linear-gradient(90deg, #fef9e6 0%, #fdf6e3 100%) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.15) !important;
    }

    .badge-lg {
        font-size: 1rem !important;
        padding: 0.75rem 1.5rem !important;
    }

    .alert-icon i {
        animation: spin 3s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .activity {
        opacity: 0;
        animation: fadeInUp 0.6s forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    $("a[href$='#disetujui']").hide();
    $("a[href$='#tidakDisetujui']").hide();

    function show_detail_peraturan(id_peraturan, e) {
        if (e != undefined) {
            $(".list-peraturan-active").removeClass("active");
            $(e).addClass("active");
        }

        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $("input[name='usulan_peraturan']").val("");

        if (id_peraturan) {
            $("input[name='usulan_peraturan']").val(id_peraturan);
            check_disetujui_tidak_disetujui_kasubbag();
            get_last_file();

            $.ajax({
                url: base_url + 'monitoring_raperbup/request/get_detail_peraturan',
                data: { id_peraturan: id_peraturan },
                type: 'GET',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                    $(".list-activites").html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted"></i></div>');
                },
                success: function (response) {
                    let html = "";

                    // === BANNER SEDANG DIPROSES - VERSI JFT (PALING GANTENG) ===
                    if (response.length > 0 && response[0].processing_status) {
                        let processor = response[0].processing_by_name || 'Kasubbag / Tim Teknis';
                        let since = response[0].processing_date || 'Tanggal tidak diketahui';
                        let statusText = response[0].processing_status || 'Sedang Diproses';

                        html += `
                    <div class="alert alert-warning border-left-warning shadow-lg mb-4 animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon">
                                <i class="fas fa-cog fa-spin fa-2x text-warning"></i>
                            </div>
                            <div class="alert-detail ml-4">
                                <h5 class="mb-1 text-dark font-weight-bold">
                                    <i class="fas fa-hourglass-half mr-2"></i>${statusText}
                                </h5>
                                <p class="mb-0 text-muted">
                                    <strong>Oleh:</strong> ${processor}<br>
                                    <strong>Sejak:</strong> ${since}
                                </p>
                            </div>
                            <div class="ml-auto">
                                <span class="badge badge-pill badge-warning badge-lg shadow-sm px-4 py-3">
                                    <i class="fas fa-tools mr-1"></i> SEDANG DIPROSES
                                </span>
                            </div>
                        </div>
                    </div>`;
                    }

                    // === TAMPILKAN SEMUA AKTIVITAS ===
                    let firstRejectedIndex = -1;
                    $.each(response, function (index, value) {
                        if (value.catatan_ditolak && firstRejectedIndex === -1) {
                            firstRejectedIndex = index;
                        }
                    });

                    $.each(response, function (index, value) {
                        html += `
                    <div class="activity animate__animated animate__fadeInUp" style="animation-delay: ${index * 100}ms;">
                        <div class="activity-icon ${value.class_color} text-white shadow-dark">
                            <i class="fas fa-user-alt"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary font-weight-bold">${value.tanggal_custom}</span>
                                ${value.file ? '<span class="bullet"></span>' + value.file : ''}
                                <div class="ml-4 float-right dropdown">
                                    ${value.action_delete || ''}
                                </div>
                            </div>
                            <p class="mb-2">${value.status_terakhir}</p>`;

                        // Tombol Revisi hanya muncul sekali di catatan ditolak pertama
                        if (value.catatan_ditolak && index === firstRejectedIndex) {
                            html += `
                        <div class="mt-3">
                            <a href="${base_url}monitoring_raperbup/edit_usulan_raperbup/${id_peraturan}" 
                               class="btn btn-warning btn-sm shadow-sm mr-2">
                               <i class="fas fa-edit"></i> Revisi Usulan
                            </a>
                        </div>`;
                        }

                        html += `</div></div>`;
                    });

                    $(".list-activites").html(html);
                },
                complete: function () {
                    HoldOn.close();
                },
                error: function () {
                    $(".list-activites").html(`
                    <div class="text-center py-5 text-danger">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h6>Gagal memuat detail peraturan</h6>
                    </div>`);
                    HoldOn.close();
                }
            });
        }
    }

    var selectedUsulanId = '<?php echo isset($selected_usulan_id) ? $selected_usulan_id : ''; ?>';
    var selectedSkpdId = '<?php echo isset($selected_skpd_id) ? $selected_skpd_id : ''; ?>';

    $(document).ready(function () {
        // Hapus ini semua! JANGAN load otomatis lagi!
        // get_data_peraturan(); ← DULU SALAH DI SINI!

        // Set filter default ke "Belum Diperiksa"
        $("input[name='filter'][value='belum']").prop('checked', true);

        // Kalau ada selectedSkpdId dari notifikasi, langsung set & load
        if (selectedSkpdId) {
            $("select[name='skpd']").val(selectedSkpdId).trigger('change');
        }

        // Kalau ada selectedUsulanId (dari notifikasi), kita tetap siapin
        if (selectedUsulanId) {
            // Kita akan load datanya nanti setelah SKPD dipilih
        }

        // Tampilkan pesan awal yang ramah
        $(".list-peraturan").html(`
        <li class="text-center text-muted py-5">
            <i class="fas fa-search fa-3x mb-3 d-block opacity-50"></i>
            <h6>Silakan pilih SKPD terlebih dahulu</h6>
            <small>Data peraturan akan muncul otomatis</small>
        </li>
    `);
    });
    
    function get_data_peraturan() {
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $(".list-activites").html("");
        $(".last_file").html("");
        $(".list-peraturan").html("<li>Belum Ada Peraturan</li>");
        let filter = $("input[name='filter']:checked").val();
        let skpd = $("select[name='skpd']").val();

        var data = {
            filter: filter,
            skpd: skpd
        };
        if (selectedUsulanId) {
            data.usulan_id = atob(selectedUsulanId); // Decode base64
        }

        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_peraturan',
            data: data,
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                let id_encrypt = "";
                let list_peraturan = "";
                let selectedIdEncrypt = null;
                if (response.length != 0) {
                    $.each(response, function (index, value) {
                        list_peraturan += "<li class='nav-item hr-bottom'><a href='#' class='nav-link list-peraturan-active' onclick=\"show_detail_peraturan('" + value.id_encrypt + "',this)\">" + value.nama_peraturan + "</a></li>";
                        // Find the id_encrypt for the selected usulan_id
                        if (selectedUsulanId && value.id_usulan_raperbup == atob(selectedUsulanId)) {
                            selectedIdEncrypt = value.id_encrypt;
                        }
                    });
                    $(".list-peraturan").html(list_peraturan);
                    // Highlight the selected peraturan
                    if (selectedIdEncrypt) {
                        let targetLink = $(".list-peraturan a[onclick*='" + selectedIdEncrypt + "']");
                        if (targetLink.length > 0) {
                            $(".list-peraturan-active").removeClass("active");
                            targetLink.addClass("active");
                            // Directly call show_detail_peraturan
                            show_detail_peraturan(selectedIdEncrypt);
                        }
                    }
                }
            },
            complete: function () {
                HoldOn.close();
            },
            error: function (xhr, status, error) {
                console.log('Error loading peraturan:', xhr.responseText);
                swal('Error', 'Gagal mengambil data: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
            }
        });
    }

    function change_status(status) {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        if (status == '2') {
            $("#showPanelDitolak").modal("show");
        } else {
            swal({
                title: 'Apakah anda yakin mengubah menyetujui usulan ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: base_url + 'monitoring_raperbup/setuju_ditolak_jft',
                            data: {
                                id_peraturan: id_peraturan,
                                status: status
                            },
                            type: 'POST',
                            beforeSend: function () {
                                HoldOn.open(optionsHoldOn);
                            },
                            success: function (response) {
                                get_data_peraturan();
                                if (response) {
                                    $("input[name='usulan_peraturan']").val("");
                                    swal('Berhasil', 'Status berhasil diubah', 'success');
                                } else {
                                    swal('Gagal', 'Status tidak bisa diubah', 'error');
                                }
                            },
                            complete: function (response) {
                                HoldOn.close();
                            }
                        });
                    } else {
                        swal('Batal', 'Data masih tersimpan!', 'error').then(function (results) {
                            HoldOn.close();
                            if (result.results) {
                                show_detail_peraturan(id_peraturan);
                            }
                        });
                    }
                });
        }
    }

    function check_disetujui_tidak_disetujui_kasubbag() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/check_disetujui_tidak_disetujui_jft',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                if (response) {
                    $("a[href$='#disetujui']").show();
                    $("a[href$='#tidakDisetujui']").show();
                } else {
                    $("a[href$='#disetujui']").hide();
                    $("a[href$='#tidakDisetujui']").hide();
                }
            },
            complete: function () {
                HoldOn.close();
            }
        });
    }

    function save_ditolak() {
        let id_usulan_raperbup = $("input[name='usulan_peraturan']").val();
        let catatan = $("textarea[name='catatan']").val();
        let files = $("input[name='file_upload']")[0].files[0];

        if (!catatan && !files) {
            swal('Gagal', 'Catatan atau File Perbaikan Wajib Diisi', 'error');
        } else {
            let fd = new FormData();
            fd.append('id_usulan_raperbup', id_usulan_raperbup);
            fd.append('catatan', catatan);
            fd.append('file_upload', files);
            $.ajax({
                url: base_url + 'monitoring_raperbup/raperbup_ditolak',
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    $("#showPanelDitolak").modal("toggle");
                    $("textarea[name='catatan']").val("");
                    $("input[name='file_upload']").val("");
                    get_data_peraturan();
                    if (response) {
                        $("input[name='usulan_peraturan']").val("");
                        swal('Berhasil', 'Status berhasil diubah', 'success');
                    } else {
                        swal('Gagal', 'Status tidak bisa diubah', 'error');
                    }
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }
    }

    function confirm_delete(id_trx_raperbup) {
        let id_usulan_raperbup = $("input[name='usulan_peraturan']").val();
        swal({
            title: 'Apakah anda yakin menghapus data ini?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'monitoring_raperbup/delete_trx_raperbup',
                        data: {
                            id_trx_raperbup: id_trx_raperbup
                        },
                        type: 'GET',
                        beforeSend: function () {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function (response) {
                            get_data_peraturan();
                            if (response) {
                                $("input[name='usulan_peraturan']").val("");
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                swal('Gagal', 'Data tidak bisa dihapus', 'error');
                            }
                        },
                        complete: function (response) {
                            HoldOn.close();
                        }
                    });
                } else {
                    swal('Batal', 'Data masih tersimpan!', 'error').then(function (results) {
                        HoldOn.close();
                        if (result.results) {
                            show_detail_peraturan(id_usulan_raperbup);
                        }
                    });
                }
            });
    }

    function get_last_file() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_last_file',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                let html = "<table>";
                html += "<tr><td>File Revisi</td><td style='padding:5px;'>:</td><td>" + response.usulan + "</td></tr>";
                if (response.lampiran_group) {
                    html += "<tr><td>Lampiran</td><td style='padding:5px;'>:</td><td>" + response.lampiran_group + "</td></tr>";
                }
                html += "</table>";
                $(".last_file").html(html);
            },
            complete: function (response) {
                HoldOn.close();
            }
        });
    }

    function view_detail(file, ekstensi) {
        let file_extension = ["pdf", "jpg", "jpeg", "png"];
        $("#showFormDetail").modal("show");
        if (file_extension.indexOf(ekstensi) >= 0) {
            if (ekstensi == "pdf") {
                $(".isi-content").html("<div class='embed-responsive embed-responsive-1by1'>" +
                    "<iframe class='embed-responsive-item' src='" + file + "'></iframe>" +
                    "</div>");
            } else {
                $(".isi-content").html("<img width='1100' src='" + file + "' />");
            }

        } else {
            $(".isi-content").html("<div class='text-center'><img height='300px' src='" + base_url + "assets/img/drawkit/drawkit-full-stack-man-colour.svg' alt='image'><h6>Dokumen file tidak bisa di lihat karena ekstensi file tidak didukung untuk dilihat di browser</h6><a class='btn btn-success' download href='" + file + "'>Download</a></div>");
        }
    }
</script>