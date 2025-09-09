<?php

class Level_user_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->table="level_user";
        $this->primary_id="id_level_user";
    }
}