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
                                <legend class="text-uppercase font-size-sm font-weight-bold">Menu</legend>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Pilih Icon</label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Pilih Icon" name="icon_menu" value="<?php echo !empty($content) ? $content->class_icon : ""; ?>" required>
                                            <div class="input-group-append" onclick="show_panel_icon()">
                                                <div class="input-group-text">
                                                    <i class="ion ion-settings"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Parent Menu <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <select class="form-control select-search" name="parent_menu" required>
                                            <option value="0">Root</option>
                                            <?php
                                            echo $menu_option;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Nama Menu <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" value="<?php echo !empty($content) ? $content->nama_menu : ""; ?>" name="nama_menu" required placeholder="Nama Menu">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Nama Module <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" value="<?php echo !empty($content) ? $content->nama_module : ""; ?>" name="nama_module" required placeholder="Nama Module">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Nama Kelas <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" value="<?php echo !empty($content) ? $content->nama_class : ""; ?>" name="nama_class" required placeholder="Nama Kelas">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Urutan Menu <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" value="<?php echo !empty($content) ? $content->order_menu : ""; ?>" name="order_menu" required placeholder="Order Menu">
                                    </div>
                                </div>
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
<div id="showPanelIcon" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function show_panel_icon() {
        $("#showPanelIcon").modal("show");
    }
</script>