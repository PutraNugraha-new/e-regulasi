<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{

    protected $_table;
    protected $_primary_id;

    //for insert activity to table log. true if active, false isn't active
    protected $_status_log = false;

    public $_database;

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->db;
    }

    public function query($query)
    {
        return $this->_database->query($query);
    }

    public function get($config = array(), $query_result = "result")
    {
        if (!empty($config) && !empty($config['fields'])) {
            $this->_database->select($config['fields']);
        }

        if (!empty($config) && !empty($config['join'])) {
            foreach ($config['join'] as $key => $row) {
                $this->_database->join($key, $row);
            }
        }

        if (!empty($config) && !empty($config['left_join'])) {
            foreach ($config['left_join'] as $key => $row) {
                $this->_database->join($key, $row, 'left');
            }
        }

        if (!empty($config) && !empty($config['right_join'])) {
            foreach ($config['right_join'] as $key => $row) {
                $this->_database->join($key, $row, 'right');
            }
        }

        if (!empty($config) && !empty($config['where'])) {
            $this->_database->where($config['where']);
        }

        if (!empty($config) && !empty($config['or_where'])) {
            $this->_database->or_where($config['or_where']);
        }

        if (!empty($config) && !empty($config['or_where_in'])) {
            foreach ($config['or_where_in'] as $key => $row) {
                $this->_database->or_where_in($key, $row);
            }
        }

        if (!empty($config) && !empty($config['or_where_not_in'])) {
            foreach ($config['or_where_not_in'] as $key => $row) {
                $this->_database->or_where_not_in($key, $row);
            }
        }

        if (!empty($config) && !empty($config['where_in'])) {
            foreach ($config['where_in'] as $key => $row) {
                $this->_database->where_in($key, $row);
            }
        }

        if (!empty($config) && !empty($config['where_false'])) {
            $this->_database->where($config['where_false'], NULL, FALSE);
        }

        if (!empty($config) && !empty($config['group_by'])) {
            $this->_database->group_by($config['group_by']);
        }

        if (!empty($config) && !empty($config['order_by'])) {
            foreach ($config['order_by'] as $key => $row) {
                $this->_database->order_by($key, $row);
            }
        }

        if (!empty($config) && !empty($config['order_by_false'])) {
            $this->_database->order_by($config['order_by_false']);
        }

        if (!empty($config) && !empty($config['limit'])) {
            $this->_database->limit($config['limit']);
        }

        if (!empty($config) && !empty($config['limit_arr'])) {
            $this->_database->limit($config['limit_arr'][1], $config['limit_arr'][0]);
        }

        $this->_database->where($this->table . '.deleted_at', NULL);

        if ($query_result == "result") {
            return $this->_database->get($this->table)->result();
        } else if ($query_result == "result_array") {
            return $this->_database->get($this->table)->result_array();
        } else if ($query_result == "row") {
            return $this->_database->get($this->table)->row();
        } else if ($query_result == "row_array") {
            return $this->_database->get($this->table)->row_array();
        }
    }

    public function get_by($value = "")
    {
        if ($value != "") {
            $this->_database->where($this->primary_id, $value);
            return $this->_database->get($this->table)->row();
        }
    }

    //untuk log setiap aktifitas insert, update, delete
    /*CREATE TABLE `log` (
        `id_log` bigint(30) NOT NULL AUTO_INCREMENT,
        `table_log` varchar(100) DEFAULT NULL,
        `rincian_log` text,
        `user_id` int(11) DEFAULT NULL,
        `id_primary_log` int(11) DEFAULT NULL,
        `date_log` datetime DEFAULT NULL,
        `operation_query_log` enum('insert','delete','update') DEFAULT NULL,
        PRIMARY KEY (`id_log`)
      ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8*/
    protected function trigger_log($desc = "", $id_primary = "")
    {
        if ($this->_status_log) {
            if (!empty($desc) && !empty($id_primary)) {
                $data = array(
                    'table_log' => $this->table,
                    'rincian_log' => $desc,
                    'user_id' => 1,
                    'id_primary_log' => $id_primary,
                    'date_log' => $this->datetime()
                );
                $this->_database->insert('log', $data);
            }
        }
    }

    public function save($data = array())
    {
        if (!empty($data)) {
            $this->_database->insert($this->table, $data);
            $insert_id = $this->_database->insert_id();

            if (!empty($insert_id)) {
                $desc = 'menambahkan data di table ' . $this->table . ' value ' . json_encode($data);
                $this->trigger_log($desc, $insert_id);
            }

            return $insert_id;
        } else {
            return false;
        }
    }

    public function save_batch($data = array())
    {
        if (!empty($data)) {
            $status = $this->_database->insert_batch($this->table, $data);

            return $status;
        } else {
            return false;
        }
    }

    public function edit($value = "", $data = array())
    {
        if (!empty($data) && !empty($value)) {

            //start: ambil data sebelum update
            $tmpArr = array();
            foreach ($data as $key => $row) {
                array_push($tmpArr, $key);
            }
            $fields = implode(",", $tmpArr);

            $this->db->start_cache();

            $data_master = $this->get(
                array(
                    "fields" => $fields,
                    "where" => array(
                        $this->primary_id => $value
                    )
                ),
                "result_array"
            );
            $this->db->stop_cache();

            $this->db->flush_cache();
            //end:

            $this->_database->where($this->primary_id, $value);
            $result = $this->_database->update($this->table, $data);

            if ($result) {
                $desc = 'merubah data di table ' . $this->table . ' before ' . json_encode($data_master) . ' after ' . json_encode($data);
                $this->trigger_log($desc, $value);
            }

            return $result;
        } else {
            return false;
        }
    }

    public function edit_batch($value = "", $data = array())
    {
        if (!empty($data) && !empty($value)) {

            $result = $this->_database->update_batch($this->table, $data, $value);

            return $result;
        } else {
            return false;
        }
    }

    public function edit_by($condition = array(), $data = array())
    {
        if (!empty($data) && !empty($condition)) {

            //start : ambil data sebelum update
            $tmpArr = array();
            foreach ($data as $key => $row) {
                array_push($tmpArr, $key);
            }

            array_push($tmpArr, $this->primary_id);

            $fields = implode(",", $tmpArr);

            $this->db->start_cache();

            $data_master = $this->get(
                array(
                    "fields" => $fields,
                    "where" => $condition
                ),
                "row_array"
            );
            $this->db->stop_cache();

            $this->db->flush_cache();
            //end:

            if (is_array($condition)) {
                foreach ($condition as $key => $row) {
                    $this->_database->where($key, $row);
                }
            } else {
                echo 'Paramater 1 bukan array';
                die;
            }

            $result = $this->_database->update($this->table, $data);

            if ($result) {
                $desc = 'merubah data di table ' . $this->table . ' before ' . json_encode($data_master) . ' after ' . json_encode($data);
                $this->trigger_log($desc, $data_master[$this->primary_id]);
            }

            return $result;
        } else {
            return false;
        }
    }

    public function remove($value = "", $soft_delete = true)
    {
        if (!empty($value)) {
            if ($soft_delete) {
                $data = array(
                    "deleted_at" => $this->datetime()
                );
                $result = $this->edit($value, $data);
                return $result;
            } else {
                $this->db->start_cache();

                $data_master = $this->get(
                    array(
                        "where" => array(
                            $this->primary_id => $value
                        )
                    ),
                    "row_array"
                );
                $this->db->stop_cache();

                $this->db->flush_cache();

                $result = $this->_database->delete($this->table, array($this->primary_id => $value));

                if ($result) {
                    $desc = 'menghapus data di table ' . $this->table . ' value ' . json_encode($data_master);
                    $this->trigger_log($desc, $data_master[$this->primary_id]);
                }

                return $result;
            }
        } else {
            return false;
        }
    }

    public function datetime()
    {
        return $this->config->item('date_now');
    }

    public function start_transac()
    {
        return $this->_database->trans_begin();
    }

    public function status_transac()
    {
        return $this->_database->trans_status();
    }

    public function rollback_transac()
    {
        return $this->_database->trans_rollback();
    }

    public function commit_transac()
    {
        return $this->_database->trans_commit();
    }
}
