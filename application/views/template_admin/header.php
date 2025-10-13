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
	<script src="<?php echo base_url(); ?>assets/js/popper.min.js">
	</script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js">
	</script>
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
	<script>
		// Fungsi untuk menandai semua pesan telah dibaca
		function tandaiSemuaDibaca() {
			// Kirim permintaan AJAX untuk mengupdate status pesan
			$.ajax({
				url: base_url + 'monitoring_raperbup/request/tandaiSemuaDibaca', // Gantilah dengan URL yang sesuai
				method: "POST",
				data: {
					status_pesan: 2
				},
				success: function(response) {
					// Tampilkan pesan atau lakukan tindakan lain jika diperlukan
					alert(response);
					// Refresh halaman (opsional)
					location.reload();
				},
				error: function(error) {
					console.log(error);
				}
			});
		}
	</script>

</head>

<body>
	<div id="app">
		<div class="main-wrapper">
			<div class="navbar-bg"></div>
			<nav class="navbar navbar-expand-lg main-navbar">
				<ul class="navbar-nav mr-auto">
					<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
					<!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg "><i class="fas fa-search"></i></a></li> -->
				</ul>
				<ul class="navbar-nav navbar-right">
					<?php
					$level_user_id = $this->session->userdata('level_user_id');
					?>
					<?php if ($level_user_id == 6): ?>

						<?php
						$query = $this->db->query("
	SELECT DISTINCT
    trx_raperbup.usulan_raperbup_id,
    usulan_raperbup.id_user_created,
    user.nama_lengkap AS nama_pengguna,
    usulan_raperbup.nama_peraturan,
    trx_raperbup.level_user_id_status,
    trx_raperbup.status_tracking,
    trx_raperbup.kasubbag_agree_disagree,
    trx_raperbup.status_pesan,
    trx_raperbup.created_at
FROM trx_raperbup
LEFT JOIN usulan_raperbup ON trx_raperbup.usulan_raperbup_id = usulan_raperbup.id_usulan_raperbup
LEFT JOIN user ON usulan_raperbup.id_user_created = user.id_user
WHERE trx_raperbup.kasubbag_agree_disagree = 1
    AND trx_raperbup.level_user_id_status = 7
    AND trx_raperbup.status_pesan = 1
        ");
						$result = $query->result();
						$totalRows = $query->num_rows();

						?>
						<li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"><i class="far fa-bell"></i>
								<span class="badge badge-danger" id="notificationCount"><?php echo $totalRows; ?></span>
							</a>
							<div class="dropdown-menu dropdown-list dropdown-menu-right">
								<div class="dropdown-header">Pemberitahuan
									<div class="float-right">
										<a href="#" onclick="tandaiSemuaDibaca();">Tandai semua telah di baca</a>
									</div>
								</div>
								<div class="dropdown-list-content dropdown-list-icons">


									<?php foreach ($result as $row): ?>
										<a href="#" class="dropdown-item">
											<div class="dropdown-item-icon bg-info text-white">
												<i class="fas fa-bell"></i>
											</div>
											<div class="dropdown-item-desc">
												<b><?php echo $row->nama_pengguna; ?></b><br>
												<i><?php echo $row->nama_peraturan; ?></i>
												<div class="time">
													<?php
													setlocale(LC_TIME, 'id_ID');
													echo strftime('%A, %d %B %Y', strtotime($row->created_at));
													?>
												</div>
											</div>
										</a>
									<?php endforeach; ?>

									<div class="dropdown-footer text-center">
										<!-- <a href="#">View All <i class="fas fa-chevron-right"></i></a> -->
									</div>
								</div>
						</li>
					<?php endif; ?>
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