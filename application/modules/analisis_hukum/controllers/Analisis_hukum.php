<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analisis_hukum extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('analisis_hukum_model');
        $this->load->model('link_analisis_hukum_model');
    }

    public function index()
    {
        $data['breadcrumb'] = ["header_content" => "Analisis Hukum", "breadcrumb_link" => [['link' => false, 'content' => 'Analisis Hukum', 'is_active' => true]]];
        $this->execute('index', $data);
    }

    public function tambah_analisis_hukum()
    {
        if (empty($_POST)) {
            $data['breadcrumb'] = ["header_content" => "Analisis Hukum", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'analisis_hukum', 'content' => 'Analisis Hukum', 'is_active' => false], ['link' => false, 'content' => 'Tambah Analisis Hukum', 'is_active' => true]]];
            $this->execute('form_analisis_hukum', $data);
        } else {
            $input_name = "file_upload";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_analisis_hukum'), "", "pdf");

            if (!isset($upload_file['error'])) {

                $data = array(
                    "judul" => $this->ipost("judul"),
                    "file" => $upload_file['data']['file_name'],
                    "taging" => $this->ipost("tag"),
                    'created_at' => $this->datetime(),
                );

                $status = $this->analisis_hukum_model->save($data);

                foreach ($this->ipost("external_link") as $key => $value) {
                    if($value){
                        $data_link = array(
                            "external_link" => $value,
                            "analisis_hukum_id" => $status,
                            'created_at' => $this->datetime(),
                        );
        
                        $this->link_analisis_hukum_model->save($data_link);
                    }
                }
                if ($status) {
                    $this->session->set_flashdata('message', 'Data baru berhasil ditambahkan');
                    $this->session->set_flashdata('type-alert', 'success');
                    redirect('analisis_hukum');
                } else {
                    $this->session->set_flashdata('message', 'Data baru gagal ditambahkan');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('analisis_hukum');
                }
            } else {
                $this->session->set_flashdata('message', $upload_file['error']);
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('analisis_hukum/tambah_analisis_hukum');
            }
        }
    }

    public function edit_analisis_hukum($id_analisis_hukum)
    {
        $data_master = $this->analisis_hukum_model->get(
            array(
                "fields" => "analisis_hukum.*,GROUP_CONCAT(external_link ORDER BY id_link_analisis_hukum separator '|') AS external_link,GROUP_CONCAT(id_link_analisis_hukum ORDER BY id_link_analisis_hukum separator '|') AS external_link_id",
                "join" => array(
                    "link_analisis_hukum" => "id_analisis_hukum=analisis_hukum_id AND link_analisis_hukum.deleted_at IS NULL"
                ),
                "where"=>array(
                    "id_analisis_hukum"=>decrypt_data($id_analisis_hukum)
                ),
                "group_by"=>"id_analisis_hukum"
            ),
            "row"
        );

        if (!$data_master) {
            $this->page_error();
        } else {
            if (empty($_POST)) {
                $data['content'] = $data_master;

                $ekstensi_file_analisis_hukum = explode(".", $data_master->file);
                $data['url_preview_analisis_hukum'] = "<button type='button' class='btn btn-primary mb-2' onclick=\"view_detail('" . base_url() . $this->config->item("file_analisis_hukum") . "/" . $data_master->file . "','" . $ekstensi_file_analisis_hukum[1] . "')\">View</button>";

                $data['breadcrumb'] = ["header_content" => "Analisis Hukum", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'analisis_hukum', 'content' => 'Analisis Hukum', 'is_active' => false], ['link' => false, 'content' => 'Ubah Analisis Hukum', 'is_active' => true]]];
                $this->execute('form_analisis_hukum', $data);
            } else {
                $nama_file_usulan = $data_master->file;
                if (!empty($_FILES['file_upload']['name'])) {
                    $input_name = "file_upload";
                    $upload_file = $this->upload_file($input_name, $this->config->item('file_analisis_hukum'), "", "pdf");
                    if (isset($upload_file['error'])) {
                        $this->session->set_flashdata('message', $upload_file['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('analisis_hukum/edit_analisis_hukum/' . $id_analisis_hukum);
                    }

                    $nama_file_usulan = $upload_file['data']['file_name'];
                }

                $data = array(
                    "judul" => $this->ipost("judul"),
                    "file" => $nama_file_usulan,
                    "taging" => $this->ipost("tag"),
                    'updated_at' => $this->datetime(),
                );

                $status = $this->analisis_hukum_model->edit(decrypt_data($id_analisis_hukum), $data);

                foreach ($this->ipost("external_link_edit") as $key => $value) {
                    if($value){
                        $data_link = array(
                            "external_link" => $value,
                            'updated_at' => $this->datetime(),
                        );
        
                        $this->link_analisis_hukum_model->edit($this->ipost("id_edit_external_link")[$key], $data_link);
                    }else{
                        $data_remove = array(
                            "deleted_at" => $this->datetime(),
                        );
                        $this->link_analisis_hukum_model->edit($this->ipost("id_edit_external_link")[$key], $data_remove);
                    }
                }

                foreach ($this->ipost("external_link") as $key => $value) {
                    if($value){
                        $data_link = array(
                            "external_link" => $value,
                            "analisis_hukum_id" => decrypt_data($id_analisis_hukum),
                            'created_at' => $this->datetime(),
                        );
        
                        $this->link_analisis_hukum_model->save($data_link);
                    }
                }

                if ($status) {
                    $this->session->set_flashdata('message', 'Data berhasil diubah');
                    $this->session->set_flashdata('type-alert', 'success');
                    redirect('analisis_hukum');
                } else {
                    $this->session->set_flashdata('message', 'Data gagal diubah');
                    $this->session->set_flashdata('type-alert', 'danger');
                    redirect('analisis_hukum');
                }
            }
        }
    }

    public function delete_analisis_hukum()
    {
        $id_analisis_hukum = decrypt_data($this->iget('id_analisis_hukum'));
        $data_master = $this->analisis_hukum_model->get_by($id_analisis_hukum);

        if (!$data_master) {
            $this->page_error();
        } else {
            $data_remove = array(
                "deleted_at" => $this->datetime(),
            );
            $status = $this->analisis_hukum_model->edit($id_analisis_hukum, $data_remove);
            $this->link_analisis_hukum_model->edit_by(array("analisis_hukum_id"=>$id_analisis_hukum), $data_remove);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }
}
