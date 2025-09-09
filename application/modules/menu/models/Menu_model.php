<?php

class Menu_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->table="menu";
        $this->primary_id="id_menu";
    }
}