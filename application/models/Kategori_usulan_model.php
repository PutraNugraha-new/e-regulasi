<?php

class Kategori_usulan_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "kategori_usulan";
        $this->primary_id = "id_kategori_usulan";
    }
}
