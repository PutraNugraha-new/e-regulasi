<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('level_user/level_user_model', 'level_user_model');
    }

    public function get_data_privilege_level()
    {
        $data_list_privilege_level = $this->level_user_model->get(
            array(
                'fields' => 'id_level_user,nama_level_user,GROUP_CONCAT(nama_menu separator "<br>") as nama_menu',
                'left_join' => array(
                    'privilege_level_menu' => 'privilege_level_menu.level_user_id=level_user.id_level_user AND privilege_level_menu.deleted_at IS NULL',
                    'menu' => 'privilege_level_menu.menu_id=menu.id_menu'
                ),
                'order_by' => array(
                    'nama_level_user' => "ASC"
                ),
                'group_by' => "id_level_user"
            )
        );

        $templist = array();
        foreach ($data_list_privilege_level as $key => $row) {
            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_level_user);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
