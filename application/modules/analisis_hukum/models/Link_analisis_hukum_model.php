<?php

class Link_analisis_hukum_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "link_analisis_hukum";
        $this->primary_id = "id_link_analisis_hukum";
    }
}
