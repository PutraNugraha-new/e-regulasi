<?php

class Trx_spm_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "trx_spm";
        $this->primary_id = "id_trx_spm";
    }
}
