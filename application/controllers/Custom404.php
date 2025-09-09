<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom404 extends MY_Controller {

    function __construct(){
        parent::__construct();
    }

	public function index()
	{
        $this->output->set_status_header('404'); 
        $data['breadcrumb'] = [['link'=>true,'url'=>base_url().'dashboard','content'=>'Dashboard','is_active'=>false],['link'=>false,'content'=>'Error 404','is_active'=>true]];
        $this->execute('template_admin/show_error_404',$data);
    }
}
