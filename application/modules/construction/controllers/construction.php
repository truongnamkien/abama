<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Construction extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('construction'));
        $this->load->model(array('static_content_model'));
    }

    public function index() {
        $this->disable_masterview();
        $data = array(
            'email' => FALSE,
            'mobile' => FALSE,
            'phone' => FALSE,
            'facebook_page' => FALSE,
        );
        foreach ($data as $key => &$value) {
            $value = $this->_static_content($key, 'config');
        }
        $data['PAGE_LANG'] = $this->_global_vars['PAGE_LANG'];
        $this->load->view('construction_view', $data);
    }

    public function _static_content($name, $page = 'home') {
        $content = $this->static_content_model->get_where(array('page' => $page, 'content_name' => $name));
        if ($content['return_code'] == API_SUCCESS && !empty($content['data'])) {
            $content = array_shift($content['data']);
            return $content['content'];
        } else {
            return '';
        }
    }

}
