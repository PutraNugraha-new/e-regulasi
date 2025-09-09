<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['breadcrumb'] = ["header_content" => "Dashboard", "breadcrumb_link" => [['link' => false, 'content' => 'Dashboard', 'is_active' => true]]];
        if($this->session->userdata("level_user_id") == "5"){
            $this->execute('index_inisiator', $data);
        }else{
            $this->execute('index', $data);
        }
    }
}
