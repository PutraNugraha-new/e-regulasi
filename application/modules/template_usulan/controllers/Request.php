<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('template_usulan_model');
    }

    public function get_data_template_usulan()
    {
        $data_usulan = $this->template_usulan_model->get(
            array(
                "order_by" => array(
                    "nama_template" => "ASC"
                )
            )
        );

        $templist = array();
        foreach ($data_usulan as $key => $row) {
            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }

            $templist[$key]['file'] = "<a href='" . base_url() . "assets/file_template/" . $row->file_template . "' download>Download</a>";
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_template_usulan);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
