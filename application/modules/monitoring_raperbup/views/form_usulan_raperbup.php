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
                    <fieldset class="mb-3">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">File <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <input type="file" class="form-control" name="file_upload" accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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