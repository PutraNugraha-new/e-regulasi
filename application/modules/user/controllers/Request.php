<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('user_model');
        $this->load->model('level_user/level_user_model', 'level_user_model');
    }

    public function get_data_user()
    {
        $level = decrypt_data($this->iget('level', true));
        $temp_wh = array();
        if (!empty($level)) {
            $temp_wh['level_user_id'] = $level;
        }
        $data_user = $this->user_model->get(
            array(
                "join" => array(
                    "level_user" => "level_user_id=id_level_user"
                ),
                "where" => $temp_wh
            )
        );

        $templist = array();
        foreach ($data_user as $key => $row) {
            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_user);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function cek_username()
    {
        $id_user = decrypt_data($this->iget("id_user"));
        $username = $this->iget("username");

        $wh_false = "";
        if ($id_user) {
            $wh_false = "id_user NOT IN (" . $id_user . ")";
        }

        $data_user = $this->user_model->get(
            array(
                "where" => array(
                    "username" => $username
                ),
                "where_false" => $wh_false
            )
        );

        if (!$data_user) {
            $data = true;
        } else {
            $data = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
