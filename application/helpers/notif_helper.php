<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      
    if ( ! function_exists('show_message'))
    {
        function show_message()
        {
            $CI = get_instance();
            $CI->load->library('session');
            if(!empty($CI->session->flashdata('message'))){
                echo '<div class="alert alert-danger">'.$CI->session->flashdata('message').'</div>';
            }
        }

        function show_input_error(){
            echo validation_errors('<div class="alert alert-danger">','</div>');
        }
    }