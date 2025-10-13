<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Peraturan :</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column list-peraturan">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h4><span class='last_file'></span></h4>
                            <div class="card-header-action">
                                <a href="#disposisi" onclick="disposisi_monitoring_raperbup()"
                                    class="btn btn-info">Disposisi</a>
                                <a href="#disetujui" onclick="change_status_pemeriksaan_kasubag('1')"
                                    class="btn btn-info">Disetujui</a>
                                <a href="#tidakDisetujui" onclick="change_status_ditolak('2')"
                                    class="btn btn-info">Tidak Disetujui</a>
                            </div>
                        </div>
                        <div class="card-body data_detail_peraturan">
                            <div class="table-responsive">
                                <table id="datatableUsulanRaperbup"
                                    class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Peraturan : </label>
                                        <select class="form-control select-search" name="usulan_peraturan" onChange="get_detail_peraturan()">
                                            <option value="">-- Pilih Peraturan --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4><span class='last_file'></span></h4>
                            <div class="card-header-action">
                                <a href="#disposisi" onclick="disposisi_monitoring_raperbup()" class="btn btn-info">Disposisi</a>
                                <a href="#disetujui" onclick="change_status_pemeriksaan_kasubag('1')" class="btn btn-info">Disetujui</a>
                                <a href="#tidakDisetujui" onclick="change_status_ditolak('2')" class="btn btn-info">Tidak Disetujui</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableUsulanRaperbup" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>
</div>

<div id="showPanelDisposisi" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="get_detail_peraturan()">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="get_detail_peraturan()">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="showPanelTidakSetuju" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="get_detail_peraturan()">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="get_detail_peraturan()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let datatableUsulanRaperbup = $("#datatableUsulanRaperbup").DataTable({
        "ordering": false,
        "columns": [{
            "width": "5%"
        },
        {
            "width": "35%"
        },
        {
            "width": "20%"
        },
        {
            "width": "10%"
        }
        ]
    });
    $("a[href$='#disposisi']").hide();
    $("a[href$='#disetujui']").hide();
    $("a[href$='#tidakDisetujui']").hide();

    get_data_peraturan();

    function get_data_peraturan() {
        $.ajax({
            url: base_url + 'monitoring_raperbup/request/get_data_peraturan',
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                let list_peraturan = "";
                if (response.length != 0) {
                    $.each(response, function (index, value) {
                        list_peraturan += "<li class='nav-item'><a href='#' class='nav-link list-peraturan-active' onclick=\"show_detail_peraturan('" + value.id_encrypt + "',this)\">" + value.nama_peraturan + "</a></li>"
                    });
                    $(".list-peraturan").html(list_peraturan);
                } else {
                    list_peraturan += "Data Belum Ada";
                    $(".list-peraturan").html(list_peraturan);
                }

            },
            complete: function () {
                HoldOn.close();
            }
        });
    }

    function show_detail_peraturan(id_peraturan, e) {
        $(".list-peraturan-active").removeClass("active");
        $(e).addClass("active");
        datatableUsulanRaperbup.clear().draw();
        $("a[href$='#disposisi']").hide();
        $("a[href$='#disetujui']").hide();
        $("a[href$='#tidakDisetujui']").hide();
        if (id_peraturan) {
            check_disposisi();
            check_disetujui_tidak_disetujui_kabag();
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
                    $.each(response, function (index, value) {
                        datatableUsulanRaperbup.row.add([
                            value.file,
                            value.status_terakhir,
                            value.tanggal_custom,
                            value.action_delete
                        ]).draw(false);
                    });
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
                    html += "<option " + selected + " value='" + value.id_encrypt + "'>" + value.nama_lengkap + "</option>";
                });
                $("select[name='id_kasubbag']").html(html);
            },
            complete: function (response) {
                HoldOn.close();
            }
        });
    }

    function save_disposisi() {
        let id_peraturan = $("select[name='usulan_peraturan']").val();
        let catatan_disposisi = $("textarea[name='catatan']").val();
        let id_kasubbag = $("select[name='id_kasubbag'").val();

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
                    get_detail_peraturan();
                    $("#showPanelDisposisi").modal("toggle");
                    $("select[name='id_kasubbag'").val("");
                    $("textarea[name='catatan']").html("");
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
        let id_peraturan = $("select[name='usulan_peraturan']").val();
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
                if (response) {
                    get_detail_peraturan();
                    swal('Berhasil', 'Status berhasil diubah', 'success');
                } else {
                    get_detail_peraturan();
                    swal('Gagal', 'Status tidak bisa diubah', 'error');
                }
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
        let id_peraturan = $("select[name='usulan_peraturan']").val();
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
                    get_detail_peraturan();
                    $("#showPanelTidakSetuju").modal("toggle");
                    $("textarea[name='catatan_tidak_setuju']").html("");
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
        let id_peraturan = $("select[name='usulan_peraturan']").val();
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
        let id_peraturan = $("select[name='usulan_peraturan']").val();
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
                                get_detail_peraturan();
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                get_detail_peraturan();
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
                            get_detail_peraturan();
                        }
                    });
                }
            });
    }

    function get_last_file() {
        let id_peraturan = $("select[name='usulan_peraturan']").val();
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
                $(".last_file").html("File : " + response);
            },
            complete: function (response) {
                HoldOn.close();
            }
        });
    }
</script>