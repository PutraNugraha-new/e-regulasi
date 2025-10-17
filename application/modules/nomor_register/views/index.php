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
                                    <select class="form-control select2" name="skpd"
                                        onchange="get_data_usulan_raperbup()">
                                        <option value="">-- SEMUA SKPD --</option>
                                        <?php foreach ($skpd as $key => $value) { ?>
                                            <option value="<?php echo $value->id_master_satker; ?>">
                                                <?php echo $value->nama; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Urutkan Berdasarkan</label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="sort_order"
                                        onchange="get_data_usulan_raperbup()">
                                        <option value="desc">Nomor Register Terbaru</option>
                                        <option value="asc">Nomor Register Terlama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Status</label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="status"
                                        onchange="get_data_usulan_raperbup()">
                                        <option value="">-- Semua Status --</option>
                                        <option value="1">Usulan Baru</option>
                                        <option value="2">Diteruskan ke Kasubbag</option>
                                        <option value="3">Koreksi Kasubbag</option>
                                        <option value="4">Perbaikan Perancang</option>
                                        <option value="5">Disetujui / Publish</option>
                                        <option value="6">Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Tahun</label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="tahun"
                                        onchange="get_data_usulan_raperbup()">
                                        <option value="">-- Semua Tahun --</option>
                                        <?php foreach ($tahun as $thn) { ?>
                                            <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Jenis Surat</label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="tipe"
                                        onchange="get_data_usulan_raperbup()">
                                        <option value="">-- Semua Jenis --</option>
                                        <?php foreach ($kategori_usulan as $kat) { ?>
                                            <option value="<?php echo $kat->id_kategori_usulan; ?>">
                                                <?php echo $kat->nama_kategori; ?>
                                            </option>
                                        <?php } ?>
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
                                <table id="datatableUsulanRaperbup"
                                    class="table datatable-save-state table-bordered table-striped">
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="get_data_usulan_raperbup()">
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
                        <textarea style="height: 100px;" class="form-control" name="catatan" required></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Kasubbag</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="id_kasubbag" required></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_nomor_register()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="get_data_usulan_raperbup()">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="showPanelCancelUsulan" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Usulan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="get_data_usulan_raperbup()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Catatan Pembatalan</label>
                    <div class="col-lg-8">
                        <input type="hidden" name="id_usulan_raperbup_cancel" />
                        <textarea style="height: 100px;" class="form-control" name="catatan_pembatalan"
                            required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" onclick="cancel_usulan()">Batalkan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="get_data_usulan_raperbup()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const level_user = <?php echo $level_user ? $level_user : 0; ?>;
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
        ],
        "order": [
            [1, 'desc']
        ]
    });
    get_data_usulan_raperbup();

    function get_data_usulan_raperbup() {
        datatableUsulanRaperbup.clear().draw();
        $.ajax({
            url: base_url + 'nomor_register/request/get_data_usulan_raperbup',
            data: {
                skpd: $("select[name='skpd']").val(),
                sort_order: $("select[name='sort_order']").val(),
                status: $("select[name='status']").val(),
                tahun: $("select[name='tahun']").val(),
                tipe: $("select[name='tipe']").val()
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                console.log('Response:', response); // Debug response
                if (response.length === 0) {
                    swal('Info', 'Tidak ada data yang sesuai dengan filter', 'info');
                }
                $.each(response, function(index, value) {
                    let actions = "<a class='btn btn-danger btn-icon' onClick=\"show_panel_nomor_register('" + value.id_encrypt + "')\" href='#'>Teruskan</a>";
                    if (level_user !== 6) {
                        actions += "<a class='btn btn-info btn-icon my-2' href='" + base_url + "Nomor_register/edit_usulan_raperbup/" + value.id_encrypt + "'>Revisi</a>";
                    }
                    if (value.status_terakhir.indexOf('Dibatalkan') === -1 && value.status_terakhir.indexOf('Sudah Di Publish') === -1) {
                        actions += "<a class='btn btn-warning btn-icon mt-2' onClick=\"show_panel_cancel_usulan('" + value.id_encrypt + "')\" href='#'>Batalkan</a>";
                    }
                    datatableUsulanRaperbup.row.add([
                        value.nama || '-',
                        value.nomor_register || '-',
                        value.nama_peraturan || '-',
                        value.lampiran_group || '-',
                        value.file || '-',
                        value.status_terakhir || '-',
                        actions
                    ]).draw(false);
                });
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText); // Debug error
                swal('Error', 'Gagal mengambil data: ' + error, 'error');
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

    function show_panel_cancel_usulan(id_usulan_raperbup) {
        $("#showPanelCancelUsulan").modal("show");
        $("input[name='id_usulan_raperbup_cancel']").val(id_usulan_raperbup);
        $("textarea[name='catatan_pembatalan']").val("");
    }

    function cancel_usulan() {
        let catatan_pembatalan = $("textarea[name='catatan_pembatalan']").val();
        let id_usulan_raperbup = $("input[name='id_usulan_raperbup_cancel']").val();

        if (!catatan_pembatalan) {
            swal('Gagal', 'Catatan pembatalan wajib diisi', 'error');
            return;
        }

        $.ajax({
            url: base_url + 'nomor_register/cancel_usulan',
            data: {
                id_usulan_raperbup: id_usulan_raperbup,
                catatan_pembatalan: catatan_pembatalan
            },
            type: 'POST',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $("#showPanelCancelUsulan").modal("toggle");
                get_data_usulan_raperbup();
                if (response.status) {
                    swal('Berhasil', response.message, 'success');
                } else {
                    swal('Gagal', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                swal('Error', 'Gagal membatalkan usulan: ' + error, 'error');
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