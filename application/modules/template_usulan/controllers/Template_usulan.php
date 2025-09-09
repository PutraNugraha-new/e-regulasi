<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Template_usulan extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('template_usulan_model');
    }

    public function index()
    {
        $data['breadcrumb'] = ["header_content" => "Template Usulan", "breadcrumb_link" => [['link' => false, 'content' => 'Template Usulan', 'is_active' => true]]];
        $this->execute('index', $data);
    }

    public function tambah_template_usulan()
    {
        if (empty($_POST)) {
            $data['breadcrumb'] = ["header_content" => "Template Usulan", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'template_usulan', 'content' => 'Template Usulan', 'is_active' => false], ['link' => false, 'content' => 'Tambah Template Usulan', 'is_active' => true]]];
            $this->execute('form_template_usulan', $data);
        } else {
            $input_name = "file_template";
            $upload_file = $this->upload_file($input_name, $this->config->item('file_template'), "", "doc");

            if (!isset($upload_file['error'])) {

                $data = array(
                    "nama_template" => $this->ipost("nama_template"),
                    "file_template" => $upload_file['data']['file_name'],
                    'created_at' => $this->datetime(),
                );

                $this->template_usulan_model->save($data);

                redirect('template_usulan');
            } else {
                $this->session->set_flashdata('message', $upload_file['error']);
                $this->session->set_flashdata('type-alert', 'danger');
                redirect('template_usulan/tambah_template_usulan');
            }
        }
    }

    public function edit_template_usulan($id_template_usulan)
    {
        $data_master = $this->template_usulan_model->get_by(decrypt_data($id_template_usulan));

        if (!$data_master) {
            $this->page_error();
        } else {
            if (empty($_POST)) {
                $data['content'] = $data_master;
                $data['breadcrumb'] = ["header_content" => "Template Usulan", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'template_usulan', 'content' => 'Template Usulan', 'is_active' => false], ['link' => false, 'content' => 'Ubah Template Usulan', 'is_active' => true]]];
                $this->execute('form_template_usulan', $data);
            } else {
                if (!empty($_FILES['file_template']['name'])) {
                    $input_name = "file_template";
                    $upload_file = $this->upload_file($input_name, $this->config->item('file_template'), "", "doc");

                    if (!isset($upload_file['error'])) {

                        $data = array(
                            "nama_template" => $this->ipost("nama_template"),
                            "file_template" => $upload_file['data']['file_name'],
                            'updated_at' => $this->datetime(),
                        );

                        $this->template_usulan_model->edit(decrypt_data($id_template_usulan), $data);

                        redirect('template_usulan');
                    } else {
                        $this->session->set_flashdata('message', $upload_file['error']);
                        $this->session->set_flashdata('type-alert', 'danger');
                        redirect('template_usulan/edit_template_usulan/' . $id_template_usulan);
                    }
                } else {
                    $data = array(
                        "nama_template" => $this->ipost("nama_template"),
                        'updated_at' => $this->datetime(),
                    );

                    $this->template_usulan_model->edit(decrypt_data($id_template_usulan), $data);
                    $this->session->set_flashdata('message', 'Data berhasil diubah');
                    $this->session->set_flashdata('type-alert', 'success');
                    redirect('template_usulan');
                }
            }
        }
    }

    public function delete_template_usulan()
    {
        $id_template_usulan = decrypt_data($this->iget('id_template_usulan'));
        $data_master = $this->template_usulan_model->get_by($id_template_usulan);

        if (!$data_master) {
            $this->page_error();
        } else {
            $data_remove = array(
                "deleted_at" => $this->datetime()
            );
            $status = $this->template_usulan_model->edit($id_template_usulan, $data_remove);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }
}
