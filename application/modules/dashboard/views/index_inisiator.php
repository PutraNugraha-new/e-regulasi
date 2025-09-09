<div class="main-content">
    <section class="section">
        <?php echo $breadcrumb_main; ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Template Usulan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableTemplateUsulan" class="table datatable-save-state table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Template</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
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

<script>
    let datatableTemplateUsulan = $("#datatableTemplateUsulan").DataTable({
        "bLengthChange": false,
        "bFilter": false,
        "bPaginate": false,
    });
    get_data_template_usulan();

    function get_data_template_usulan() {
        datatableTemplateUsulan.clear().draw();
        $.ajax({
            url: base_url + 'template_usulan/request/get_data_template_usulan',
            type: 'GET',
            beforeSend: function() {
                HoldOn.open(optionsHoldOn);
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    datatableTemplateUsulan.row.add([
                        value.nama_template,
                        value.file
                    ]).draw(false);
                });
            },
            complete: function() {
                HoldOn.close();
            }
        });
    }
</script>