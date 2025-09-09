<?php

class Privilege_level_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "privilege_level_menu";
        $this->primary_id = "id_privilege";
    }
}
