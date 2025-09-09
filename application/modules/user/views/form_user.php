<style>
	.user-image-custom {
		margin-bottom: 10px;
	}
</style>
<div class="main-content">
	<section class="section">
		<?php echo $breadcrumb_main; ?>
		<div class="content">
			<!-- Form inputs -->
			<div class="card">
				<div class="card-body">
					<?php echo form_open_multipart(current_url(), array('class' => 'form-validate-jquery')); ?>
					<fieldset class="mb-3">
						<div class="form-group row">
							<label class="col-form-label col-lg-2">Nama Lengkap <span class="text-danger">*</span></label>
							<div class="col-lg-10">
								<input type="text" class="form-control" value="<?php echo !empty($content) ? $content->nama_lengkap : ""; ?>" name="nama_lengkap" required placeholder="Nama Lengkap">
								<input type="hidden" name="id_user" value="<?php echo !empty($content) ? encrypt_data($content->id_user) : ""; ?>" />
								<input type="hidden" name="id_level_user" value="<?php echo !empty($content) ? $content->level_user_id : ""; ?>" />
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label col-lg-2">Username <span class="text-danger">*</span></label>
							<div class="col-lg-10">
								<input type="text" class="form-control" value="<?php echo !empty($content) ? $content->username : ""; ?>" name="username" required placeholder="Username">
							</div>
						</div>


						<div class="form-group row">
							<label class="col-form-label col-lg-2">Password <?php echo !empty($content) ? '' : '<span class="text-danger">*</span>'; ?></label>
							<div class="col-lg-10">
								<input type="password" name="password" id="password" <?php echo !empty($content) ? '' : 'required'; ?> placeholder="Password" class="form-control">
								<?php echo empty($content) ? '' : '<span class="form-text text-muted">Jika ingin merubah password, silahkan diisi</span>'; ?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label col-lg-2">Level <span class="text-danger">*</span></label>
							<div class="col-lg-10">
								<select class="form-control select2" name="level_user" required onchange="cek_parent()">
									<option value="">-- PILIH LEVEL USER --</option>
									<?php
									foreach ($level_user as $key => $row) {
										$selected = "";
										if (!empty($content)) {
											if ($row->id_level_user == $content->level_user_id) {
												$selected = 'selected="selected"';
											}
										}
									?>
										<option <?php echo $selected; ?> value="<?php echo $row->id_level_user; ?>"><?php echo $row->nama_level_user; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>

						<div class="form-group row skpd-is-show">
							<label class="col-form-label col-lg-2">SKPD <span class="text-danger">*</span></label>
							<div class="col-lg-10">
								<select class="form-control select2" name="skpd">
									<option value="">-- PILIH SKPD --</option>
									<?php
									foreach ($master_satker as $key => $row) {
										$selected = "";
										if (!empty($content)) {
											if ($row->id_master_satker == $content->master_satker_id) {
												$selected = 'selected="selected"';
											}
										}
									?>
										<option <?php echo $selected; ?> value="<?php echo $row->id_master_satker; ?>"><?php echo $row->nama; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group row keterangan-is-show">
							<label class="col-form-label col-lg-2">Keterangan <span class="text-danger">*</span></label>
							<div class="col-lg-10">
								<input type="text" class="form-control" value="<?php echo !empty($content) ? $content->keterangan : ""; ?>" name="keterangan" required placeholder="Keterangan">
							</div>
						</div>
					</fieldset>

					<div class="text-right">
						<button type="submit" class="btn btn-primary">Simpan <i class="icon-paperplane ml-2"></i></button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<!-- /form inputs -->

		</div>
	</section>
</div>

<script>
	$(".skpd-is-show").hide();
	$("select[name='skpd']").attr("required", false);
	$(".keterangan-is-show").hide();
	$("input[name='keterangan']").attr("required", false);

	init();

	function init() {
		let id_level_user = $("input[name='id_level_user']").val();
		if (id_level_user == 5) {
			cek_skpd();
		}else if(id_level_user == 7){
			cek_kasubag();
		}
	}
	$("form").submit(function() {
		var swalInit = swal.mixin({
			buttonsStyling: false,
			confirmButtonClass: 'btn btn-primary',
			cancelButtonClass: 'btn btn-light'
		});

		let id_user = $("input[name='id_user']").val();
		let username = $("input[name='username']").val();
		let status = true;

		$.ajax({
			url: base_url + 'user/request/cek_username',
			async: false,
			data: {
				username: username,
				id_user: id_user
			},
			type: 'GET',
			beforeSend: function() {
				HoldOn.open(optionsHoldOn);
			},
			success: function(response) {
				// alert(response);
				status = response;
			},
			complete: function(response) {
				HoldOn.close();
			}
		});

		if (!status) {
			swalInit(
				'Gagal',
				'Username sudah digunakan',
				'error'
			);
			return false;
		}
	});

	function cek_parent(){
		let level_user = $("select[name='level_user']").val();

		if(level_user == 5){
			cek_skpd();
		}else if(level_user == 7){
			cek_kasubag();
		}
	}

	function cek_skpd() {
		$(".skpd-is-show").hide();
		$("select[name='skpd']").attr("required", false);
		$(".keterangan-is-show").hide();
		$("input[name='keterangan']").attr("required", false);
		let level_user = $("select[name='level_user']").val();

		if (level_user == 5) {
			$(".skpd-is-show").show();
			$("select[name='skpd']").attr("required", true);
		}
	}
	
	function cek_kasubag() {
		$(".skpd-is-show").hide();
		$("select[name='skpd']").attr("required", false);
		$(".keterangan-is-show").hide();
		$("input[name='keterangan']").attr("required", false);

		let level_user = $("select[name='level_user']").val();

		if (level_user == 7) {
			$(".keterangan-is-show").show();
			$("input[name='keterangan']").attr("required", true);
		}
	}
</script>