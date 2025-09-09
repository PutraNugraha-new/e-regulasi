<?php

class Trx_raperbup_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "trx_raperbup";
        $this->primary_id = "id_trx_raperbup";
    }
    public function updateStatusPesan($status) {
        // Update status pesan di tabel trx_raperbup
        $data = array('status_pesan' => $status);
        $this->db->update('trx_raperbup', $data);

        // Jika perlu, Anda juga dapat menambahkan WHERE clause untuk membatasi pembaruan
        // $this->db->where('kondisi', 'nilai');

        // Kembalikan status pembaruan
        return $this->db->affected_rows();
    }
}
