<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    protected $title_main;
    protected $header_main = 'template_admin/header';
    protected $breadcrumb_main = 'template_admin/breadcrumb';
    protected $sidebar_main = 'template_admin/sidebar';
    protected $index_main = 'template_admin/main';
    protected $footer_main = 'template_admin/footer';

    function __construct()
    {
        parent::__construct();
        $this->load->model('menu/menu_model', 'menu_model');
        $this->load->model('privilege_level/privilege_level_model', 'privilege_level_model');
    }

    public function _remap($method, $params)
    {
        $class_name = $this->router->class;
        $level_user_id = $this->session->userdata('level_user_id');
        if ($class_name != "request") {
            if ($class_name != "Custom404") {
                $menu = $this->menu_model->get(
                    array(
                        "join" => array(
                            "privilege_level_menu" => "id_menu=menu_id"
                        ),
                        "where" => array(
                            "nama_class" => $class_name,
                            "level_user_id" => $level_user_id
                        )
                    ),
                    "row"
                );

                $check_privilege = $this->privilege_level_model->get(
                    array(
                        "where" => array(
                            "level_user_id" => $level_user_id,
                            "menu_id" => $menu->id_menu
                        )
                    ),
                    "row"
                );
                if ($check_privilege->view_content != 1) {
                    $this->page_error();
                }
            }

            if (method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $params);
            }
        } else {
            if (method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $params);
            }
            $this->page_error();
        }
    }

    public function menu($parent_id = 0, $level_user_id)
    {
        $str = "";
        $master = $this->menu_model->query("SELECT id_menu,nama_menu,nama_module,nama_class,class_icon,IFNULL(a.jml_child,0) AS jml_child FROM `menu` LEFT JOIN (SELECT COUNT(*) AS jml_child, id_parent_menu FROM menu WHERE `menu`.`deleted_at` IS NULL GROUP BY id_parent_menu) AS a ON a.id_parent_menu=id_menu WHERE menu.`id_parent_menu` = " . $parent_id . " AND `menu`.`deleted_at` IS NULL AND id_menu IN (SELECT menu_id FROM privilege_level_menu WHERE level_user_id  = " . $level_user_id . " AND view_content = 1 AND deleted_at IS NULL) ORDER BY order_menu")->result_array();

        // var_dump($master);
        // die;

        for ($i = 0; $i < count($master); $i++) {
            $child = "";
            $link = "";
            $li_class = "";
            if ($parent_id == 0) {
                if ($master[$i]['jml_child'] == 0) {
                    $link = "<a href='" . site_url($master[$i]['nama_module']) . "' class='nav-link'><i class='" . $master[$i]['class_icon'] . "'></i><span>" . $master[$i]['nama_menu'] . "</span></a>";
                } else {
                    $li_class .= "nav-item dropdown";
                    $link = "<a href='#' class='nav-link has-dropdown' data-toggle='dropdown'><i class='" . $master[$i]['class_icon'] . "'></i><span>" . $master[$i]['nama_menu'] . "</span></a>";
                    $child = "<ul class='dropdown-menu'>" . $this->menu($master[$i]['id_menu'], $level_user_id) . "</ul>";
                }
            } else {
                if ($master[$i]['jml_child'] == 0) {
                    $child = "<a href='" . site_url($master[$i]['nama_module']) . "' class='nav-link'>" . $master[$i]['nama_menu'] . "</a>";
                } else {
                    $li_class .= "nav-item dropdown";
                    $link = "<a href='#' class='nav-link has-dropdown' data-toggle='dropdown'><span>" . $master[$i]['nama_menu'] . "</span></a>";
                    $child = "<ul class='dropdown-menu'>" . $this->menu($master[$i]['id_menu'], $level_user_id) . "</ul>";
                }
            }

            $str .= "<li class='" . $li_class . "'>" . $link;
            $str .= $child;
            $str .= "</li>";
        }

        return $str;
    }

    public function execute($page, $data = array())
    {
        $CI = &get_instance();
        $CI->load->library('session');
        if ($CI->session->userdata("is_logged_in")) {
            $level_user_id = $CI->session->userdata('level_user_id');
            $data['sidebar'] = $this->menu(0, $level_user_id);
            $data['title_main'] = $this->config->item('APP_TITLE');
            $data['header_main'] = $this->load->view($this->header_main, $data, true);
            $data['breadcrumb_main'] = $this->load->view($this->breadcrumb_main, $data, true);
            $data['sidebar_main'] = $this->load->view($this->sidebar_main, $data, true);
            $data['footer_main'] = $this->load->view($this->footer_main, $data, true);
            $data['content_main'] = $this->load->view($page, $data, true);
            $this->load->view($this->index_main, $data);
        } else {
            redirect("Login");
        }
    }

    public function ipost($name = "")
    {
        return $this->input->post($name, true);
    }

    public function iget($name = "")
    {
        return $this->input->get($name, true);
    }

    public function datetime()
    {
        return $this->config->item('date_now');
    }

    public function upload_file($name_field = "", $upload_path = "", $upload_menu = "", $type_upload = "image")
    {
        if ((!file_exists($upload_path)) && !(is_dir($upload_path))) {
            mkdir($upload_path, 0777);
        }
        $this->load->library('upload');

        $config = array();
        $status = "";
        if ($type_upload == 'image') {
            $filename = md5(uniqid(rand(), true));
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name'] = $filename;
        } else {
            $filename = md5(uniqid(rand(), true));
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'docx|doc|pdf';
            $config['max_size'] = 2048;
            $config['file_name'] = $filename;
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($name_field)) {
            $status = array('error' => $this->upload->display_errors());
        } else {
            $status = array('data' => $this->upload->data());
            if ($upload_menu == "berita") {
                $this->thumbnailResizeImage($status['data']['file_name'], $upload_path);
            }
        }

        return $status;
    }

    public function thumbnailResizeImage($filename, $filepath)
    {
        $source_path = $filepath . '/' . $filename;
        $target_path = $filepath;

        if ((!file_exists($target_path . "/thumbnail")) && !(is_dir($target_path . "/thumbnail"))) {
            mkdir($target_path . "/thumbnail");
        }

        $config = array(
            array(
                'image_library' => 'gd2',
                'source_image' => $source_path,
                'new_image' => $target_path . "/thumbnail/" . $filename,
                'maintain_ratio' => TRUE,
                'width' => 230
            ),
            array(
                'image_library' => 'gd2',
                'source_image' => $source_path,
                'new_image' => $target_path . "/" . $filename,
                'maintain_ratio' => TRUE,
                'width' => 660
            )
        );

        $this->load->library('image_lib', $config[0]);
        foreach ($config as $item) {
            $this->image_lib->initialize($item);
            if (!$this->image_lib->resize()) {
                return false;
            }
            $this->image_lib->clear();
        }
    }

    public function send_notification_mobile($judul, $deskripsi, $read_count, $link_news, $caption, $image_thumb, $tanggal_publish_desc, $tanggal, $image_large, $nama_lengkap, $nama_kategori_berita, $waktu_publish)
    {
        define('API_ACCESS_KEY', 'AAAA4_LLyFU:APA91bHMQJy7VNnhjhDTQTIXFsbVk47lA8e20GB_czzC7BHRI11IGM5xI3G2B8-QkPRFO5gKeponMzTvg4ysmVVhauLJ2yIRNXyfWtsZBfZvZGTjopoBHB0r9-pH7jjw1MpMVZV24e1q');

        $list_token = $this->list_token_model->get();

        $temp = array();

        foreach ($list_token as $key => $row) {
            array_push($temp, $row->token);
        }

        $fields = array(
            'registration_ids' => $temp,
            'priority' => "high",
            'data' => array(
                "title_notif" => "MMC Katingan",
                "judul" => $judul,
                "deskripsi" => $deskripsi,
                "read_count" => $read_count,
                "link_news" => $link_news,
                "caption" => $caption,
                "image_thumb" => $image_thumb,
                "tanggal_publish_desc" => $tanggal_publish_desc,
                "tanggal" => $tanggal,
                "image_large" => $image_large,
                "nama_lengkap" => $nama_lengkap,
                "nama_kategori_berita" => $nama_kategori_berita,
                "waktu_publish" => $waktu_publish,
                "click_action" => "news_detail"
            )
        );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function page_error()
    {
        redirect('404_override');
    }

    public function is_login()
    {
        if (!$this->session->userdata("is_logged_in")) {
            redirect('Login');
        }
    }
}
