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
                data: {
                    id_peraturan: id_peraturan
                },
                type: 'GET',
                beforeSend: function() {
                    HoldOn.open(optionsHoldOn);
                },
                success: function(response) {
                    let html = "";
                    let firstRejectedIndex = -1;

                    // Cari index catatan_ditolak pertama
                    $.each(response, function(index, value) {
                        if (value.catatan_ditolak && firstRejectedIndex === -1) {
                            firstRejectedIndex = index;
                        }
                    });

                    $.each(response, function(index, value) {
                        html += "<div class='activity'>" +
                            "<div class='activity-icon " + value.class_color + " text-white shadow-dark'>" +
                            "<i class='fas fa-user-alt'></i>" +
                            "</div>" +
                            "<div class='activity-detail'>" +
                            "<div class='mb-2'>" +
                            "<span class='text-job'>" + value.tanggal_custom + "</span>" +
                            (value.file ? "<span class='bullet'></span>" + value.file : "") +
                            "<div class='ml-4 float-right dropdown'>" +
                            value.action_delete +
                            "</div>" +
                            "</div>" +
                            "<p>" + value.status_terakhir + "</p>";

                        // Tampilkan tombol HANYA pada index catatan_ditolak pertama
                        if (value.catatan_ditolak && index === firstRejectedIndex) {
                            html += "<a class='btn btn-warning btn-sm' href='" + base_url + "monitoring_raperbup/edit_usulan_raperbup/" + id_peraturan + "'>Revisi</a>";
                            // html += "<a href='#' onclick=\"change_status('1')\" class='btn btn-info ml-2'>Teruskan ke Kabag</a>";
                        }

                        html += "</div>" +
                            "</div>";
                    });

                    $(".list-activites").html(html);
                },
                complete: function() {
                    HoldOn.close();
                }
            });
        }
    }

    var selectedUsulanId = '<?php echo isset($selected_usulan_id) ? $selected_usulan_id : ''; ?>';
    var selectedSkpdId = '<?php echo isset($selected_skpd_id) ? $selected_skpd_id : ''; ?>';

    $(document).ready(function() {
        // Set filter SKPD
        if (selectedSkpdId) {
            $("select[name='skpd']").val(selectedSkpdId);
        }
        // Set filter (default to 'belum' if not specified)
        $("input[name='filter'][value='belum']").prop('checked', true);
        get_data_peraturan();
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
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                let id_encrypt = "";
                let list_peraturan = "";
                let selectedIdEncrypt = null;
                if (response.length != 0) {
                    $.each(response, function(index, value) {
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
            complete: function() {
                HoldOn.close();
            },
            error: function(xhr, status, error) {
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
            url: base_url + 'monitoring_raperbup/request/check_disetujui_tidak_disetujui_jft',
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
                let html = "<table>";
                html += "<tr><td>File Usulan</td><td style='padding:5px;'>:</td><td>" + response.usulan + "</td></tr>";
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