<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pemberitahuan</h4>
                            <div class="card-header-action">
                                <a href="#" onclick="tandaiSemuaDibaca();" class="btn btn-primary">Tandai semua dibaca</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border" id="notificationListDashboard">
                                <li class="media text-center">Memuat notifikasi...</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Template Usulan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableTemplateUsulan" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Template</th>
                                            <th>File</th>
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
    let datatableTemplateUsulan = $("#datatableTemplateUsulan").DataTable({
        "bLengthChange": false,
        "bFilter": false,
        "bPaginate": false,
    });

    function get_data_template_usulan() {
        datatableTemplateUsulan.clear().draw();
        $.ajax({
            url: base_url + 'template_usulan/request/get_data_template_usulan',
            type: 'GET',
            beforeSend: function () {
                HoldOn.open(optionsHoldOn);
            },
            success: function (response) {
                $.each(response, function (index, value) {
                    datatableTemplateUsulan.row.add([
                        value.nama_template,
                        value.file
                    ]).draw(false);
                });
            },
            complete: function () {
                HoldOn.close();
            }
        });
    }

    $(document).ready(function () {
        function loadNotifikasiDashboard() {
            $.ajax({
                url: base_url + 'dashboard/request/get_notifikasi',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var html = '';
                    if (response.error) {
                        html = '<li class="media text-center text-danger">Gagal memuat notifikasi: ' + response.error + '</li>';
                    } else if (response.notifikasi.length > 0) {
                        $.each(response.notifikasi, function (index, item) {
                            var date = new Date(item.created_at);
                            var formattedDate = date.toLocaleDateString('id-ID', {
                                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                            });
                            html += `
                                <li class="media">
                                    <img class="mr-3 rounded-circle" width="50" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png" alt="avatar">
                                    <div class="media-body">
                                        <div class="media-title">${item.nama_pengguna}</div>
                                        <span class="text-small text-muted">${item.pesan}</span>
                                        <div class="text-small text-muted">${formattedDate}</div>
                                        <a href="${item.link}" onclick="tandaiDibaca(${item.id_notifikasi})" class="btn btn-sm btn-primary mt-2">Lihat Detail</a>
                                    </div>
                                </li>`;
                        });
                    } else {
                        html = '<li class="media text-center">Tidak ada pemberitahuan baru</li>';
                    }
                    $('#notificationListDashboard').html(html);
                },
                error: function (xhr, status, error) {
                    console.log('Error loading notifications:', xhr.responseText);
                    $('#notificationListDashboard').html('<li class="media text-center text-danger">Gagal memuat notifikasi: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error') + '</li>');
                }
            });
        }

        // Load template usulan dan notifikasi saat halaman dimuat
        get_data_template_usulan();
        loadNotifikasiDashboard();
    });
</script>