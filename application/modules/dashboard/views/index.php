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
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border" id="notificationListDashboard">
                                <li class="media text-center">Memuat notifikasi...</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state" data-height="600">
                                <img height="300px"
                                    src="<?php echo base_url(); ?>assets/img/drawkit/drawkit-full-stack-man-colour.svg"
                                    alt="image">
                                <h2 class="mt-0">Dashboard Aplikasi Penyusunan Produk Hukum Daerah</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        var is_admin_opd = <?= ($this->session->userdata('level_user_id') == 5) ? 'true' : 'false' ?>;

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
                    } else if (response.notifikasi.length > 0) {
                        $.each(response.notifikasi, function (index, item) {
                            var date = new Date(item.created_at);
                            var formattedDate = date.toLocaleDateString('id-ID', {
                                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                            });

                            var usulan_id = item.id_usulan_raperbup || '';
                            var kategori_id = item.kategori_usulan_id || '';
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
                        html = '<li class="media text-center">Tidak ada pemberitahuan baru</li>';
                    }
                    $('#notificationListDashboard').html(html);
                },
                error: function (xhr) {
                    $('#notificationListDashboard').html('<li class="media text-center text-danger">Gagal memuat notifikasi.</li>');
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }

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
        }

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
                error: function (xhr, status, error) {
                    swal('Error', 'Gagal menandai semua notifikasi: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
                },
                complete: function () {
                    HoldOn.close();
                }
            });
        }

        loadNotifikasiDashboard();
    });
</script>