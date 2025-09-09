<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('level_user_model');
	}

	public function get_data_level_user()
	{
        $data_level_user = $this->level_user_model->get(
            array(
                'order_by'=>array(
                    'level_user.nama_level_user'=>"ASC"
                )
            )
        );

        $templist = array();
        foreach($data_level_user as $key=>$row){
            foreach($row as $keys=>$rows){
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
