<?php

class Analisis_hukum_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "analisis_hukum";
        $this->primary_id = "id_analisis_hukum";
    }
}
