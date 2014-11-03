<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Blog_Category_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'blog_category';
        $this->database = 'blog_category';
        $this->load->config('content_page', TRUE);
        parent::__construct();
    }

    protected function check_existed($data) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " AND " : "") . 'name_' . $lang . ' = "' . $data['name_' . $lang] . '"';
        }
        $blog_category_info = $this->get_where($filter);
        if ($blog_category_info['return_code'] == API_SUCCESS && !empty($blog_category_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

}
