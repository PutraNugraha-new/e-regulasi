<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('web')) {
    function replace_dot($value)
    {
        return str_replace(".", "", $value);
    }
}

if (!function_exists('nominal')) {
    function nominal($angka)
    {
        $jd = number_format($angka, 0, ',', '.');
        return $jd;
    }
}
