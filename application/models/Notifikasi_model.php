<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_model extends CI_Model {
    private $table = 'notifikasi';

    public function simpan_notif($data) {
        $result = $this->db->insert($this->table, $data);
        if (!$result) {
            log_message('error', 'Failed to insert notification: ' . json_encode($data) . ' | Error: ' . json_encode($this->db->error()));
        }
        return $result;
    }

    public function get($params = array(), $type = 'result') {
        try {
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
            log_message('debug', 'Get notifikasi query: ' . $this->db->last_query());

            if ($query === FALSE) {
                log_message('error', 'Database error in get notifikasi: ' . json_encode($this->db->error()));
                return [];
            }

            return $type == 'row' ? $query->row() : $query->result();
        } catch (Exception $e) {
            log_message('error', 'Exception in get notifikasi: ' . $e->getMessage());
            return [];
        }
    }

    public function edit($id, $data) {
        try {
            $this->db->where('id_notifikasi', $id);
            $result = $this->db->update($this->table, $data);
            if (!$result) {
                log_message('error', 'Failed to update notification ID: ' . $id . ' | Error: ' . json_encode($this->db->error()));
            }
            return $result;
        } catch (Exception $e) {
            log_message('error', 'Exception in edit notifikasi: ' . $e->getMessage());
            return false;
        }
    }
}