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
                                    <select class="form-control select2" name="skpd" onchange="get_data_monitoring_kalteng()">
                                        <option value="">-- SEMUA SKPD --</option>
                                        <?php
                                        foreach ($skpd as $key => $value) {
                                        ?>
                                            <option value="<?php echo $value->id_master_satker; ?>"><?php echo $value->nama; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row list-peraturan">
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

<div id="showPanelDitolak" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Ditolak</h5>
                <input type="hidden" name="id_raperbup" />
                <input type="hidden" name="id_trx_raperbup" />
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
                        <input type="file" class="form-control" name="file_upload" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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
                <button type="submit" class="btn btn-primary" onclick="save_usulan_ditolak()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    get_data_monitoring_kalteng();

    function get_data_monitoring_kalteng() {
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_monitoring_kalteng',
            // data: {
            //     skpd: $("select[name='skpd']").val()
            // },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                let list_peraturan = "";
                if (response.length != 0) {
                    let pilihan = "";
                    $.each(response, function(index, value) {
                        if (value.asisten_agree_disagree != "") {
                            pilihan = "";
                        } else {
                            pilihan = "<div class='article-user'>" +
                                "<div class='form-group'>" +
                                "<div class='selectgroup w-100'>" +
                                "<label class='selectgroup-item'>" +
                                "<input type='radio' name='value' value='100' class='selectgroup-input' onclick=\"change_status('" + value.id_encrypt + "','" + value.id_encrypt_trx_raperbup + "','1')\" " + (value.provinsi_agree_disagree == '1' ? 'checked=""' : '') + ">" +
                                "<span class='selectgroup-button'>Meneruskan</span>" +
                                "</label>" +
                                "<label class='selectgroup-item'>" +
                                "<input type='radio' name='value' value='150' class='selectgroup-input' onclick=\"change_status('" + value.id_encrypt + "','" + value.id_encrypt_trx_raperbup + "','2')\" " + (value.provinsi_agree_disagree == '2' ? 'checked=""' : '') + ">" +
                                "<span class='selectgroup-button'>Menolak</span>" +
                                "</label>" +
                                "</div>" +
                                "</div>" +
                                "</div>";
                        }
                        list_peraturan += "<div class='col-12 col-sm-6 col-md-6 col-lg-4'>" +
                            "<article class='article article-style-b'>" +
                            "<div class='article-header'>" +
                            "<div class='article-image " + (value.provinsi_agree_disagree == '' ? 'bg-primary' : (value.provinsi_agree_disagree == '1' ? 'bg-success' : (value.provinsi_agree_disagree == '2' ? 'bg-warning' : ''))) + " text-center'>" +
                            "<i class='fa fa-book text-white' style='font-size:110px;margin-top:20px;'></i>" +
                            "</div>" +
                            "<div class='article-badge'>" +
                            "</div>" +
                            "</div>" +
                            "<div class='article-details'>" +
                            "<div class='article-title'>" +
                            "<h6>" + value.nama_peraturan + "</h6>" +
                            "</div>" +
                            pilihan +
                            "<div class='article-cta'>" +
                            value.file +
                            "</div>" +
                            "</div>" +
                            "</article>" +
                            "</div>";
                    });

                } else {
                    list_peraturan += "<div class='col-12'>" +
                        "<div class='card'>" +
                        "<div class='card-body'>" +
                        "Belum Ada Usulan" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                }
                $(".list-peraturan").html(list_peraturan);
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function save_usulan_ditolak() {
        let id_raperbup = $("input[name='id_raperbup']").val();
        let id_trx_raperbup = $("input[name='id_trx_raperbup']").val();
        let catatan = $("textarea[name='catatan']").val();
        let files = $("input[name='file_upload']")[0].files[0];

        if (!catatan && !files) {
            swal('Gagal', 'Catatan atau File Perbaikan Wajib Diisi', 'error');
        } else {
            let fd = new FormData();
            fd.append('id_raperbup', id_raperbup);
            fd.append('id_trx_raperbup', id_trx_raperbup);
            fd.append('catatan', catatan);
            fd.append('file_upload', files);
            $.ajax({
                url: base_url + 'monitoring_raperbup/usulan_ditolak_kalteng',
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                beforeSend: function() {
                    HoldOn.open(optionsHoldOn);
                },
                success: function(response) {
                    $("textarea[name='catatan']").val("");
                    $("input[name='file_upload']").val("");
                    $("input[name='id_raperbup']").val("");
                    $("input[name='id_trx_raperbup']").val("");
                    $("#showPanelDitolak").modal("toggle");
                    get_data_monitoring_kalteng();
                    if (response) {
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

    function change_status(id_raperbup, id_trx_raperbup, status) {
        let val_change = status;
        if (val_change == "2") {
            $("#showPanelDitolak").modal("show");
            $("input[name='id_raperbup']").val(id_raperbup);
            $("input[name='id_trx_raperbup']").val(id_trx_raperbup);
        } else {

            swal({
                    title: 'Apakah anda yakin merubah status usulan ini?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: base_url + 'monitoring_raperbup/change_status_pemeriksaan_provinsi',
                            data: {
                                id_raperbup: id_raperbup,
                                id_trx_raperbup: id_trx_raperbup,
                                status: val_change
                            },
                            type: 'POST',
                            beforeSend: function() {
                                HoldOn.open(optionsHoldOn);
                            },
                            success: function(response) {
                                if (response) {
                                    get_data_monitoring_kalteng();
                                    swal('Berhasil', 'Status berhasil diubah', 'success');
                                } else {
                                    get_data_monitoring_kalteng();
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
                            get_data_monitoring_kalteng();
                        });
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