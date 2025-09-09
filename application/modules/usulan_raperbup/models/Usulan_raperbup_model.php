<?php

class Usulan_raperbup_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "usulan_raperbup";
        $this->primary_id = "id_usulan_raperbup";
    }
}
