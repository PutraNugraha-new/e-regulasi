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
    .banner-processing-kasubbag {
        border-left: 8px solid #f39c12 !important;
        background: linear-gradient(90deg, #fff8e6 0%, #fff3cd 100%) !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 30px rgba(243, 156, 18, 0.25) !important;
        animation: glow 2s infinite alternate;
    }

    @keyframes glow {
        from {
            box-shadow: 0 8px 30px rgba(243, 156, 18, 0.25);
        }

        to {
            box-shadow: 0 8px 45px rgba(243, 156, 18, 0.45);
        }
    }
</style>

<script>
    $("a[href$='#disetujui']").hide();
    $("a[href$='#tidakDisetujui']").hide();

    var selectedUsulanId = '<?= isset($selected_usulan_id) ? $selected_usulan_id : ""; ?>';
    var selectedSkpdId = '<?= isset($selected_skpd_id) ? $selected_skpd_id : ""; ?>';

    $(document).ready(function() {
        // Set SKPD kalau dari notifikasi
        if (selectedSkpdId) {
            $("select[name='skpd']").val(selectedSkpdId);
        }

        // Event change SKPD & Filter
        $("select[name='skpd'], input[name='filter']").on('change', function() {
            get_data_peraturan();
        });

        get_data_peraturan();
    });

    function get_data_peraturan() {
        $(".list-activites").html("");
        $(".list-peraturan").html("<li>Memuat data...</li>"); // Ubah pesan loading

        $("a[href$='#disposisi']").hide();
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $("a[href$='#publish']").hide();
        $("a[href$='#terimafinal']").hide();
        $("a[href$='#tolakfinal']").hide();

        $(".last_file").html("");
        let skpd = $("select[name='skpd']").val();
        let filter = $("input[name='filter']:checked").val();

        // HAPUS pengecekan if (skpd), langsung panggil AJAX
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_peraturan',
            data: {
                skpd: skpd,
                filter: filter
            }, // skpd bisa kosong, backend akan handle
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                let list_peraturan = "";
                if (response.length != 0) {
                    $.each(response, function(index, value) {
                        list_peraturan += "<li class='nav-item hr-bottom'><a href='#' class='nav-link list-peraturan-active' onclick=\"show_detail_peraturan('" + value.id_encrypt + "',this)\">" + value.nama_peraturan + "</a></li>";
                    });
                    $(".list-peraturan").html(list_peraturan);
                } else {
                    // Pesan yang lebih informatif
                    $(".list-peraturan").html("<li class='text-center text-muted py-3'><i class='fas fa-inbox'></i> Tidak ada data peraturan</li>");
                }
            },
            error: function() {
                $(".list-peraturan").html("<li class='text-center text-danger py-3'><i class='fas fa-exclamation-triangle'></i> Gagal memuat data</li>");
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function show_detail_peraturan(id_peraturan, e) {
        if (e) {
            $(".list-peraturan-active").removeClass("active");
            $(e).addClass("active");
        }

        $("input[name='usulan_peraturan']").val(id_peraturan);
        $("a[href$='#disetujui'], a[href$='#tidakDisetujui']").hide();

        check_disetujui_tidak_disetujui_kasubbag();
        get_last_file();

        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_detail_peraturan',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
                $(".list-activites").html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted"></i></div>');
            },
            success: function(response) {
                let html = "";

                // BANNER SEDANG DIPROSES — VERSI KASUBBAG (KUNING MENYALA + GLOW!)
                if (response.length > 0 && response[0].processing_status) {
                    let oleh = response[0].processing_by_name || 'JFT';
                    let sejak = response[0].processing_date || 'Tanggal tidak diketahui';

                    html += `
                    <div class="alert banner-processing-kasubbag mb-4 animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center">
                            <div class="mr-4">
                                <i class="fas fa-cog fa-spin fa-3x text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-1 text-warning font-weight-bold">
                                    ${response[0].processing_status}
                                </h4>
                                <p class="mb-1" style="color: black;">
                                    <strong>Sedang diproses oleh:</strong> ${oleh}<br>
                                    <strong>Sejak:</strong> ${sejak}
                                </p>
                                <span class="badge badge-warning badge-pill px-4 py-2">
                                    MENUNGGU TINDAK LANJUT ANDA
                                </span>
                            </div>
                        </div>
                    </div>`;
                }

                // Aktivitas lainnya
                let firstRejected = -1;
                $.each(response, function(i, v) {
                    if (v.catatan_ditolak && firstRejected === -1) firstRejected = i;
                });

                $.each(response, function(i, v) {
                    html += `
                    <div class="activity animate__animated animate__fadeInUp" style="animation-delay:${i * 100}ms">
                        <div class="activity-icon ${v.class_color} text-white shadow-dark">
                            <i class="fas fa-user-alt"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary font-weight-bold">${v.tanggal_custom}</span>
                                ${v.file ? '<span class="bullet"></span>' + v.file : ''}
                            </div>
                            <p class="mb-2">${v.status_terakhir}</p>
                            ${v.catatan_ditolak && i === firstRejected ?
                            '<a class="btn btn-warning btn-sm" href="' + base_url + 'monitoring_raperbup/edit_usulan_raperbup/' + id_peraturan + '">Revisi Usulan</a>' : ''
                        }
                        </div>
                    </div>`;
                });

                $(".list-activites").html(html);
            },
            complete: function() {
                HoldOn.close();
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
                            url: base_url + 'monitoring_raperbup/setuju_ditolak_monitoring_raperbup',
                            data: {
                                id_peraturan: id_peraturan,
                                status: status
                            },
                            type: 'POST',
                            beforeSend: function() {
                                HoldOn.open(optionsHoldOn);
                            },
                            success: function(response) {
                                get_data_peraturan();
                                if (response) {
                                    $("input[name='usulan_peraturan']").val("");
                                    swal('Berhasil', 'Status berhasil diubah', 'success');
                                } else {
                                    swal('Gagal', 'Status tidak bisa diubah', 'error');
                                }
                            },
                            complete: function(response) {
                                HoldOn.close();
                            }
                        });
                    } else {
                        swal('Batal', 'Data masih tersimpan!', 'error').then(function(results) {
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
            url: base_url + 'monitoring_raperbup/request/check_disetujui_tidak_disetujui_kasubbag',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                if (response) {
                    $("a[href$='#disetujui']").show();
                    $("a[href$='#tidakDisetujui']").show();
                } else {
                    $("a[href$='#disetujui']").hide();
                    $("a[href$='#tidakDisetujui']").hide();
                }
            },
            complete: function() {
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
                beforeSend: function() {
                    HoldOn.open(optionsHoldOn);
                },
                success: function(response) {
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
                complete: function() {
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
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            get_data_peraturan();
                            if (response) {
                                $("input[name='usulan_peraturan']").val("");
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                swal('Gagal', 'Data tidak bisa dihapus', 'error');
                            }
                        },
                        complete: function(response) {
                            HoldOn.close();
                        }
                    });
                } else {
                    swal('Batal', 'Data masih tersimpan!', 'error').then(function(results) {
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
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                // console.log(response);
                let html = "<table>";
                html += "<tr><td>File Revisi</td><td style='padding:5px;'>:</td><td>" + response.usulan + "</td></tr>";
                if (response.lampiran_group) {
                    html += "<tr><td>Lampiran</td><td style='padding:5px;'>:</td><td>" + response.lampiran_group + "</td></tr>";
                }
                html += "</table>";
                $(".last_file").html(html);
            },
            complete: function(response) {
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