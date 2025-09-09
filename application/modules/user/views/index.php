<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Level: </label>
                                        <select class="form-control select-search" name="level_search" onChange="get_data_user()">
                                            <option value="">-- PILIH --</option>
                                            <?php
                                            foreach ($list_level_user as $key => $row) {
                                                $selected = "";
                                                if ($selected_level == $row->id_level_user) {
                                                    $selected = 'selected="selected"';
                                                }
                                            ?>
                                                <option <?php echo $selected; ?> value="<?php echo encrypt_data($row->id_level_user); ?>"><?php echo $row->nama_level_user; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>&nbsp;</h4>
                            <div class="card-header-action">
                                <a href="<?php echo base_url() . 'user/tambah_user'; ?>" class="btn btn-info">Tambah User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableUser" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <th>Username</th>
                                            <th>Level</th>
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
    let datatableUser = $("#datatableUser").DataTable();

    function get_data_user() {

        let level = $("select[name=level_search]").val();
        datatableUser.clear().draw();
        if (level) {
            $.ajax({
                url: base_url + 'user/request/get_data_user',
                data: {
                    level: level
                },
                type: 'GET',
                beforeSend: function() {
                    HoldOn.open(optionsHoldOn);
                },
                success: function(response) {
                    $.each(response, function(index, value) {
                        datatableUser.row.add([
                            value.nama_lengkap,
                            value.username,
                            value.nama_level_user,
                            "<a href='" + base_url + "user/edit_user/" + value.id_encrypt + "' class='btn btn-primary btn-icon'><i class='ion ion-edit'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('" + value.id_encrypt + "')\" href='#'><i class='ion ion-trash-a'></i></a>"
                        ]).draw(false);
                    });
                },
                complete: function() {
                    HoldOn.close();
                }
            });
        }
    }

    function confirm_delete(id_user) {

        swal({
                title: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: base_url + 'user/delete_user',
                        data: {
                            id_user: id_user
                        },
                        type: 'GET',
                        beforeSend: function() {
                            HoldOn.open(optionsHoldOn);
                        },
                        success: function(response) {
                            if (response) {
                                get_data_user();
                                swal('Berhasil', 'Data berhasil dihapus', 'success');
                            } else {
                                get_data_user();
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