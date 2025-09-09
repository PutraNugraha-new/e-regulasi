<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableprivilegeLevel" class="table datatable-save-state">
                                    <thead>
                                        <tr>
                                            <th>Level</th>
                                            <th>Menu</th>
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
    let datatableprivilegeLevel = $("#datatableprivilegeLevel").DataTable();
    get_data_privilege_level();

    function get_data_privilege_level() {
        datatableprivilegeLevel.clear().draw();
        $.ajax({
            url: base_url + 'privilege_level/request/get_data_privilege_level',
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableprivilegeLevel.row.add([
                        value.nama_level_user,
                        value.nama_menu,
                        "<a href='" + base_url + "privilege_level/set_privilege_menu/" + value.id_encrypt + "' class='btn btn-primary btn-icon'><i class='ion ion-edit'></i></a>"
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }
</script>