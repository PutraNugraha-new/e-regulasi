<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('pemilik_usaha/pemilik_usaha_model','pemilik_usaha_model');
		$this->load->model('data_usaha/data_usaha_model','data_usaha_model');
		$this->load->model('aset_omset_usaha/aset_omset_usaha_model','aset_omset_usaha_model');
	}

	function get_jumlah_data_pemilik_usaha_masuk(){

        $data = $this->pemilik_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_pemilik_usaha_masuk"
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function get_jumlah_data_usaha_masuk(){

        $data = $this->data_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_data_usaha_masuk",
                "join"=>array(
                    "master_pemilik_usaha"=>"id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1",
                ),
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    function get_jumlah_data_aset_omset_masuk(){

        $data = $this->aset_omset_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_data_aset_omset_masuk",
                "join"=>array(
                    "master_data_usaha"=>"id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL and master_data_usaha.is_verified = 1",
                    "master_pemilik_usaha"=>"id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1"
                ),
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    function get_jumlah_data_pemilik_usaha_belum_terverifikasi(){

        $data = $this->pemilik_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_pemilik_usaha_belum_terverifikasi",
                "where"=>array(
                    "master_pemilik_usaha.is_verified"=>"0"
                )
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function get_jumlah_data_usaha_belum_terverifikasi(){

        $data = $this->data_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_data_usaha_belum_terverifikasi",
                "join"=>array(
                    "master_pemilik_usaha"=>"id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1",
                ),
                "where"=>array(
                    "master_data_usaha.is_verified"=>"0"
                )
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    function get_jumlah_data_aset_omset_belum_terverifikasi(){

        $data = $this->aset_omset_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_data_aset_omset_belum_terverifikasi",
                "join"=>array(
                    "master_data_usaha"=>"id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL and master_data_usaha.is_verified = 1",
                    "master_pemilik_usaha"=>"id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1"
                ),
                "where"=>array(
                    "aset_omset_usaha.is_verified"=>"0"
                )
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function get_jumlah_data_pemilik_usaha_sudah_terverifikasi(){

        $data = $this->pemilik_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_pemilik_usaha_sudah_terverifikasi",
                "where"=>array(
                    "master_pemilik_usaha.is_verified"=>"1"
                )
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function get_jumlah_data_usaha_sudah_terverifikasi(){

        $data = $this->data_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_data_usaha_sudah_terverifikasi",
                "join"=>array(
                    "master_pemilik_usaha"=>"id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1",
                ),
                "where"=>array(
                    "master_data_usaha.is_verified"=>"1"
                )
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    function get_jumlah_data_aset_omset_sudah_terverifikasi(){

        $data = $this->aset_omset_usaha_model->get(
            array(
                "fields"=>"IFNULL(count(*),0) as jumlah_data_aset_omset_sudah_terverifikasi",
                "join"=>array(
                    "master_data_usaha"=>"id_data_usaha=master_data_usaha_id AND master_data_usaha.deleted_at IS NULL and master_data_usaha.is_verified = 1",
                    "master_pemilik_usaha"=>"id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL and master_pemilik_usaha.is_verified = 1"
                ),
                "where"=>array(
                    "aset_omset_usaha.is_verified"=>"1"
                )
            ),"row"
        );

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    function get_jumlah_unit_usaha(){

        $data = $this->data_usaha_model->query("
        SELECT IFNULL(COUNT(*),0) AS jumlah_unit_usaha
        FROM master_data_usaha
        JOIN master_pemilik_usaha ON id_pemilik_usaha=master_pemilik_usaha_id AND master_pemilik_usaha.deleted_at IS NULL AND master_pemilik_usaha.is_verified = 1
        WHERE DATE_FORMAT(master_data_usaha.created_at,'%Y') = (SELECT DATE_FORMAT(master_data_usaha.created_at,'%Y') AS tahun
        FROM master_data_usaha
        WHERE master_data_usaha.deleted_at IS NULL
        GROUP BY tahun
        ORDER BY tahun DESC
        LIMIT 1)
        AND master_data_usaha.deleted_at IS NULL AND master_data_usaha.is_verified = 1
        ")->row();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function get_jumlah_aset_umkm(){

        $data = $this->aset_omset_usaha_model->query("
        SELECT IFNULL(SUM(jumlah_aset), 0) AS jumlah_aset_umkm 
        FROM `aset_omset_usaha` 
        JOIN `master_data_usaha` ON `id_data_usaha`=`master_data_usaha_id` AND `master_data_usaha`.`deleted_at` IS NULL AND `master_data_usaha`.`is_verified` = 1 
        JOIN `master_pemilik_usaha` ON `id_pemilik_usaha`=`master_pemilik_usaha_id` AND `master_pemilik_usaha`.`deleted_at` IS NULL AND `master_pemilik_usaha`.`is_verified` = 1 
        WHERE `aset_omset_usaha`.`is_verified` = '1' AND `aset_omset_usaha`.`deleted_at` IS NULL
        AND tahun_berkenaan = (SELECT tahun_berkenaan AS tahun
        FROM aset_omset_usaha
        WHERE `aset_omset_usaha`.`deleted_at` IS NULL
        GROUP BY tahun
        ORDER BY tahun DESC
        LIMIT 1)
        ")->row();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function get_jumlah_omset_umkm(){

        $data = $this->aset_omset_usaha_model->query("
        SELECT IFNULL(SUM(jumlah_omset_per_tahun), 0) AS jumlah_omset_umkm 
        FROM `aset_omset_usaha` 
        JOIN `master_data_usaha` ON `id_data_usaha`=`master_data_usaha_id` AND `master_data_usaha`.`deleted_at` IS NULL AND `master_data_usaha`.`is_verified` = 1 
        JOIN `master_pemilik_usaha` ON `id_pemilik_usaha`=`master_pemilik_usaha_id` AND `master_pemilik_usaha`.`deleted_at` IS NULL AND `master_pemilik_usaha`.`is_verified` = 1 
        WHERE `aset_omset_usaha`.`is_verified` = '1' AND `aset_omset_usaha`.`deleted_at` IS NULL
        AND tahun_berkenaan = (SELECT tahun_berkenaan AS tahun
        FROM aset_omset_usaha
        WHERE `aset_omset_usaha`.`deleted_at` IS NULL
        GROUP BY tahun
        ORDER BY tahun DESC
        LIMIT 1)
        ")->row();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}
