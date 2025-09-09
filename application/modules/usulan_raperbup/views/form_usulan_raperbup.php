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
                        <input type="hidden" name="kategori_usulan_hidden" value="<?php echo !empty($content) ? $content->kategori_usulan_id : ""; ?>" />
                        <label class="col-form-label col-lg-2">Kategori Usulan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <select class="form-control" name="kategori_usulan" onchange="check_lampiran()">
                                <option value="">-- PILIH KATEGORI</option>
                                <?php
                                foreach ($kategori_usulan as $key => $value) {
                                    $selected = "";

                                    if (!empty($content)) {
                                        if ($value->id_kategori_usulan == $content->kategori_usulan_id) {
                                            $selected = "selected";
                                        }
                                    }
                                ?>
                                    <option <?php echo $selected; ?> value="<?php echo encrypt_data($value->id_kategori_usulan); ?>" data-id="<?php echo $value->id_kategori_usulan; ?>"><?php echo $value->nama_kategori; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama Peraturan <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input required type="text" class="form-control" name="nama_peraturan" value="<?php echo !empty($content) ? $content->nama_peraturan : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">File Usulan <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_usulan;
                            }
                            ?>
                            <input <?php echo !empty($content) ? "" : "required"; ?> type="file" class="form-control" name="file_upload" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
                        </div>
                    </div>
                    <div class="form-group row is-show-lampiran-kepala-dinas">
                        <label class="col-form-label col-lg-2">Lampiran Surat Pengantar Kepala Dinas <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
                        </div>
                    </div>

                    <div class="form-group row is-show-lampiran-sk-tim">
                        <label class="col-form-label col-lg-2">Lampiran SK Tim <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran_sk_tim;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran_sk_tim" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="form-text text-muted">
                                Max. Upload Size : 2 MB
                            </small>
                            <small class="form-text text-muted">
                                Type File : doc, docx, & pdf
                            </small>
                        </div>
                    </div>

                    <div class="form-group row is-show-lampiran-daftar-hadir">
                        <label class="col-form-label col-lg-2">Lampiran Daftar Hadir <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_lampiran_daftar_hadir;
                            }
                            ?>
                            <input type="file" class="form-control" name="file_lampiran_daftar_hadir" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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

        </div>
    </section>
</div>

<div id="showFormDetail" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body isi-content">

            </div>
        </div>
    </div>
</div>

<script>
    $(".is-show-lampiran-kepala-dinas").hide();
    $(".is-show-lampiran-sk-tim").hide();
    $(".is-show-lampiran-daftar-hadir").hide();

    function view_detail(file, ekstensi) {
        let file_extension = ["pdf", "jpg", "jpeg", "png"];
        $("#showFormDetail").modal("show");
        if (file_extension.indexOf(ekstensi) >= 0) {
            if (ekstensi == "pdf") {
                $(".isi-content").html("<div class='embed-responsive embed-responsive-1by1'>" +
                    "<iframe class='embed-responsive-item' src='" + file + "'></iframe>" +
                    "</div>");
            } else {
                $(".isi-content").html("<img width='1100' src='" + file + "' />");
            }

        } else {
            $(".isi-content").html("<div class='text-center'><img height='300px' src='" + base_url + "assets/img/drawkit/drawkit-full-stack-man-colour.svg' alt='image'><h6>Dokumen file tidak bisa di lihat karena ekstensi file tidak didukung untuk dilihat di browser</h6><a class='btn btn-success' download href='" + file + "'>Download</a></div>");
        }
    }

    check_lampiran();

    function check_lampiran() {
        $("input[name='file_lampiran']").attr("required", false);
        $("input[name='file_lampiran_sk_tim']").attr("required", false);
        $("input[name='file_lampiran_daftar_hadir']").attr("required", false);
        let kategori_usulan_hidden = $("input[name='kategori_usulan_hidden']").val();
        let kategori_usulan = $("select[name='kategori_usulan'] option:selected").data("id");

        if (kategori_usulan == "1" || kategori_usulan == "2") {
            $(".is-show-lampiran-kepala-dinas").show();
            $(".is-show-lampiran-sk-tim").show();
            $(".is-show-lampiran-daftar-hadir").show();
            if (kategori_usulan_hidden == "") {
                $("input[name='file_lampiran']").attr("required", true);
                $("input[name='file_lampiran_sk_tim']").attr("required", true);
                $("input[name='file_lampiran_daftar_hadir']").attr("required", true);
            }
        } else if (kategori_usulan == "3") {
            $(".is-show-lampiran-kepala-dinas").show();
            $(".is-show-lampiran-sk-tim").hide();
            $(".is-show-lampiran-daftar-hadir").hide();
            if (kategori_usulan_hidden == "") {
                $("input[name='file_lampiran']").attr("required", true);
                $("input[name='file_lampiran_sk_tim']").attr("required", false);
                $("input[name='file_lampiran_daftar_hadir']").attr("required", false);
            }
        } else {
            $("input[name='file_lampiran']").attr("required", false);
            $("input[name='file_lampiran_sk_tim']").attr("required", false);
            $("input[name='file_lampiran_daftar_hadir']").attr("required", false);
            $(".is-show-lampiran-kepala-dinas").hide();
            $(".is-show-lampiran-sk-tim").hide();
            $(".is-show-lampiran-daftar-hadir").hide();
        }
    }
</script>