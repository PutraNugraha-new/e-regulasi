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
                    <?php echo form_open_multipart(); ?>
                    <?php
                    if (!empty($this->session->flashdata('message'))) {
                        echo "<div class='alert " . ($this->session->flashdata('type-alert') == 'success' ? 'alert-success' : 'alert-danger') . " alert-dismissible show fade'>
                            <div class='alert-body'>
                            <button class='close' data-dismiss='alert'>
                                <span>×</span>
                            </button>
                            " . $this->session->flashdata('message') . "
                            </div>
                        </div>";
                    }
                    ?>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama Template <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input required type="text" class="form-control" name="nama_template" value="<?php echo !empty($content) ? $content->nama_template : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">File <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input <?php echo !empty($content) ? "" : "required"; ?> type="file" class="form-control" name="file_template" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan <i class="icon-paperplane ml-2"></i></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <!-- /form inputs -->

            <!-- <?php
            if (isset($content)) {
            ?>
                <div class="card">
                    <div class="card-body">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="http://docs.google.com/gview?url=http://sicali.karamunting.com/assets/file_usulan/I_PERNYATAAN_DAN_NEGASINYA.pdf&amp;embedded=true"></iframe>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?> -->

        </div>
    </section>
</div>