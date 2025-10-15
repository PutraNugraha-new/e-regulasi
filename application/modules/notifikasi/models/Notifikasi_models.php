<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_model extends CI_Model {
    private $table = 'notifikasi';

    public function simpan_notif($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get($params = array(), $type = 'result') {
        if (isset($params['fields'])) {
            $this->db->select($params['fields']);
        }

        if (isset($params['join'])) {
            foreach ($params['join'] as $table => $condition) {
                $this->db->join($table, $condition, 'left');
            }
        }

        if (isset($params['where'])) {
            $this->db->where($params['where']);
        }

        if (isset($params['order_by'])) {
            foreach ($params['order_by'] as $field => $direction) {
                $this->db->order_by($field, $direction);
            }
        }

        if (isset($params['limit'])) {
            $this->db->limit($params['limit']);
        }

        $query = $this->db->get($this->table);
        return $type == 'row' ? $query->row() : $query->result();
    }

    public function edit($id, $data) {
        $this->db->where('id_notifikasi', $id);
        return $this->db->update($this->table, $data);
    }
}