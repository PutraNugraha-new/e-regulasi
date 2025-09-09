<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('menu_model');
	}

	public function get_data_menu()
	{
        $data_list_menu = $this->menu_model->get(
            array(
                'order_by'=>array(
                    'menu.nama_menu'=>"ASC"
                )
            )
        );

        $templist = array();
        foreach($data_list_menu as $key=>$row){
            foreach($row as $keys=>$rows){
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_menu);
        }

        $data = $templist;
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}
