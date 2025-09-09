<!DOCTYPE html>
<html lang="en">
<head>
	<title>E-Regulation - Katingan</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/template_logins/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/template_logins/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/template_logins/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="assets/template_logins/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/template_logins/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/template_logins/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/template_logins/css/main.css">
<!--===============================================================================================-->

	<link rel="icon" href="assets/favicon.png" type="image/x-icon">
	<script>
		var base_url = "<?php echo base_url(); ?>";
	</script>

</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<span class="login100-form-title">
						Aplikasi Penyusunan Produk Hukum Daerah
					</span>
					<img src="assets/template_logins/images/justice-scale.png" alt="IMG">
				</div>

				<span class="login100-form">
					<span class="login100-form-title">
						Login
					</span>
					<span class='error_field'></span>
					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="username" placeholder="Username" name="username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password" name="password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" onclick="check_auth()">
							Login
						</button>
					</div>
				</span>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="assets/template_logins/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/template_logins/vendor/bootstrap/js/popper.js"></script>
	<script src="assets/template_logins/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/template_logins/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/template_logins/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="assets/template_logins/js/main.js"></script>

	<script>
		function check_auth() {
			let username = $("input[name='username']").val();
			let password = $("input[name='password']").val();
			$(".error_field").html("");

			if (!username) {
				$(".error_field").html("<div class='alert alert-danger'>Field username tidak boleh kosong</div>");
			} else if (!password) {
				$(".error_field").html("<div class='alert alert-danger'>Field Password tidak boleh kosong</div>");
			} else {
				$.ajax({
					url: base_url + 'Login/act_login',
					data: {
						username: username,
						password: password
					},
					type: 'POST',
					beforeSend: function() {
					},
					success: function(response) {
						if (response == true) {
							$(".error_field").html("<div class='alert alert-success'>Berhasil...</div>");
							location.reload();
						} else {
							$(".error_field").html("<div class='alert alert-danger'>Username dan Password salah</div>");
						}
					},
					complete: function() {
					}
				});
			}
		}

		$(function() {

			$('.login100-form').on('keypress', function(event) {
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if (keycode == '13') {
					check_auth();
				}
			});
		});
	</script>

</body>
</html>