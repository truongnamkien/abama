<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends MY_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('product'));
        $this->load->helper(array('form', 'url'));
        $this->load->config('upload', TRUE);
        $this->upload_config = $this->config->config['upload'];
    }

    /**
     * HTML form multi upload
     * @param type $existed_files
     * @return type
     */
    public function index($existed_files) {
        $data['existed_files'] = $existed_files;
        return $this->load->view('multi_upload_frm', $data, TRUE);
    }

    /**
     * HTML preview những file đã upload
     * @param type $id
     * @return type
     */
    public function render($id) {
        $data = array('photo_id' => $id);
        $this->load->model('product_photo_model');
        $ret = $this->product_photo_model->get($id);
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            $data['photo_path'] = base_url(Modules::run('photo/_get_photo_path', $ret['data']['url'], 60));
            $data['type'] = 'product_photo';
            return $this->load->view('pagelet_uploaded_item', $data, TRUE);
        }
    }

    /**
     * HTML preview những banner đã upload
     * @param type $id
     * @return type
     */
    public function render_banner($id) {
        $data = array('photo_id' => $id);
        $this->load->model('static_content_model');
        $ret = $this->static_content_model->get($id);
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            $photo_path = Modules::run('photo/_get_photo_path', $ret['data']['content'], 60);
            $data['photo_path'] = base_url($photo_path);
            $data['type'] = 'home_banner';
            return $this->load->view('pagelet_uploaded_item', $data, TRUE);
        }
    }

    /**
     * Force download product photo
     */
    public function download() {
        $file_name = $this->input->get_post('filename');
        if (!is_file($file_name)) {
            show_404();
        }
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // required for IE
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        if ($file_extension == 'jpg') {
            $mime = 'image/jpeg';
        } else if ($file_extension == 'png') {
            $mime = 'image/png';
        }
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        readfile($file_name);
        exit();
    }

    /**
     * Trang list hình Sản phẩm ra để download
     * @param type $offset
     */
    public function product_list($offset = 0) {
        $this->load->language(array('admin', 'product'));
        $this->load->model(array('product_model', 'product_category_model', 'product_photo_model'));
        $data = array();
        $this->set_title(lang('admin_support_product_photo'));

        $category_id = $this->input->get_post('product_category_id');
        if (!empty($category_id)) {
            $product_list = $this->product_model->get_fields(array('product_id'), array('product_category_id' => $category_id));
            if ($product_list['return_code'] == API_SUCCESS && !empty($product_list['data'])) {
                $product_list = $product_list['data'];
                $product_id_list = array();
                foreach ($product_list as $product) {
                    $product_id_list[] = $product['product_id'];
                }
                $product_id_list = array_unique($product_id_list);
                $filter = 'product_id IN (' . implode(', ', $product_id_list) . ')';
                $photo_list = $this->product_photo_model->get_where($filter, 'product_id', "ASC");
                if ($photo_list['return_code'] == API_SUCCESS && !empty($photo_list['data'])) {
                    $photo_list = $photo_list['data'];
                } else {
                    $photo_list = array();
                }
            } else {
                $photo_list = array();
            }
            $config['suffix'] = '?product_category_id=' . $category_id;
        } else {
            $path = $this->config->item('product_photo_path', 'upload');
            $jpg_photos = glob($path . '*.jpg');
            $png_photos = glob($path . '*.png');
            if (empty($jpg_photos)) {
                $jpg_photos = array();
            }
            if (empty($png_photos)) {
                $png_photos = array();
            }
            $photo_list = array_merge($jpg_photos, $png_photos);
        }
        $product_categories = array();
        $product_categories[""] = lang('product_product_category_id');

        $category_list = $this->product_category_model->get_all();
        if ($category_list['return_code'] == API_SUCCESS && !empty($category_list['data'])) {
            $category_list = $category_list['data'];
            foreach ($category_list as $category) {
                $product_categories[$category['product_category_id']] = $category['category_name_' . $this->_current_lang];
            }
        }

        $config['base_url'] = site_url('admin/upload/product_list/');
        $config['total_rows'] = count($photo_list);
        $config['per_page'] = 20;
        $config['first_url'] = site_url('admin/upload/product_list');
        $config['uri_segment'] = '4';
        $config['anchor_class'] = 'class="number"';
        $config['first_link'] = lang('admin_first_link');
        $config['last_link'] = lang('admin_last_link');
        $config['next_link'] = lang('admin_next_link');
        $config['prev_link'] = lang('admin_prev_link');
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $data['pagination'] = TRUE;

        $photo_list = array_slice($photo_list, $offset, $config['per_page']);
        $ret = array();
        foreach ($photo_list as $photo) {
            if (!empty($category_id)) {
                $file_name = glob($photo['url'] . '*.*');
                if (!empty($file_name)) {
                    $ret[] = array(
                        'image' => array_shift($file_name),
                        'thumbnail' => base_url(Modules::run('photo/_get_photo_path', $photo['url'], 100)),
                    );
                }
            } else {
                $photo_url = substr($photo, 0, strlen($photo) - 4);
                $ret[] = array(
                    'image' => $photo,
                    'thumbnail' => base_url(Modules::run('photo/_get_photo_path', $photo_url, 100)),
                );
            }
        }
        $data['photo_list'] = $ret;
        $data['category_list'] = $product_categories;
        $this->load->view('product_list_photo_view', $data);
    }

}
