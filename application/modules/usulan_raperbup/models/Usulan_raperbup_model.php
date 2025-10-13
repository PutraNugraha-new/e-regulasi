<?php

class Usulan_raperbup_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "usulan_raperbup";
        $this->primary_id = "id_usulan_raperbup";
    }

    // Tambahkan fungsi ini di class usulan_raperbup_model
    public function get_tahun_unik()
    {
        $this->db->select('DISTINCT YEAR(created_at) AS tahun');
        $this->db->from($this->table);
        $this->db->order_by('tahun', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
