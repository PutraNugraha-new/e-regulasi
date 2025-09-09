<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>&nbsp;</h4>
                            <div class="card-header-action">
                                <a href="<?php echo base_url(); ?>level_user/tambah_level_user" class="btn btn-info">Tambah Level User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableLevelUser" class="table datatable-save-state">
                                    <thead>
                                        <tr>
                                            <th>Nama Hak Akses</th>
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
    let datatableLevelUser = $("#datatableLevelUser").DataTable();
    get_data_level_user();

    function get_data_level_user() {
        datatableLevelUser.clear().draw();
        $.ajax({
            url: base_url + 'level_user/request/get_data_level_user',
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableLevelUser.row.add([
                        value.nama_level_user,
                        "<a href='" + base_url + "level_user/edit_level_user/" + value.id_encrypt + "' class='btn btn-primary btn-icon'><i class='ion ion-edit'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('" + value.id_encrypt + "')\" href='#'><i class='ion ion-trash-a'></i></a>"
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function confirm_delete(id_level_user) {

        swal({
                title: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'level_user/delete_level_user',
                        data: {
                            id_level_user: id_level_user
                        },
                        type: 'GET',
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            if (response) {
                                get_data_level_user();
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                get_data_level_user();
                                swal('Gagal', 'Data tidak bisa dihapus', 'error');
                            }
                        },
                        complete: function(response) {
                            HoldOn.close();
                        }
                    });
                } else {
                    swal('Batal', 'Data masih tersimpan!', 'success').then(function(results) {
                        HoldOn.close();
                        if (result.results) {
                            get_data_user();
                        }
                    });
                }
            });
    }
</script>