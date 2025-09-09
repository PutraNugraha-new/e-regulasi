<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        if ($this->session->userdata('is_logged_in') == true) {
            redirect('dashboard');
        } else {
            $this->load->view("login");
        }
    }

    public function act_login()
    {
        $username = $this->input->post('username', true);
        $pass = $this->input->post('password');
        $status = false;

        $data_user = $this->user_model->get(
            array(
                "fields" => "user.*,urutan_legalitas",
                "join" => array(
                    "level_user" => "level_user_id=id_level_user"
                ),
                'where' => array(
                    'username' => $username
                )
            ),
            'row'
        );

        if ($data_user) {
            if (password_verify($pass, $data_user->password)) {
                $this->session->set_userdata('nama_lengkap', $data_user->nama_lengkap);
                $this->session->set_userdata('level_user_id', $data_user->level_user_id);
                $this->session->set_userdata('urutan_legalitas', $data_user->urutan_legalitas);
                $this->session->set_userdata('id_user', $data_user->id_user);
                $this->session->set_userdata('is_logged_in', true);
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($status));
    }

    public function act_logout()
    {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {

            // remove session datas
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }

            // user logout ok
            redirect('login');
        } else {

            redirect('login');
        }
    }
}
