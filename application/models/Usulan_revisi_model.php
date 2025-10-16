<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usulan_revisi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    protected $table = 'usulan_revisi';

    public function get_all_revisi($id_usulan)
    {
        $this->db->select('usulan_revisi.*, user.nama_lengkap AS nama_user');
        $this->db->where('usulan_revisi.id_usulan_raperbup', $id_usulan);
        $this->db->join('user', 'user.id_user = usulan_revisi.id_user', 'left'); // 'left' untuk menangani kasus jika user tidak ada
        $query = $this->db->get('usulan_revisi');
        return $query->result();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id_revisi, $data)
    {
        $this->db->where('id_revisi', $id_revisi);
        return $this->db->update($this->table, $data);
    }

    public function delete($id_revisi)
    {
        $this->db->where('id_revisi', $id_revisi);
        return $this->db->delete($this->table);
    }

    public function get_by_id($id_revisi)
    {
        $this->db->select('usulan_revisi.*, user.nama_lengkap as nama_user');
        $this->db->from($this->table);
        $this->db->join('user', 'user.id_user = usulan_revisi.id_user', 'left');
        $this->db->where('usulan_revisi.id_revisi', $id_revisi);
        return $this->db->get()->row();
    }

    public function get_by_usulan($id_usulan_raperbup)
    {
        $this->db->select('usulan_revisi.*, user.nama_lengkap as nama_user');
        $this->db->from($this->table);
        $this->db->join('user', 'user.id_user = usulan_revisi.id_user', 'left');
        $this->db->where('usulan_revisi.id_usulan_raperbup', $id_usulan_raperbup);
        $this->db->order_by('usulan_revisi.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
