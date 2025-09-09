<div class="main-content">
    <section class="section">
        <input type="hidden" name="id_usulan_raperbup" value="<?php echo $id_usulan_raperbup; ?>" />
        <?php echo $breadcrumb_main; ?>
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
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="lampiran"></h4>
                            <div class="card-header-action">
                                <?php
                                if ($status_upload_perbaikan) {
                                ?>
                                    <a href="#uploadPerbaikan" class="btn btn-info" onclick="panel_upload_perbaikan()"><i class='ion ion-ios-cloud-upload'></i> Upload Perbaikan</a>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="activities">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="showPanelUploadPerbaikan" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Perbaikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="get_detail_usulan_raperbup()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart(base_url() . "usulan_raperbup/upload_perbaikan"); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="hidden" name="id_usulan_raperbup_modal" />
                        <input required type="file" class="form-control" name="file_upload" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        <small class="form-text text-muted">
                            Max. Upload Size : 2 MB
                        </small>
                        <small class="form-text text-muted">
                            Type File : doc, docx, & pdf
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_ditolak()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="get_detail_usulan_raperbup()">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div id="showPanelUploadPerbaikanHasilRapat" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Perbaikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="get_detail_usulan_raperbup()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart(base_url() . "usulan_raperbup/upload_perbaikan_hasil_rapat"); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">File</label>
                    <div class="col-lg-10">
                        <input type="hidden" name="id_usulan_raperbup_modal" />
                        <input required type="file" class="form-control" name="file_upload" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        <small class="form-text text-muted">
                            Max. Upload Size : 2 MB
                        </small>
                        <small class="form-text text-muted">
                            Type File : doc, docx, & pdf
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="save_ditolak()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="get_detail_usulan_raperbup()">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
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
    get_detail_usulan_raperbup();

    function get_detail_usulan_raperbup() {
        $.ajax({
            url: base_url + 'usulan_raperbup/request/get_detail_usulan_raperbup',
            data: {
                id_usulan_raperbup: $("input[name='id_usulan_raperbup']").val()
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                let html = "";
                $.each(response, function(index, value) {
                    html +=
                        "<div class='activity'>" +
                        "<div class='activity-icon bg-primary text-white shadow-primary'>" +
                        "<i class='fas fa-thumbtack'></i>" +
                        "</div>" +
                        "<div class='activity-detail'>" +
                        "<div class='mb-2'>" +
                        "<span class='text-job text-primary'>" + value.tanggal_custom + "</span>" +
                        "</div>" +
                        "<p>" + value.status_terakhir + "</p>" +
                        "</div>" +
                        "</div>"
                });

                $(".activities").html(html);
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

    function panel_upload_perbaikan() {
        $("#showPanelUploadPerbaikan").modal("show");
        $("input[name='id_usulan_raperbup_modal']").val($("input[name='id_usulan_raperbup']").val());
    }

    function panel_upload_perbaikan_hasil_rapat() {
        $("#showPanelUploadPerbaikanHasilRapat").modal("show");
        $("input[name='id_usulan_raperbup_modal']").val($("input[name='id_usulan_raperbup']").val());
    }

    get_file_lampiran();

    function get_file_lampiran() {
        $(".lampiran").html("");
        $.ajax({
            url: base_url + 'usulan_raperbup/request/get_file_lampiran',
            data: {
                id_usulan_raperbup: $("input[name='id_usulan_raperbup']").val()
            },
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                if (response) {
                    let html = "<table>";

                    html += "<tr><td><h4>Lampiran Kepala Dinas</h4></td><td style='padding:10px;'>:</td><td>" + response.lampiran_kepala_dinas + "</td></tr>";

                    if (response.lampiran_sk_tim) {
                        html += "<tr><td><h4>Lampiran SK Tim</h4></td><td style='padding:10px;'>:</td><td>" + response.lampiran_sk_tim + "</td></tr>";
                    }

                    if (response.lampiran_daftar_hadir) {
                        html += "<tr><td><h4>Lampiran Daftar Hadir</h4></td><td style='padding:10px;'>:</td><td>" + response.lampiran_daftar_hadir + "</td></tr>";
                    }
                    html += "</table>";

                    $(".lampiran").html(html);
                }
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }

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
</script>