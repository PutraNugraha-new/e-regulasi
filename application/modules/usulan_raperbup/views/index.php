<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <?php
        if (!empty($this->session->flashdata('message'))) {
            echo "<div class='alert " . ($this->session->flashdata('type-alert') == 'success' ? 'alert-success' : 'alert-danger') . " alert-dismissible show fade'>
            <div class='alert-body'>
              <button class='close' data-dismiss='alert'>
                <span>×</span>
              </button>
              " . $this->session->flashdata('message') . "
            </div>
          </div>";
        }
        ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>&nbsp;</h4>
                            <div class="card-header-action">
                                <a href="<?php echo base_url() . 'usulan_raperbup/tambah_usulan_raperbup'; ?>" class="btn btn-info">Tambah Usulan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableUsulanRaperbup" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Nama Peraturan</th>
                                            <th>Lampiran</th>
                                            <th>File Usulan</th>
                                            <th>File Final</th>
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

<script>
    let datatableUsulanRaperbup = $("#datatableUsulanRaperbup").DataTable({
        "columns": [{
                "width": "20%"
            },
            null,
            {
                "width": "10%"
            },
            {
                "width": "10%"
            },
            {
                "width": "10%"
            },
            null,
            {
                "width": "15%"
            },
        ]
    });
    get_data_usulan_raperbup();

    function get_data_usulan_raperbup() {
        datatableUsulanRaperbup.clear().draw();
        $.ajax({
            url: base_url + 'usulan_raperbup/request/get_data_usulan_raperbup',
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                console.log(response);
                $.each(response, function(index, value) {
                    datatableUsulanRaperbup.row.add([
                        value.nama_kategori,
                        value.nama_peraturan,
                        value.lampiran_group,
                        value.file_usulan,
                        value.file,
                        value.status_terakhir,
                        "<a href='" + base_url + "usulan_raperbup/edit_usulan_raperbup/" + value.id_encrypt + "' class='btn btn-primary btn-icon icon-left m-2'><i class='ion ion-edit'></i> Edit</a> <a class='btn btn-danger btn-icon icon-left m-2' onClick=\"confirm_delete('" + value.id_encrypt + "')\" href='#'><i class='ion ion-trash-a'></i> Delete</a> <a class='btn btn-warning btn-icon icon-left m-2' href='" + base_url + "usulan_raperbup/detail_usulan_raperbup/" + value.id_encrypt + "'><i class='ion ion-eye'></i> History & Upload Perbaikan</a>"
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function confirm_delete(id_usulan_raperbup) {
        swal({
                title: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'usulan_raperbup/delete_usulan_raperbup',
                        data: {
                            id_usulan_raperbup: id_usulan_raperbup
                        },
                        type: 'GET',
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            if (response) {
                                get_data_usulan_raperbup();
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                get_data_usulan_raperbup();
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
                            get_data_usulan_raperbup();
                        }
                    });
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