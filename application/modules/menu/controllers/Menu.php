<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('menu_model');
    }

    public function index()
    {
        $data['breadcrumb'] = ["header_content" => "Menu", "breadcrumb_link" => [['link' => false, 'content' => 'Menu']]];
        $this->execute('index', $data);
    }

    public function menu_option(&$str, $parent_id = 0, $prefix, $select, $editidmenu)
    {
        $master = $this->menu_model->query("SELECT id_menu,nama_menu,nama_module,nama_class,class_icon,IFNULL(a.jml_child,0) AS jml_child FROM `menu` LEFT JOIN (SELECT COUNT(*) AS jml_child, id_parent_menu FROM menu GROUP BY id_parent_menu) AS a ON a.id_parent_menu=id_menu WHERE menu.`id_parent_menu` = " . $parent_id . " AND `menu`.`deleted_at` IS NULL AND id_menu IN (SELECT menu_id FROM privilege_level_menu) ORDER BY order_menu")->result_array();

        for ($i = 0; $i < count($master); $i++) {
            if ($editidmenu == $master[$i]['id_menu']) {
                continue;
            }
            $sel = "";
            if ($select == $master[$i]['id_menu']) {
                $sel = "selected='selected'";
            }
            $str .= "<option " . $sel . " value='" . encrypt_data($master[$i]['id_menu']) . "'>" . $prefix . $master[$i]['nama_menu'] . "</option>";
            $this->menu_option($str, $master[$i]['id_menu'], $prefix . "-", $select, $editidmenu);;
        }

        return $str;
    }

    public function tambah_menu()
    {
        if (empty($_POST)) {
            $str = "";
            $data['menu_option'] = $this->menu_option($str, 0, "-", "", "");
            $data['breadcrumb'] = ["header_content" => "Menu", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'menu', 'content' => 'Menu',], ['link' => false, 'content' => 'Tambah Menu']]];
            $this->execute('form_menu', $data);
        } else {

            $data = array(
                "class_icon" => $this->ipost('icon_menu'),
                "id_parent_menu" => decrypt_data($this->ipost('parent_menu')),
                "nama_menu" => $this->ipost('nama_menu'),
                "nama_module" => $this->ipost('nama_module'),
                "nama_class" => $this->ipost('nama_class'),
                "order_menu" => $this->ipost('order_menu'),
                'created_at' => $this->datetime()
            );

            $status = $this->menu_model->save($data);
            if ($status) {
                $this->session->set_flashdata('message', 'Data baru berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('message', 'Data baru gagal ditambahkan');
            }

            redirect('menu');
        }
    }

    public function edit_menu($id_menu)
    {
        $data_master = $this->menu_model->get_by(decrypt_data($id_menu));

        if (!$data_master) {
            $this->page_error();
        }

        if (empty($_POST)) {
            $data['content'] = $data_master;
            $str = "";
            $data['menu_option'] = $this->menu_option($str, 0, "-", $data_master->id_parent_menu, decrypt_data($id_menu));
            $data['breadcrumb'] = ["header_content" => "Menu", "breadcrumb_link" => [['link' => true, 'url' => base_url() . 'menu', 'content' => 'Menu', 'is_active' => false], ['link' => false, 'content' => 'Ubah Menu']]];
            $this->execute('form_menu', $data);
        } else {
            $data = array(
                "class_icon" => $this->ipost('icon_menu'),
                "id_parent_menu" => decrypt_data($this->ipost('parent_menu')),
                "nama_menu" => $this->ipost('nama_menu'),
                "nama_module" => $this->ipost('nama_module'),
                "nama_class" => $this->ipost('nama_class'),
                "order_menu" => $this->ipost('order_menu'),
                'updated_at' => $this->datetime()
            );

            $status = $this->menu_model->edit(decrypt_data($id_menu), $data);
            if ($status) {
                $this->session->set_flashdata('message', 'Data berhasil diubah');
            } else {
                $this->session->set_flashdata('message', 'Data gagal diubah');
            }

            redirect('menu');
        }
    }

    public function delete_menu()
    {
        $id_menu = decrypt_data($this->iget('id_menu'));
        $data_master = $this->menu_model->get_by($id_menu);

        if (!$data_master) {
            $this->page_error();
        }

        $status = $this->menu_model->remove($id_menu);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }
}
