<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <!-- NOTIFIKASI -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pemberitahuan</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tandaiSemuaDibaca()">
                                    Tandai Semua Dibaca
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="notification-scroll">
                                <ul class="list-unstyled list-unstyled-border" id="notificationListDashboard">
                                    <li class="media text-center p-4">Memuat notifikasi...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TEMPLATE USULAN (KHUSUS ADMIN OPD) -->
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Template Usulan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableTemplateUsulan" class="table table-bordered table-striped">
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

<style>
    .notification-scroll {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0 16px 16px 16px;
    }

    .notification-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .notification-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .notification-scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .notification-scroll::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    #notificationListDashboard .media {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    #notificationListDashboard .media:last-child {
        border-bottom: none;
    }

    .empty-notification,
    #notificationListDashboard .media .mx-auto {
        color: #aab0bc !important;
    }

    #notificationListDashboard .media.border-0 {
        border-bottom: none !important;
        padding: 20px 16px;
    }
</style>

<script>
    $(document).ready(function () {
        const is_admin_opd = true; // Khusus Admin OPD

        // DataTable Template
        const datatableTemplateUsulan = $("#datatableTemplateUsulan").DataTable({
            "bLengthChange": false,
            "bFilter": false,
            "bPaginate": false,
        });

        function get_data_template_usulan() {
            datatableTemplateUsulan.clear().draw();
            $.ajax({
                url: base_url + 'template_usulan/request/get_data_template_usulan',
                type: 'GET',
                dataType: 'json',
                beforeSend: () => HoldOn.open(optionsHoldOn),
                success: res => {
                    $.each(res, (i, v) => {
                        datatableTemplateUsulan.row.add([v.nama_template, v.file]).draw(false);
                    });
                },
                complete: () => HoldOn.close()
            });
        }

        function loadNotifikasiDashboard() {
            $.ajax({
                url: base_url + 'dashboard/request/get_notifikasi',
                type: 'GET',
                dataType: 'json',
                beforeSend: () => HoldOn.open(optionsHoldOn),
                success: function (response) {
                    var html = '';
                    if (response.error) {
                        html = '<li class="media text-center text-danger">Gagal memuat notifikasi: ' + response.error + '</li>';
                    } else if (response.notifikasi && response.notifikasi.length > 0) {
                        $.each(response.notifikasi, function (index, item) {
                            var date = new Date(item.created_at);
                            var formattedDate = date.toLocaleDateString('id-ID', {
                                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                            });

                            html += `
                        <li class="media">
                            <img class="mr-3 rounded-circle" width="50" src="${base_url}assets/img/avatar/avatar-1.png" alt="avatar">
                            <div class="media-body">
                                <div class="media-title">${item.nama_pengguna || 'Sistem'}</div>
                                <span class="text-small text-muted">${item.pesan}</span>
                                <div class="text-small text-muted">${formattedDate}</div>
                                <button class="btn btn-sm btn-secondary mt-2" disabled>
                                    Lihat Detail (Tidak Tersedia)
                                </button>
                                <small class="text-muted d-block mt-1">Fitur hanya untuk Admin Pusat</small>
                            </div>
                        </li>`;
                        });
                    } else {
                        // TAMPILAN KOSONG YANG KAMU SUKA — 100% SAMA!
                        html = `
                    <li class="media border-0 py-5">
                        <div class="mx-auto text-center">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3 d-block"></i>
                            <div class="text-muted font-weight-bold">Tidak ada pemberitahuan baru</div>
                            <small class="text-muted">Semua sudah dibaca atau belum ada yang baru</small>
                        </div>
                    </li>`;
                    }
                    $('#notificationListDashboard').html(html);
                },
                complete: () => HoldOn.close()
            });
        }

        // INI YANG PENTING: ADMIN OPD BOLEH KLIK "Tandai Semua Dibaca"!
        window.tandaiSemuaDibaca = function () {
            const btn = $('.card-header-action .btn-primary');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            $.ajax({
                url: base_url + 'dashboard/request/tandai_semua_dibaca',
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        swal('Sukses!', res.message || 'Semua notifikasi telah ditandai dibaca!', 'success');
                        loadNotifikasiDashboard();
                    } else {
                        swal('Gagal', res.message || 'Gagal menandai semua', 'error');
                    }
                },
                error: () => swal('Error', 'Terjadi kesalahan server', 'error'),
                complete: () => btn.prop('disabled', false).html(originalText)
            });
        };

        // JALANKAN
        get_data_template_usulan();
        loadNotifikasiDashboard();
    });
</script>