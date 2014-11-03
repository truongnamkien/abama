<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Outer_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('static_content'));
        $this->load->model(array('static_content_model'));
        $this->load->helper(array('array'));
        $this->load->config('content_page', TRUE);
    }

    /**
     * Hiển thị nội dung trang chủ
     */
    public function index() {
        $this->set_title(lang('static_content_title'));
        $notice_list = $this->session->userdata('notice_list');
        $this->session->unset_userdata('notice_list');
        $data = array();
        if (!empty($notice_list)) {
            $data['notice'] = array_shift($notice_list);
        }

        $this->load->view('home_view', $data);
    }

    public function _pagelet_banner() {
        $slide_images = $this->static_content_model->get_where(array('page' => 'home', 'content_name' => 'static_content_banner_home'));
        $data = array(
            'photo' => array()
        );
        if ($slide_images['return_code'] == API_SUCCESS && !empty($slide_images['data'])) {
            $slide_images = $slide_images['data'];
        } else {
            $slide_images = array();
        }
        if (empty($slide_images)) {
            return FALSE;
        }

        $banner_category = $this->config->item('banner_category', 'content_page');

        foreach ($slide_images as &$slide) {
            $slide_data = json_decode($slide['content'], TRUE);
            unset($slide['content']);
            $slide['photo'] = $slide_data['photo'];
            unset($slide['page']);
            unset($slide['content_name']);
            unset($slide['type']);
            $slide = array_merge($slide, $banner_category['static_content_banner_home']);
        }
        $data['banner_list'] = $slide_images;
        return $this->load->view('pagelet_banner', $data, TRUE);
    }

    public function _pagelet_sub_banner($category) {
        $banner_category = $this->config->item('banner_category', 'content_page');
        if (!isset($banner_category['static_content_banner_' . $category])) {
            return FALSE;
        }

        $photo_list = $this->static_content_model->get_where(array('page' => 'home', 'content_name' => 'static_content_banner_' . $category));
        if ($photo_list['return_code'] == API_SUCCESS && !empty($photo_list['data'])) {
            $photo_list = $photo_list['data'];
        } else {
            $photo_list = array();
        }
        if (empty($photo_list)) {
            return FALSE;
        }
        $photo = array_rand($photo_list);
        $photo = array_merge($photo_list[$photo]);

        $photo_content = json_decode($photo['content'], TRUE);
        $photo['photo'] = $photo_content['photo'];
        unset($photo['content']);
        $photo = array_merge($photo, $banner_category['static_content_banner_' . $category]);
        $data = array('photo' => $photo);

        return $this->load->view('pagelet_sub_banner', $data, TRUE);
    }

}
