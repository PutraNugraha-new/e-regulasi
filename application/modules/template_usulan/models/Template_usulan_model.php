<?php

class Template_usulan_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "template_usulan";
        $this->primary_id = "id_template_usulan";
    }
}
