<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usulan_revisi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_revisi($id_usulan)
    {
        $this->db->select('usulan_revisi.*, user.nama_lengkap AS nama_user');
        $this->db->where('usulan_revisi.id_usulan_raperbup', $id_usulan);
        $this->db->join('user', 'user.id_user = usulan_revisi.id_user', 'left'); // 'left' untuk menangani kasus jika user tidak ada
        $query = $this->db->get('usulan_revisi');
        return $query->result();
    }

    public function insert_revisi($data)
    {
        return $this->db->insert('usulan_revisi', $data);
    }

    public function update_revisi($id_revisi, $data)
    {
        $this->db->where('id_revisi', $id_revisi);
        return $this->db->update('usulan_revisi', $data);
    }

    public function delete_revisi($id_revisi)
    {
        $this->db->where('id_revisi', $id_revisi);
        return $this->db->delete('usulan_revisi');
    }
}
