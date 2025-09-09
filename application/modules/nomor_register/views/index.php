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
                                    <select class="form-control select2" name="skpd" onchange="get_data_usulan_raperbup()">
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableUsulanRaperbup" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SKPD</th>
                                            <th>Nomor Register</th>
                                            <th>Nama Peraturan</th>
                                            <th>Lampiran</th>
                                            <th>File Usulan</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
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

<div id="showPanelNomorRegister" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nomor Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="get_data_usulan_raperbup()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Nomor Register</label>
                    <div class="col-lg-8">
                        <input type="hidden" name="id_usulan_raperbup" />
                        <input type="text" name="nomor_register" class="form-control" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Catatan</label>
                    <div class="col-lg-8">
                        <textarea style="height: 100px;" class="form-control" name="catatan" require></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Kasubbag</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="id_kasubbag" require></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_nomor_register()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="get_data_usulan_raperbup()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let datatableUsulanRaperbup = $("#datatableUsulanRaperbup").DataTable({
        "columns": [{
                "width": "20%"
            },
            {
                "width": "10%"
            },
            {
                "width": "35%"
            },
            {
                "width": "10%"
            },
            {
                "width": "10%"
            },
            {
                "width": "15%"
            },
            {
                "width": "5%"
            },
        ]
    });
    get_data_usulan_raperbup();

    function get_data_usulan_raperbup() {
        datatableUsulanRaperbup.clear().draw();
        $.ajax({
            url: base_url + 'nomor_register/request/get_data_usulan_raperbup',
            data: {
                skpd: $("select[name='skpd']").val()
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableUsulanRaperbup.row.add([
                        value.nama,
                        value.nomor_register,
                        value.nama_peraturan,
                        value.lampiran_group,
                        value.file,
                        value.status_terakhir,
                        "<a class='btn btn-danger btn-icon' onClick=\"show_panel_nomor_register('" + value.id_encrypt + "')\" href='#'><i class='ion ion-edit'></i></a>"
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function show_panel_nomor_register(id_usulan_raperbup) {
        $("#showPanelNomorRegister").modal("show");
        $("input[name='id_usulan_raperbup']").val(id_usulan_raperbup);
        $.ajax({
            url: base_url + 'nomor_register/request/get_data_usulan_raperbup_by_id',
            data: {
                id_usulan_raperbup: id_usulan_raperbup
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $("input[name='nomor_register']").val(response.usulan_raperbup.nomor_register);
                if (response.trx_raperbup) {
                    $("textarea[name='catatan']").val(response.trx_raperbup.catatan_ditolak);
                }
                get_data_kasubbag(response.usulan_raperbup.id_user_kasubbag);
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function save_nomor_register() {
        let nomor_register = $("input[name='nomor_register']").val();
        let catatan_disposisi = $("textarea[name='catatan']").val();
        let id_kasubbag = $("select[name='id_kasubbag']").val();

        if (!nomor_register) {
            swal('Gagal', 'Nomor Register Wajib Diisi', 'error');
        } else if (!catatan_disposisi) {
            swal('Gagal', 'Catatan disposisi Wajib Diisi', 'error');
        } else if (!id_kasubbag) {
            swal('Gagal', 'Kasubbag Wajib Diisi', 'error');
        } else {
            $.ajax({
                url: base_url + 'nomor_register/save_nomor_register',
                data: {
                    nomor_register: nomor_register,
                    id_usulan_raperbup: $("input[name='id_usulan_raperbup']").val(),
                    catatan_disposisi: catatan_disposisi,
                    id_kasubbag: id_kasubbag,
                },
                type: 'POST',
                beforeSend: function() {
                    HoldOn.open(optionsHoldOn);
                },
                success: function(response) {
                    get_data_usulan_raperbup();
                    $("#showPanelNomorRegister").modal("toggle");
                    $("input[name='id_usulan_raperbup']").val("");
                    $("input[name='nomor_register']").val("");
                    $("textarea[name='catatan']").val("");
                    if (response) {
                        swal('Berhasil', 'Data berhasil disimpan', 'success');
                    } else {
                        swal('Gagal', 'Data tidak bisa disimpan', 'error');
                    }
                },
                complete: function() {
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

    function get_data_kasubbag(id_selected) {
        $("select[name='id_kasubbag']").html("<option value=''>-- Pilih Kasubbag --</option>");
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_kasubbag',
            type: 'POST',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                let html = "<option value=''>-- Pilih Kasubbag --</option>";
                $.each(response, function(index, value) {
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
            complete: function(response) {
                HoldOn.close();
            }
        });
    }
</script>