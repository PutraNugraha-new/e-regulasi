<style>
    .user-image-custom {
        margin-bottom: 10px;
    }

    .tokenfield.form-control:not(input) {
    padding: 0 0 0.125rem 0;
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
                        <label class="col-form-label col-lg-2">Judul <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input required type="text" class="form-control" name="judul" value="<?php echo !empty($content) ? $content->judul : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">File Analisis Hukum <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <?php
                            if (!empty($content)) {
                                echo $url_preview_analisis_hukum;
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
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Tag <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                        <input type="text" class="form-control inputtags" required value="<?php echo !empty($content) ? $content->taging : ""; ?>" data-fouc>
                        <input type="hidden" name="tag" value="<?php echo !empty($content) ? $content->taging : ""; ?>" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Link <?php echo !empty($content) ? "" : "<span class='text-danger'>*</span>"; ?></label>
                        <div class="col-lg-10">
                            <button type="button" class="btn btn-success mb-2" onclick="add_link()">+</button>
                            <?php
                            if(!empty($content)){
                                $expl = explode("|",$content->external_link);
                                $expl_id = explode("|",$content->external_link_id);
                                foreach ($expl as $key => $value) {
                                    ?>
                                    <input type="text" class="form-control mb-2" name="external_link_edit[]" value="<?php echo $value; ?>" />
                                    <input type="hidden" name="id_edit_external_link[]" value="<?php echo $expl_id[$key]; ?>" />
                                    <?php
                                }
                            }
                            ?>
                            <span class='group_link'>
                                <?php
                                if(empty($content)){
                                ?>
                                <input type="text" required class="form-control" name="external_link[]" />
                                <?php
                                }
                                ?>
                                <span></span>
                            </span>
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
    $(".inputtags").tagsinput('items');
    $(".inputtags").on("change",function(event){
        var $element = $(event.target);
        var val = $element.val();
        $("input[name='tag']").val(val);
    }).trigger("change");

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

    function add_link(){
        $("<input type='text' required class='form-control mt-2' name='external_link[]' />").insertBefore(".group_link>span");
    }

    $("form").submit(function() {
        let pass = false;
        if($("input[name='external_link_edit[]']").length != 0){
            for(let i = 0;i<$("input[name='external_link_edit[]']").length;i++){
                if($("input[name='external_link_edit[]']").eq(i).val() != ""){
                    pass = true;
                    break;
                }
            }
        }else{
            pass = true;
        }

        if(pass == false){
            swal('Gagal', 'Link wajib diisi', 'error');
        }
		return pass;
	});
</script>