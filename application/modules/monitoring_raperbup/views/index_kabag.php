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
                                                <?php echo $value->nama; ?></option>
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
                        <div class="card-header d-block">
                            <h4><span class='last_file'></span></h4>
                            <div class="card-header-action text-right">
                                <a href="#publish" onclick="publish_for_skpd()" class="btn btn-info">Publish</a>
                                <a href="#disposisi" onclick="disposisi_monitoring_raperbup()"
                                    class="btn btn-info">Disposisi</a>
                                <a href="#disetujui" onclick="change_status_pemeriksaan_kasubag('1')"
                                    class="btn btn-info">Meneruskan</a>
                                <a href="#tidakDisetujui" onclick="change_status_ditolak('2')"
                                    class="btn btn-info">Menolak</a>
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

<div id="showPanelDisposisi" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Catatan</label>
                    <div class="col-lg-10">
                        <textarea style="height: 100px;" class="form-control" name="catatan" require></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Kasubbag</label>
                    <div class="col-lg-10">
                        <select class="form-control" name="id_kasubbag" require></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_disposisi()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="showPanelTidakSetuju" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Catatan</label>
                    <div class="col-lg-10">
                        <textarea style="height: 100px;" class="form-control" name="catatan_tidak_setuju"
                            require></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_tidak_setujui()">Simpan</button>
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

<div id="showPanelUploadFilePublish" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File Final</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control" name="file_final"
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
                <button type="submit" class="btn btn-primary" onclick="send_file_final()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("a[href$='#disposisi']").hide();
    $("a[href$='#disetujui']").hide();
    $("a[href$='#tidakDisetujui']").hide();
    $("a[href$='#publish']").hide();

    function get_data_peraturan() {
        $(".list-activites").html("");
        $(".list-peraturan").html("<li>Belum Ada Peraturan</li>");
        $("a[href$='#disposisi']").hide();
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $("a[href$='#publish']").hide();
        $(".last_file").html("");
        let skpd = $("select[name='skpd']").val();
        let filter = $("input[name='filter']:checked").val();

        if (skpd) {
            $.ajax({
                url: base_url + 'monitoring_raperbup/request/get_data_peraturan',
                data: {
                    skpd: skpd,
                    filter: filter,
                },
                type: 'GET',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    let list_peraturan = "";
                    if (response.length != 0) {
                        $.each(response, function (index, value) {
                            list_peraturan += "<li class='nav-item hr-bottom'><a href='#' class='nav-link list-peraturan-active' onclick=\"show_detail_peraturan('" + value.id_encrypt + "',this)\">" + value.nama_peraturan + "</a></li>"
                        });
                        $(".list-peraturan").html(list_peraturan);
                    }

                },
                complete: function () {
                    HoldOn.close();
                }
            });
        } else {
            $(".list-peraturan").html("<li>Belum Ada Peraturan</li>");
            $(".list-activites").html("");
        }
    }

    function show_detail_peraturan(id_peraturan, e) {
        if (e != undefined) {
            $(".list-peraturan-active").removeClass("active");
            $(e).addClass("active");
        }
        $("a[href$='#disposisi']").hide();
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $("a[href$='#publish']").hide();
        $("input[name='usulan_peraturan']").val("");
        if (id_peraturan) {
            $("input[name='usulan_peraturan']").val(id_peraturan);
            check_disposisi();
            check_disetujui_tidak_disetujui_kabag();
            check_publish();
            get_last_file();
            $.ajax({
                url: base_url + 'monitoring_raperbup/request/get_detail_peraturan',
                data: {
                    id_peraturan: id_peraturan
                },
                type: 'GET',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    let html = "";
                    $.each(response, function (index, value) {
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
                            "<p>" + value.status_terakhir + "</p>" +
                            "</div>" +
                            "</div>"
                    });

                    $(".list-activites").html(html);
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }
    }

    function disposisi_monitoring_raperbup() {
        $("#showPanelDisposisi").modal("show");
        get_data_kasubbag();
    }

    function get_data_kasubbag(id_selected) {
        $("select[name='id_kasubbag']").html("<option value=''>-- Pilih Kasubbag --</option>");
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_kasubbag',
            type: 'POST',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                let html = "<option value=''>-- Pilih Kasubbag --</option>";
                $.each(response, function (index, value) {
                    let selected = "";
                    if (id_selected) {
                        if (id_selected == value.id_user) {
                            selected = "selected";
                        }
                    }
                    html += "<option " + selected + " value='" + value.id_encrypt + "'>" + value.nama_lengkap + " (" + value.keterangan + ")</option>";
                });
                $("select[name='id_kasubbag']").html(html);
            },
            complete: function (response) {
                HoldOn.close();
            }
        });
    }

    function save_disposisi() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        let catatan_disposisi = $("textarea[name='catatan']").val();
        let id_kasubbag = $("select[name='id_kasubbag']").val();

        if (!catatan_disposisi) {
            swal('Gagal', 'Catatan Wajib Diisi', 'error');
        } else if (!id_kasubbag) {
            swal('Gagal', 'Kasubbag Wajib Dipilih', 'error');
        } else {
            $.ajax({
                url: base_url + 'monitoring_raperbup/disposisi_monitoring_raperbup',
                data: {
                    id_usulan_raperbup: id_peraturan,
                    catatan_disposisi: catatan_disposisi,
                    id_kasubbag: id_kasubbag
                },
                type: 'POST',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    show_detail_peraturan(id_peraturan);
                    $("#showPanelDisposisi").modal("toggle");
                    $("select[name='id_kasubbag']").html("");
                    $("textarea[name='catatan']").val("");
                    if (response) {
                        swal('Berhasil', 'Status berhasil diubah', 'success');
                    } else {
                        swal('Gagal', 'Status tidak bisa diubah', 'error');
                    }
                },
                complete: function (response) {
                    HoldOn.close();
                }
            });
        }
    }

    //jika setuju
    function change_status_pemeriksaan_kasubag(status) {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/check_raise_usulan',
            data: {
                id_usulan_raperbup: id_peraturan
            },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                let label = "";
                if (response.provinsi) {
                    label = "Meneruskan usulan ini akan diteruskan ke Provinsi";
                } else if (response.kabupaten) {
                    label = "Meneruskan usulan ini akan diteruskan ke Asisten Pemerintahan dan Kesra";
                } else {
                    label = "";
                }

                swal({
                    title: 'Apakah anda yakin menyetujui usulan ini? ' + label,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: base_url + 'monitoring_raperbup/change_status_pemeriksaan_kasubbag',
                                data: {
                                    id_usulan_raperbup: id_peraturan,
                                    status_disposisi: status
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
            },
            complete: function (response) {
                HoldOn.close();
            }
        });
    }

    //jika tidak setuju
    function change_status_ditolak(status) {
        $("#showPanelTidakSetuju").modal("show");
    }

    function save_tidak_setujui() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        let catatan = $("textarea[name='catatan_tidak_setuju']").val();

        if (!catatan) {
            swal('Gagal', 'Catatan Wajib Diisi', 'error');
        } else {
            $.ajax({
                url: base_url + 'monitoring_raperbup/change_status_pemeriksaan_kasubbag',
                data: {
                    id_usulan_raperbup: id_peraturan,
                    status_disposisi: 2,
                    catatan: catatan
                },
                type: 'POST',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    get_data_peraturan();
                    $("#showPanelTidakSetuju").modal("toggle");
                    $("textarea[name='catatan_tidak_setuju']").val("");
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
        }
    }

    function check_disposisi() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $("a[href$='#disposisi']").hide();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/check_disposisi',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                if (response) {
                    $("a[href$='#disposisi']").hide();
                } else {
                    $("a[href$='#disposisi']").show();
                }
            },
            complete: function () {
                HoldOn.close();
            }
        });
    }

    function check_disetujui_tidak_disetujui_kabag() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/check_disetujui_tidak_disetujui_kabag',
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

    function confirm_delete(id_trx_raperbup) {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
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
                            show_detail_peraturan(id_peraturan);
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
                html += "<tr><td>File Usulan</td><td style='padding:5px;'>:</td><td>" + response.usulan + "</td></tr>";
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

    function publish_for_skpd() {
        swal({
            title: 'Apakah anda yakin publish data ini?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $("#showPanelUploadFilePublish").modal("show");
                    $("input[name='file_final']").val("");
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

    function send_file_final() {
        let id_usulan = $("input[name='usulan_peraturan']").val();
        let files = $("input[name='file_final']")[0].files[0];
        if (!files) {
            swal('Gagal', 'File Wajib Diisi', 'error');
        } else {
            let fd = new FormData();
            fd.append('id_usulan_raperbup', id_usulan);
            fd.append('file_final', files);
            $.ajax({
                url: base_url + 'monitoring_raperbup/publish_for_skpd',
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    get_data_peraturan();
                    if (response) {
                        $("#showPanelUploadFilePublish").modal("toggle");
                        $("input[name='file_final']").val("");
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
        }
    }

    function check_publish() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/check_publish_up',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                if (response) {
                    $("a[href$='#publish']").show();
                } else {
                    $("a[href$='#publish']").hide();
                }
            },
            complete: function () {
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