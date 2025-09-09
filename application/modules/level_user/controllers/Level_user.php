<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Level_user extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('level_user_model');
    }

    public function index()
    {
        $data['list_level_user'] = $this->level_user_model->get(
            array(
                'order_by' => array(
                    'level_user.nama_level_user' => "ASC"
                )
            )
        );

        $data['breadcrumb'] = ["header_content" => "Level User", "breadcrumb_link" => [['link' => false, 'content' => 'Level User', 'is_active' => true]]];
        $this->execute('index', $data);
    }

    public function tambah_level_user()
    {
        if (empty($_POST)) {
            $data['breadcrumb'] = ["header_content" => "Level User", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'level_user', 'content' => 'Level User', 'is_active' => false], ['link' => false, 'content' => 'Tambah Level User', 'is_active' => true]]];
            $this->execute('form_level_user', $data);
        } else {

            $data = array(
                "nama_level_user" => $this->ipost('level_user'),
                'created_at' => $this->datetime()
            );

            $status = $this->level_user_model->save($data);
            if ($status) {
                $this->session->set_flashdata('message', 'Data baru berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('message', 'Data baru gagal ditambahkan');
            }

            redirect('level_user');
        }
    }

    public function edit_level_user($id_level_user)
    {
        $data_master = $this->level_user_model->get_by(decrypt_data($id_level_user));

        if (!$data_master) {
            $this->page_error();
        }

        if (empty($_POST)) {
            $data['content'] = $data_master;
            $data['breadcrumb'] = ["header_content" => "Level User", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'level_user', 'content' => 'Level User', 'is_active' => false], ['link' => false, 'content' => 'Ubah Level User', 'is_active' => true]]];
            $this->execute('form_level_user', $data);
        } else {
            $data = array(
                "nama_level_user" => $this->ipost('level_user'),
                'updated_at' => $this->datetime()
            );

            $status = $this->level_user_model->edit(decrypt_data($id_level_user), $data);
            if ($status) {
                $this->session->set_flashdata('message', 'Data berhasil diubah');
            } else {
                $this->session->set_flashdata('message', 'Data gagal diubah');
            }

            redirect('level_user');
        }
    }

    public function delete_level_user()
    {
        $id_level_user = decrypt_data($this->iget('id_level_user'));
        $data_master = $this->level_user_model->get_by($id_level_user);

        if (!$data_master) {
            $this->page_error();
        }

        $status = $this->level_user_model->remove($id_level_user);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }
}
