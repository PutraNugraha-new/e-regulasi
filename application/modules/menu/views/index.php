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
                                <a href="<?php echo base_url(); ?>menu/tambah_menu" class="btn btn-info">Tambah <i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableLevelMenu" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Menu</th>
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
    let datatableLevelMenu = $("#datatableLevelMenu").DataTable({
        "ordering": false
    });
    get_data_menu();

    function get_data_menu() {
        datatableLevelMenu.clear().draw();
        $.ajax({
            url: base_url + 'menu/request/get_data_menu',
            type: 'GET',
            // beforeSend: function() {
            //     HoldOn.open(optionsHoldOn);
            // },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableLevelMenu.row.add([
                        value.nama_menu,
                        "<a href='" + base_url + "menu/edit_menu/" + value.id_encrypt + "' class='btn btn-primary btn-icon'><i class='ion ion-edit'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('" + value.id_encrypt + "')\" href='#'><i class='ion ion-trash-a'></i></a>"
                    ]).draw(false);
                });
            },
            // complete: function(response) {
            //     HoldOn.close();
            // }
        });
    }

    function confirm_delete(id_menu) {
        swal({
                title: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'menu/delete_menu',
                        data: {
                            id_menu: id_menu
                        },
                        type: 'GET',
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            if (response) {
                                get_data_menu();
                                swal('Gagal', 'Data berhasil dihapus', 'success');
                            } else {
                                get_data_menu();
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
                            get_data_menu();
                        }
                    });
                }
            });
    }
</script>