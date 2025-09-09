<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('analisis_hukum_model');
        $this->load->model('link_analisis_hukum_model');
    }

    public function get_data_analisis_hukum()
    {
        $data_analisis_hukum = $this->analisis_hukum_model->get(
            array(
                "fields" => "analisis_hukum.*,GROUP_CONCAT(external_link separator '|') AS external_link",
                "join" => array(
                    "link_analisis_hukum" => "id_analisis_hukum=analisis_hukum_id AND link_analisis_hukum.deleted_at IS NULL"
                ),
                "group_by"=>"id_analisis_hukum"
            )
        );

        $templist = array();
        foreach ($data_analisis_hukum as $key => $row) {
            foreach ($row as $keys => $rows) {
                $templist[$key][$keys] = $rows;
            }

            $templist[$key]['file_analisis_hukum'] = "";
            $file_analisis_hukum_extension = explode(".", $row->file);
            $analisis_hukum_url = base_url() . $this->config->item("file_analisis_hukum") . "/" . $row->file;
            $templist[$key]['file_analisis_hukum'] = "<button type='button' class='btn btn-primary' onclick=\"view_detail('" . $analisis_hukum_url . "','" . $file_analisis_hukum_extension[1] . "')\">View</button>";

            $tag = "";
            $expl_taging = explode(",",$row->taging);

            foreach ($expl_taging as $key_taging => $value) {
                $tag .= "<span class='badge badge-primary m-1'>".$value."</span>";
            }
            
            $link = "";
            $expl_external_link = explode("|",$row->external_link);

            foreach ($expl_external_link as $key_link => $value) {
                $link .= "<a href='".$value."' target='_blank'>".$value."</a><br />";
            }

            $templist[$key]['taging'] = $tag;
            $templist[$key]['external_link'] = $link;
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_analisis_hukum);
        }

        $data = $templist;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
