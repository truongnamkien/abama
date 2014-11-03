<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Product_Category_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'product_category';
        $this->database = 'product_category';
        $this->load->config('content_page', TRUE);
        parent::__construct();
    }

    public function get_by_name($data) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " AND " : "") . 'category_name_' . $lang . ' = "' . $data['category_name_' . $lang] . '"';
        }
        if (isset($data['parent_id']) && !empty($data['parent_id'])) {
            $filter .= " AND parent_id = \"" . $data['parent_id'] . "\"";
        } else {
            $filter .= ' AND (parent_id = 0 OR parent_id IS NULL)';
        }

        return $this->get_where($filter);
    }

    protected function check_existed($data) {
        $category_info = $this->get_by_name($data);
        if ($category_info['return_code'] == API_SUCCESS && !empty($category_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

}
