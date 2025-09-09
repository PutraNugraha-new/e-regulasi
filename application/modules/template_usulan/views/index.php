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
                                <a href="<?php echo base_url() . 'template_usulan/tambah_template_usulan'; ?>" class="btn btn-info">Tambah Template Usulan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableTemplateUsulan" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Template</th>
                                            <th>File</th>
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

<script>
    let datatableTemplateUsulan = $("#datatableTemplateUsulan").DataTable();
    get_data_template_usulan();

    function get_data_template_usulan() {
        datatableTemplateUsulan.clear().draw();
        $.ajax({
            url: base_url + 'template_usulan/request/get_data_template_usulan',
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableTemplateUsulan.row.add([
                        value.nama_template,
                        value.file,
                        "<a href='" + base_url + "template_usulan/edit_template_usulan/" + value.id_encrypt + "' class='btn btn-primary btn-icon'><i class='ion ion-edit'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('" + value.id_encrypt + "')\" href='#'><i class='ion ion-trash-a'></i></a>"
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function confirm_delete(id_template_usulan) {
        swal({
                title: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'template_usulan/delete_template_usulan',
                        data: {
                            id_template_usulan: id_template_usulan
                        },
                        type: 'GET',
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            if (response) {
                                get_data_template_usulan();
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                get_data_template_usulan();
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
                            get_data_template_usulan();
                        }
                    });
                }
            });
    }
</script>