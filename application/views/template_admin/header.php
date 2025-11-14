<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
	<title>Aplikasi Penyusunan Produk Hukum Daerah</title>

	<link rel="icon" href="<?php echo base_url(); ?>assets/favicon.png" type="image/x-icon">

	<!-- General CSS Files -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	<!-- CSS Libraries -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/jqvmap/dist/jqvmap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/summernote/dist/summernote-bs4.css">
	<link rel="stylesheet"
		href="<?php echo base_url(); ?>assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/select2/dist/css/select2.min.css">
	<link rel="stylesheet"
		href="<?php echo base_url(); ?>assets/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/ionicons201/css/ionicons.min.css">
	<link rel="stylesheet"
		href="<?php echo base_url(); ?>assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/HoldOn.min.css">
	<link rel="stylesheet"
		href="<?php echo base_url(); ?>assets/node_modules/bootstrap-daterangepicker/daterangepicker.css">

	<!-- Template CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">

	<script>
		var base_url = "<?php echo base_url(); ?>";
		var is_admin_opd = <?= ($this->session->userdata('level_user_id') == 5) ? 'true' : 'false' ?>;
	</script>

	<!-- General JS Scripts -->
	<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/stisla.js"></script>

	<!-- JS Libraies -->
	<script src="<?php echo base_url(); ?>assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/chart.js/dist/Chart.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/summernote/dist/summernote-bs4.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/datatables/media/js/jquery.dataTables.min.js"></script>
	<script
		src="<?php echo base_url(); ?>assets/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/select2/dist/js/select2.full.min.js"></script>
	<script
		src="<?php echo base_url(); ?>assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/HoldOn.min.js"></script>

	<!-- Template JS File -->
	<script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</head>

<body>
	<div id="app">
		<div class="main-wrapper">
			<div class="navbar-bg"></div>
			<nav class="navbar navbar-expand-lg main-navbar">
				<ul class="navbar-nav mr-auto">
					<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
					</li>
				</ul>
				<ul class="navbar-nav navbar-right">
					<li class="dropdown dropdown-list-toggle">
						<a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"
							<?php if ($this->session->userdata('level_user_id') == 5): ?>
								title="Notifikasi hanya untuk monitoring internal"
							<?php endif; ?>>
							<i class="far fa-bell"></i>
							<span class="badge badge-danger" id="notificationCount">0</span>
						</a>
						<div class="dropdown-menu dropdown-list dropdown-menu-right">
							<div class="dropdown-header">Pemberitahuan
								<div class="float-right">
									<a href="javascript:void(0)" onclick="tandaiSemuaDibaca(); return false;">
            Tandai semua telah dibaca
        </a>
								</div>
							</div>
							<div class="dropdown-list-content dropdown-list-icons" id="notificationList">
								<div class="dropdown-item text-center">Memuat notifikasi...</div>
							</div>
							<div class="dropdown-footer text-center">
    <?php if ($this->session->userdata('level_user_id') != 5): ?>
        <a href="<?php echo base_url(); ?>dashboard/notifikasi">Lihat Semua <i class="fas fa-chevron-right"></i></a>
    <?php else: ?>
        <span class="text-muted">Hanya untuk monitoring</span>
    <?php endif; ?>
</div>
						</div>
					</li>
					<li class="dropdown"><a href="#" data-toggle="dropdown"
							class="nav-link dropdown-toggle nav-link-lg nav-link-user">
							<div class="d-sm-none d-lg-inline-block">
								<?php echo $this->session->userdata("nama_lengkap"); ?>
							</div>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="<?php echo base_url(); ?>Login/act_logout"
								class="dropdown-item has-icon text-danger">
								<i class="fas fa-sign-out-alt"></i> Logout
							</a>
						</div>
					</li>
				</ul>
			</nav>

			<script>
    $(document).ready(function () {
        function loadNotifikasi() {
            $.ajax({
                url: base_url + 'dashboard/request/get_notifikasi',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        $('#notificationList').html('<div class="dropdown-item text-center text-danger">Gagal memuat</div>');
                        $('#notificationCount').text('0');
                        return;
                    }

                    $('#notificationCount').text(response.total || 0);
                    var html = '';

                    if (response.notifikasi && response.notifikasi.length > 0) {
                        $.each(response.notifikasi, function (index, item) {
                            var date = new Date(item.created_at).toLocaleDateString('id-ID', {
                                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                            });

                            if (!is_admin_opd) {
                                // Admin Pusat & User Biasa → BISA KLIK & BUKA DETAIL
                                var link = item.nomor_register 
                                    ? `${base_url}monitoring_raperbup/index?usulan_id=${item.id_usulan_raperbup}&kategori_usulan_id=${item.kategori_usulan_id || ''}`
                                    : `${base_url}nomor_register/index?usulan_id=${item.id_usulan_raperbup}&kategori_usulan_id=${item.kategori_usulan_id || ''}`;

                                html += `
                                <a href="${link}" class="dropdown-item" onclick="return tandaiDibaca(${item.id_notifikasi}, '${link}')">
                                    <div class="dropdown-item-icon bg-info text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>${item.nama_pengguna || 'Sistem'}</b><br>
                                        <i>${item.pesan}</i>
                                        <div class="time">${date}</div>
                                    </div>
                                </a>`;
                            } else {
                                // Admin OPD → LIHAT DOANG, GAK BISA KLIK!
                                html += `
                                <div class="dropdown-item">
                                    <div class="dropdown-item-icon bg-secondary text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>${item.nama_pengguna || 'Sistem'}</b><br>
                                        <i>${item.pesan}</i>
                                        <div class="time">${date}</div>
                                        <small class="text-muted">Hanya monitoring</small>
                                    </div>
                                </div>`;
                            }
                        });
                    } else {
                        html = '<div class="dropdown-item text-center text-muted">Tidak ada pemberitahuan baru</div>';
                    }

                    $('#notificationList').html(html);
                },
                error: function () {
                    $('#notificationList').html('<div class="dropdown-item text-center text-danger">Gagal memuat</div>');
                    $('#notificationCount').text('0');
                }
            });
        }

        // Admin OPD GAK BISA KLIK DETAIL → LANGSUNG DIBLOKIR
        window.tandaiDibaca = function (id_notifikasi, link) {
            if (is_admin_opd) {
                swal('Akses Ditolak', 'Anda tidak diizinkan membuka detail usulan.', 'warning');
                return false;
            }

            $.post(base_url + 'dashboard/request/tandai_dibaca', { id_notifikasi: id_notifikasi }, function(res) {
                if (res === true || res === 1) {
                    loadNotifikasi();
                    window.location.href = link;
                }
            }).fail(() => swal('Error', 'Gagal menandai notifikasi', 'error'));

            return false;
        };

        // INI YANG KAMU MAU: ADMIN OPD BOLEH "Tandai Semua Dibaca" DI HEADER!
        window.tandaiSemuaDibaca = function () {
            // TIDAK ADA BLOKIR LAGI → SEMUA USER BOLEH!
            $.ajax({
                url: base_url + 'dashboard/request/tandai_semua_dibaca',
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        loadNotifikasi();
                        swal('Sukses!', res.message || 'Semua notifikasi ditandai dibaca!', 'success');
                    } else {
                        swal('Gagal', res.message || 'Gagal menandai semua', 'error');
                    }
                },
                error: () => swal('Error', 'Server error', 'error')
            });
        };

        // Load pertama & auto refresh tiap 10 detik
        loadNotifikasi();
        setInterval(loadNotifikasi, 10000);
    });
</script>
</body>

</html>