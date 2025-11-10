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
                                <label class="col-form-label col-lg-2">Kategori Usulan</label>
                                <div class="col-lg-10">
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="kategori_usulan" value="all"
                                                class="selectgroup-input" checked="" onclick="get_data_peraturan()">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> All</span>
                                        </label>
                                        <?php
                                        foreach ($kategori_usulan as $key => $value) {
                                            ?>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="kategori_usulan"
                                                    value="<?php echo encrypt_data($value->id_kategori_usulan); ?>"
                                                    class="selectgroup-input" onclick="get_data_peraturan()">
                                                <span class="selectgroup-button selectgroup-button-icon">
                                                    <?php echo $value->nama_kategori ?></span>
                                            </label>
                                            <?php
                                        }
                                        ?>
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
                                <a href="#kirimFileKeProvinsi" onclick="lampiran_provinsi()" class="btn btn-warning"><i
                                        class="ion ion-ios-paper"></i> Upload Lampiran Untuk Provinsi</a>
                                <a href="#disposisi" onclick="disposisi_monitoring_raperbup()"
                                    class="btn btn-info">Disposisi</a>
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

<div id="showPanelUploadLampiran" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control" name="lampiran_provinsi"
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
                <button type="submit" class="btn btn-primary" onclick="send_lampiran_provinsi()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("a[href$='#disposisi']").hide();
    $("a[href$='#kirimFileKeProvinsi']").hide();

    var selectedUsulanId = '<?php echo $this->input->get('usulan_id') ? $this->input->get('usulan_id') : ''; ?>';
    var selectedKategoriUsulan = '<?php echo $this->input->get('kategori_usulan_id') ? $this->input->get('kategori_usulan_id') : ''; ?>';

    $(document).ready(function () {
        // HANYA set filter jika ADA parameter dari URL (dari notifikasi)
        if (selectedUsulanId || selectedKategoriUsulan) {
            if (selectedKategoriUsulan) {
                $("input[name='kategori_usulan'][value='" + selectedKategoriUsulan + "']").prop('checked', true);
            }
            // JANGAN auto-load data peraturan!
            // Biarkan user klik filter manual
        }
    });

    // Fungsi ini dipanggil HANYA saat user ubah filter
    function get_data_peraturan() {
        let kategori_usulan = $("input[name='kategori_usulan']:checked").val();
        let skpd = $("select[name='skpd']").val();

        let data = { kategori_usulan: kategori_usulan, skpd: skpd };
        if (selectedUsulanId) {
            data.usulan_id = selectedUsulanId; // tetap kirim jika ada
        }

        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_peraturan',
            data: data,
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
                $(".list-peraturan").html("<li>Memuat data...</li>");
                $(".list-activites").html("");
                $(".last_file").html("");
                $("a[href$='#disposisi']").hide();
                $("a[href$='#kirimFileKeProvinsi']").hide();
            },
            success: function (response) {
                let list_peraturan = "";
                if (response.length > 0) {
                    $.each(response, function (index, value) {
                        let activeClass = (selectedUsulanId && value.id_usulan_raperbup == selectedUsulanId) ? 'active' : '';
                        list_peraturan += `<li class='nav-item hr-bottom'>
                            <a href='#' class='nav-link list-peraturan-active ${activeClass}' 
                               onclick="show_detail_peraturan('${value.id_encrypt}', this)">
                               ${value.nama_peraturan}
                            </a>
                        </li>`;
                    });

                    $(".list-peraturan").html(list_peraturan);

                    // Auto-show detail jika dari notifikasi
                    if (selectedUsulanId) {
                        let target = $(`.list-peraturan a[onclick*='${response.find(v => v.id_usulan_raperbup == selectedUsulanId)?.id_encrypt}']`);
                        if (target.length > 0) {
                            target.click();
                        }
                    }
                } else {
                    $(".list-peraturan").html("<li>Tidak ada data peraturan</li>");
                }
            },
            complete: function () {
                HoldOn.close();
            },
            error: function () {
                $(".list-peraturan").html("<li class='text-danger'>Gagal memuat data</li>");
                HoldOn.close();
            }
        });
    }

    // Hanya panggil saat user ubah filter
    $("select[name='skpd'], input[name='kategori_usulan']").on('change', function () {
        get_data_peraturan();
    });

    // Auto-load hanya jika dari notifikasi DAN kategori sudah dipilih
    $(document).ready(function () {
        if (selectedUsulanId && selectedKategoriUsulan) {
            get_data_peraturan();
        }
    });

    function show_detail_peraturan(id_peraturan, e) {
        if (e != undefined) {
            $(".list-peraturan-active").removeClass("active");
            $(e).addClass("active");
        }
        $("a[href$='#disposisi']").hide();
        $("a[href$='#kirimFileKeProvinsi']").hide();
        $("input[name='usulan_peraturan']").val("");
        if (id_peraturan) {
            $("input[name='usulan_peraturan']").val(id_peraturan);
            check_disposisi();
            check_kirim_file_ke_provinsi();
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
                    // Tampilkan processing_status untuk transaksi terbaru (index 0)
                    if (response.length > 0 && response[0].processing_status) {
                        html += "<div class='activity'>" +
                            "<div class='activity-icon bg-info text-white shadow-dark'>" +
                            "<i class='fas fa-clock'></i>" +
                            "</div>" +
                            "<div class='activity-detail'>" +
                            "<div class='mb-2'>" +
                            "<span class='text-job'>" + response[0].processing_date + "</span>" +
                            "</div>" +
                            "<p><div class='badge badge-info'>" + response[0].processing_status + "</div></p>" +
                            "</div>" +
                            "</div>";
                    }
                    // Tampilkan aktivitas lainnya
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
                            "</div>";
                    });

                    $(".list-activites").html(html);
                },
                complete: function () {
                    HoldOn.close();
                },
                error: function (xhr, status, error) {
                    console.log('Error loading detail:', xhr.responseText);
                    swal('Error', 'Gagal mengambil detail: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
                }
            });
        }
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
                            if (response) {
                                show_detail_peraturan(id_peraturan);
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                show_detail_peraturan(id_peraturan);
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
                if (response.lampiran) {
                    html += "<tr><td>Lampiran Surat Pengantar</td><td style='padding:5px;'>:</td><td>" + response.lampiran + "</td></tr>";
                }

                if (response.lampiran_sk_tim) {
                    html += "<tr><td>Lampiran SK Tim</td><td style='padding:5px;'>:</td><td>" + response.lampiran_sk_tim + "</td></tr>";
                }
                html += "</table>";
                $(".last_file").html(html);
            },
            complete: function (response) {
                HoldOn.close();
            }
        });
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

    function check_kirim_file_ke_provinsi() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        $("a[href$='#kirimFileKeProvinsi']").hide();
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/check_upload_lampiran_provinsi',
            data: {
                id_peraturan: id_peraturan
            },
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                if (response) {
                    $("a[href$='#kirimFileKeProvinsi']").show();
                } else {
                    $("a[href$='#kirimFileKeProvinsi']").hide();
                }
            },
            complete: function () {
                HoldOn.close();
            }
        });
    }

    function lampiran_provinsi() {
        $("#showPanelUploadLampiran").modal("show");
        $("input[name='lampiran_provinsi']").val("");
    }

    function send_lampiran_provinsi() {
        let id_peraturan = $("input[name='usulan_peraturan']").val();
        let files = $("input[name='lampiran_provinsi']")[0].files[0];

        if (!files) {
            swal('Gagal', 'File Wajib Diisi', 'error');
        } else {
            let fd = new FormData();
            fd.append('id_usulan_raperbup', id_peraturan);
            fd.append('lampiran_provinsi', files);
            $.ajax({
                url: base_url + 'monitoring_raperbup/usulan_provinsi',
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    $("#showPanelUploadLampiran").modal("toggle");
                    $("input[name='lampiran_provinsi']").val("");
                    show_detail_peraturan(id_peraturan);
                    if (response) {
                        swal('Berhasil', 'File berhasil diupload', 'success');
                    } else {
                        swal('Gagal', 'File gagal diupload', 'error');
                    }
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }
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