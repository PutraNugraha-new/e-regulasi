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
                                <a href="#" onclick="tandaiSemuaDibaca();" class="btn btn-primary">Tandai semua
                                    dibaca</a>
                            </div>
                        </div>

                        <!-- Tambah class custom-scroll di sini -->
                        <div class="card-body p-0">
                            <div class="notification-scroll">
                                <ul class="list-unstyled list-unstyled-border" id="notificationListDashboard">
                                    <li class="media text-center p-4">Memuat notifikasi...</li>
                                </ul>
                            </div>
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
                                <table id="datatableTemplateUsulan"
                                    class="table datatable-save-state table-bordered table-striped">
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
        /* Sesuaikan tinggi maksimal sesuai keinginan */
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0 16px 16px 16px;
        /* biar ada ruang di kanan supaya scroll bar gak nempel */
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

    /* Optional: biar pas kosong tetap rapi */
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

    /* Supaya pas kosong, gak ada border bawah */
    #notificationListDashboard .media.border-0 {
        border-bottom: none !important;
        padding: 20px 16px;
    }
</style>

<script>
    // Inisialisasi DataTable
    let datatableTemplateUsulan = $("#datatableTemplateUsulan").DataTable({
        "bLengthChange": false,
        "bFilter": false,
        "bPaginate": false,
    });

    $(document).ready(function () {
        // Cek apakah user Admin OPD (level 5)
        var is_admin_opd = <?= ($this->session->userdata('level_user_id') == 5) ? 'true' : 'false' ?>;

        // === FUNGSI LOAD NOTIFIKASI ===
        function loadNotifikasiDashboard() {
            $.ajax({
                url: base_url + 'dashboard/request/get_notifikasi',
                type: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
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

                            var usulan_id = item.id_usulan_raperbup || '';
                            var kategori_id = item.kategori_usulan_idular_id || '';
                            var link = '';

                            if (!item.nomor_register || item.nomor_register === '') {
                                link = base_url + 'nomor_register/index?usulan_id=' + usulan_id + '&kategori_usulan_id=' + kategori_id;
                            } else {
                                link = base_url + 'monitoring_raperbup/index?usulan_id=' + usulan_id + '&kategori_usulan_id=' + kategori_id;
                            }

                            html += `
                                <li class="media">
                                    <img class="mr-3 rounded-circle" width="50" src="${base_url}assets/img/avatar/avatar-1.png" alt="avatar">
                                    <div class="media-body">
                                        <div class="media-title">${item.nama_pengguna}</div>
                                        <span class="text-small text-muted">${item.pesan}</span>
                                        <div class="text-small text-muted">${formattedDate}</div>`;

                            if (!is_admin_opd) {
                                html += `
                                        <a href="${link}" 
                                           onclick="return tandaiDibaca(${item.id_notifikasi}, '${link}')" 
                                           class="btn btn-sm btn-primary mt-2">Lihat Detail</a>`;
                            } else {
                                html += `
                                        <button class="btn btn-sm btn-secondary mt-2" disabled>
                                            Lihat Detail (Tidak Tersedia)
                                        </button>
                                        <small class="text-muted d-block mt-1">Fitur hanya untuk Admin Pusat</small>`;
                            }

                            html += `
                                    </div>
                                </li>`;
                        });
                    } else {
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
                error: function () {
                    $('#notificationListDashboard').html('<li class="media text-center text-danger">Gagal memuat notifikasi.</li>');
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }

        // === TANDAI DIBACA ===
        window.tandaiDibaca = function (id_notifikasi, link) {
            if (is_admin_opd) {
                swal('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.', 'warning');
                return false;
            }

            $.ajax({
                url: base_url + 'dashboard/request/tandai_dibaca',
                type: 'POST',
                data: { id_notifikasi: id_notifikasi },
                dataType: 'json',
                success: function (response) {
                    if (response === true || response === 1) {
                        loadNotifikasiDashboard(); // Refresh
                        window.location.href = link;
                    } else {
                        swal('Gagal', 'Gagal menandai notifikasi sebagai dibaca.', 'error');
                    }
                },
                error: function () {
                    swal('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
                }
            });
            return false;
        };

        // === TANDAI SEMUA ===
        function tandaiSemuaDibaca() {
            if (is_admin_opd) {
                swal('Tidak Diizinkan', 'Fitur ini hanya untuk Admin Pusat.', 'info');
                return;
            }

            $.ajax({
                url: base_url + 'dashboard/request/tandai_semua_dibaca',
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    HoldOn.open(optionsHoldOn);
                },
                success: function (response) {
                    if (response.status) {
                        swal('Berhasil', response.message, 'success');
                        loadNotifikasiDashboard();
                    } else {
                        swal('Gagal', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    swal('Error', 'Gagal menandai semua notifikasi: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }

        // === LOAD TEMPLATE USULAN ===
        function get_data_template_usulan() {
            datatableTemplateUsulan.clear().draw();
            $.ajax({
                url: base_url + 'template_usulan/request/get_data_template_usulan',
                type: 'GET',
                dataType: 'json',
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
                },
                error: function (xhr) {
                    swal('Error', 'Gagal memuat template usulan: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
                }
            });
        }

        // === JALANKAN SEMUA ===
        get_data_template_usulan();
        loadNotifikasiDashboard();
    });
</script>