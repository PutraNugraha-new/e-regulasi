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
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/ionicons201/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/HoldOn.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/node_modules/bootstrap-daterangepicker/daterangepicker.css">

	<!-- Template CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">

	<script>
		var base_url = "<?php echo base_url(); ?>";
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
	<script src="<?php echo base_url(); ?>assets/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/select2/dist/js/select2.full.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
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
					<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
				</ul>
				<ul class="navbar-nav navbar-right">
					<li class="dropdown dropdown-list-toggle">
						<a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
							<i class="far fa-bell"></i>
							<span class="badge badge-danger" id="notificationCount">0</span>
						</a>
						<div class="dropdown-menu dropdown-list dropdown-menu-right">
							<div class="dropdown-header">Pemberitahuan
								<div class="float-right">
									<a href="#" onclick="tandaiSemuaDibaca();">Tandai semua telah dibaca</a>
								</div>
							</div>
							<div class="dropdown-list-content dropdown-list-icons" id="notificationList">
								<div class="dropdown-item text-center">Memuat notifikasi...</div>
							</div>
							<div class="dropdown-footer text-center">
								<a href="<?php echo base_url(); ?>dashboard/notifikasi">Lihat Semua <i class="fas fa-chevron-right"></i></a>
							</div>
						</div>
					</li>
					<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
							<div class="d-sm-none d-lg-inline-block"><?php echo $this->session->userdata("nama_lengkap"); ?></div>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="<?php echo base_url(); ?>Login/act_logout" class="dropdown-item has-icon text-danger">
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
									$('#notificationList').html('<div class="dropdown-item text-center text-danger">Gagal memuat notifikasi: ' + response.error + '</div>');
									$('#notificationCount').text('0');
									return;
								}
								$('#notificationCount').text(response.total);
								var html = '';
								if (response.notifikasi.length > 0) {
									$.each(response.notifikasi, function (index, item) {
										var date = new Date(item.created_at);
										var formattedDate = date.toLocaleDateString('id-ID', {
											weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
										});
										html += `
											<a href="${item.link}" class="dropdown-item" onclick="tandaiDibaca(${item.id_notifikasi})">
												<div class="dropdown-item-icon bg-info text-white">
													<i class="fas fa-bell"></i>
												</div>
												<div class="dropdown-item-desc">
													<b>${item.nama_pengguna}</b><br>
													<i>${item.pesan}</i>
													<div class="time">${formattedDate}</div>
												</div>
											</a>`;
									});
								} else {
									html = '<div class="dropdown-item text-center">Tidak ada pemberitahuan baru</div>';
								}
								$('#notificationList').html(html);
							},
							error: function (xhr, status, error) {
								console.log('Error loading notifications:', xhr.responseText);
								$('#notificationList').html('<div class="dropdown-item text-center text-danger">Gagal memuat notifikasi: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error') + '</div>');
							}
						});
					}

					function tandaiDibaca(id_notifikasi) {
						$.ajax({
							url: base_url + 'dashboard/request/tandai_dibaca',
							type: 'POST',
							data: { id_notifikasi: id_notifikasi },
							dataType: 'json',
							success: function (response) {
								if (response) {
									loadNotifikasi(); // Refresh notifikasi
								}
							},
							error: function (xhr, status, error) {
								console.log('Error marking notification as read:', xhr.responseText);
								swal('Gagal', 'Gagal menandai notifikasi sebagai dibaca: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
							}
						});
					}

					function tandaiSemuaDibaca() {
						$.ajax({
							url: base_url + 'dashboard/request/tandai_dibaca',
							type: 'POST',
							dataType: 'json',
							success: function (response) {
								if (response) {
									loadNotifikasi(); // Refresh notifikasi
									swal('Berhasil', 'Semua notifikasi telah ditandai sebagai dibaca', 'success');
								}
							},
							error: function (xhr, status, error) {
								console.log('Error marking all notifications as read:', xhr.responseText);
								swal('Gagal', 'Gagal menandai semua notifikasi: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Server error'), 'error');
							}
						});
					}

					// Polling setiap 10 detik
					loadNotifikasi();
					setInterval(loadNotifikasi, 10000);

					// Expose tandaiSemuaDibaca to global scope
					window.tandaiSemuaDibaca = tandaiSemuaDibaca;
				});
			</script>
</body>
</html>