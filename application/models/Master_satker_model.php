<?php

class Master_satker_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "master_satker";
        $this->primary_id = "id_master_satker";
    }
}
