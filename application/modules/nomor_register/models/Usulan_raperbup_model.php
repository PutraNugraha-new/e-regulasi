<?php

class Usulan_raperbup_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "usulan_raperbup";
        $this->primary_id = "id_usulan_raperbup";
    }

    public function get_next_nomor($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }

        $this->db->select_max('nomor_register');
        $this->db->where('tahun', $tahun);
        $query = $this->db->get('usulan_raperbup');

        $row = $query->row();
        return ($row && $row->nomor_register) ? $row->nomor_register + 1 : 1;
    }

    public function cek_nomor_ada($nomor_register, $tahun)
    {
        return $this->db->where('nomor_register', $nomor_register)
            ->where('tahun', $tahun)
            ->count_all_results('usulan_raperbup') > 0;
    }

    public function simpan_nomor($id_usulan, $nomor_register, $tahun)
    {
        $data = [
            'nomor_register' => $nomor_register,
            'tahun' => $tahun
        ];

        $this->db->where('id_usulan_raperbup', $id_usulan);
        return $this->db->update('usulan_raperbup', $data);
    }

    public function get_usulan_by_id($id)
    {
        return $this->db->get_where('usulan_raperbup', ['id_usulan_raperbup' => $id])->row();
    }
}
