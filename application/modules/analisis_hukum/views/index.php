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
                                <a href="<?php echo base_url() . 'analisis_hukum/tambah_analisis_hukum'; ?>" class="btn btn-info">Tambah Analisis Hukum</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableAnalisisHukum" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>File</th>
                                            <th>Tag</th>
                                            <th>Link</th>
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
    let datatableAnalisisHukum = $("#datatableAnalisisHukum").DataTable({
        "columns": [{
                "width": "20%"
            },
            {
                "width": "10%"
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
        ]
    });
    get_data_analisis_hukum();

    function get_data_analisis_hukum() {
        datatableAnalisisHukum.clear().draw();
        $.ajax({
            url: base_url + 'analisis_hukum/request/get_data_analisis_hukum',
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableAnalisisHukum.row.add([
                        value.judul,
                        value.file_analisis_hukum,
                        value.taging,
                        value.external_link,
                        "<a href='" + base_url + "analisis_hukum/edit_analisis_hukum/" + value.id_encrypt + "' class='btn btn-primary btn-icon icon-left m-2'><i class='ion ion-edit'></i> Edit</a> <a class='btn btn-danger btn-icon icon-left m-2' onClick=\"confirm_delete('" + value.id_encrypt + "')\" href='#'><i class='ion ion-trash-a'></i> Delete</a>"
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function confirm_delete(id_analisis_hukum) {
        swal({
                title: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'analisis_hukum/delete_analisis_hukum',
                        data: {
                            id_analisis_hukum: id_analisis_hukum
                        },
                        type: 'GET',
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            if (response) {
                                get_data_analisis_hukum();
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                get_data_analisis_hukum();
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
                            get_data_analisis_hukum();
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