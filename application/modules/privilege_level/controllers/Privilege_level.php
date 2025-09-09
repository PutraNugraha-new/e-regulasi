<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privilege_level extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('privilege_level_model');
        $this->load->model('menu/menu_model', 'menu_model');
        $this->load->model('level_user/level_user_model', 'level_user_model');
    }

    public function index()
    {
        $data['breadcrumb'] = ["header_content" => "Privilege Menu", "breadcrumb_link" => [['link' => false, 'content' => 'Privilege Menu', 'is_active' => true]]];
        $this->execute('index', $data);
    }

    public function set_privilege_menu($id_level_user)
    {
        $data_master = $this->level_user_model->get_by(decrypt_data($id_level_user));
        if (!$data_master) {
            $this->page_error();
        }

        if (empty($_POST)) {

            $data['menu'] = $this->menu_model->query("SELECT id_menu,nama_menu,GROUP_CONCAT(create_content) AS create_content,GROUP_CONCAT(update_content) AS update_content,GROUP_CONCAT(delete_content) AS delete_content,GROUP_CONCAT(view_content) AS view_content FROM menu
            LEFT JOIN (
                SELECT menu_id,GROUP_CONCAT(create_content) AS create_content,GROUP_CONCAT(update_content) AS update_content,GROUP_CONCAT(delete_content) AS delete_content,GROUP_CONCAT(view_content) AS view_content 
                FROM privilege_level_menu WHERE level_user_id = " . decrypt_data($id_level_user) . "  AND deleted_at IS NULL GROUP BY menu_id) AS a ON id_menu=a.menu_id
            WHERE deleted_at IS NULL
            GROUP BY id_menu")->result();

            $data['breadcrumb'] = ["header_content" => "Privilege Menu", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'privilege_level', 'content' => 'Privilege Menu', 'is_active' => false], ['link' => false, 'content' => 'Set Privilege Menu', 'is_active' => true]]];
            $this->execute('form_privilege_level', $data);
        } else {
            $menu = $this->menu_model->get();
            foreach ($menu as $key => $row) {
                $check_privilege = $this->privilege_level_model->get(
                    array(
                        "where" => array(
                            "level_user_id" => decrypt_data($id_level_user),
                            "menu_id" => $row->id_menu
                        )
                    ),
                    "row"
                );

                if (!$check_privilege) {
                    if (isset($_POST['privilege_level'][$row->id_menu])) {
                        $data_insert = array(
                            "level_user_id" => decrypt_data($id_level_user),
                            "menu_id" => $row->id_menu,
                            "view_content" => !isset($_POST['privilege_level'][$row->id_menu]['view']) ? '0' : $_POST['privilege_level'][$row->id_menu]['view'],
                            "update_content" => !isset($_POST['privilege_level'][$row->id_menu]['update']) ? '0' : $_POST['privilege_level'][$row->id_menu]['update'],
                            "delete_content" => !isset($_POST['privilege_level'][$row->id_menu]['delete']) ? '0' : $_POST['privilege_level'][$row->id_menu]['delete'],
                            "create_content" => !isset($_POST['privilege_level'][$row->id_menu]['add']) ? '0' : $_POST['privilege_level'][$row->id_menu]['add'],
                            "created_at" => $this->datetime()
                        );

                        $this->privilege_level_model->save($data_insert);
                    }
                } else {
                    $data_update = array(
                        "view_content" => !isset($_POST['privilege_level'][$row->id_menu]['view']) ? '0' : $_POST['privilege_level'][$row->id_menu]['view'],
                        "update_content" => !isset($_POST['privilege_level'][$row->id_menu]['update']) ? '0' : $_POST['privilege_level'][$row->id_menu]['update'],
                        "delete_content" => !isset($_POST['privilege_level'][$row->id_menu]['delete']) ? '0' : $_POST['privilege_level'][$row->id_menu]['delete'],
                        "create_content" => !isset($_POST['privilege_level'][$row->id_menu]['add']) ? '0' : $_POST['privilege_level'][$row->id_menu]['add'],
                        "updated_at" => $this->datetime()
                    );

                    if (!isset($_POST['privilege_level'][$row->id_menu]['view']) && !isset($_POST['privilege_level'][$row->id_menu]['update']) && !isset($_POST['privilege_level'][$row->id_menu]['delete']) && !isset($_POST['privilege_level'][$row->id_menu]['add'])) {
                        $this->privilege_level_model->remove($check_privilege->id_privilege);
                    } else {
                        $this->privilege_level_model->edit_by(array("level_user_id" => decrypt_data($id_level_user), "menu_id" => $row->id_menu), $data_update);
                    }
                }
            }
            redirect('privilege_level');
        }
    }

    public function delete_privilege_level($id_privilege_level)
    {
        $data_master = $this->privilege_level_model->get_by(decrypt_data($id_privilege_level));

        if (!$data_master) {
            $this->page_error();
        }

        $status = $this->privilege_level_model->remove(decrypt_data($id_privilege_level));
        if ($status) {
            $this->session->set_flashdata('message', 'Data berhasil dihapus');
        } else {
            $this->session->set_flashdata('message', 'Data gagal dihapus');
        }
        redirect('privilege_level');
    }
}
