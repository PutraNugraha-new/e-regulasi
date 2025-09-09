<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo form_open(current_url(), array('class' => 'form-validate-jquery')); ?>
                            <fieldset class="mb-3">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Level <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <?php echo isset($content) ? $content->nama_level_user : ""; ?>
                                    </div>
                                </div>
                                <?php
                                foreach ($menu as $key => $row) {
                                ?>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2"><?php echo $row->nama_menu; ?></label>
                                        <div class="col-lg-10">
                                            <input type="checkbox" name="privilege_level[<?php echo $row->id_menu; ?>][view]" value="1" <?php echo ($row->view_content == 1) ? 'checked' : ""; ?>> View
                                            <input type="checkbox" name="privilege_level[<?php echo $row->id_menu; ?>][update]" value="1" <?php echo ($row->update_content == 1) ? 'checked' : ""; ?>> Update
                                            <input type="checkbox" name="privilege_level[<?php echo $row->id_menu; ?>][delete]" value="1" <?php echo ($row->delete_content == 1) ? 'checked' : ""; ?>> Delete
                                            <input type="checkbox" name="privilege_level[<?php echo $row->id_menu; ?>][add]" value="1" <?php echo ($row->create_content == 1) ? 'checked' : ""; ?>> Add
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </fieldset>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Simpan <i class="icon-paperplane ml-2"></i></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>