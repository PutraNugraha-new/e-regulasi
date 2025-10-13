<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_usulan_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table = "kategori_usulan";
        $this->primary_id = "id_kategori_usulan";
    }

    // Ambil semua kategori usulan, urutkan berdasarkan nama_kategori
    public function get_all()
    {
        return $this->get(array(
            "order_by" => array("nama_kategori" => "ASC")
        ));
    }
}