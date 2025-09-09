<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      
    if ( ! function_exists('encrypt_data'))
    {
        function encrypt_data($value)
        {
            $CI =& get_instance();
            $CI->load->library('encryption');
            $res = $CI->encryption->encrypt($value);
            $res = strtr(
                $res,
                array(
                    '+' => '.',
                    '=' => '-',
                    '/' => '@'
                )
            );

            return $res;
        }
    }

    if ( ! function_exists('decrypt_data'))
    {
        function decrypt_data($value)
        {
            $CI =& get_instance();
            $CI->load->library('encryption');
            $res = strtr(
                $value,
                array(
                    '.' => '+',
                    '-' => '=',
                    '@' => '/'
                )
            );
            return $CI->encryption->decrypt($res);
        }
    }