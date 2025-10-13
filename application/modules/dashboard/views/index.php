<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
            <?php
$level_user_id = $this->session->userdata('level_user_id');
?>
            <?php if ($level_user_id == 4): ?>
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Pemberitahuan Pengajuan Baru</h4>
                </div>
                <div class="card-body">             
                  <ul class="list-unstyled list-unstyled-border">
                  
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
    <?php foreach ($result as $row): ?>
                    <li class="media">
                      <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png" alt="avatar">
                      <div class="media-body">
                        <p><?php
							setlocale(LC_TIME, 'id_ID'); // Set lokal ke Bahasa Indonesia

							// Format tanggal menggunakan strftime
							echo strftime('%A, %d %B %Y', strtotime($row->created_at));
							
							?></p>
                        <div class="media-title"><?php echo $row->nama_pengguna; ?></div>
                        <span class="text-small text-muted"><?php echo $row->nama_peraturan; ?></span>
                      </div>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                  <div class="text-center pt-1 pb-1">
                    <a href="#" class="btn btn-primary btn-lg btn-round" onclick="tandaiSemuaDibaca();">
                        Tandai semua telah dibaca
                    </a>
                </div>
                </div>
              </div>
            </div>
            <?php endif; ?>
                <div class="col-12">
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state" data-height="600">
                                <img height="300px" src="<?php echo base_url(); ?>assets/img/drawkit/drawkit-full-stack-man-colour.svg" alt="image">
                                <h2 class="mt-0">Dashboard Aplikasi Penyusunan Produk Hukum Daerah</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>