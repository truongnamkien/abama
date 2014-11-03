<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Product_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'product';
        $this->database = 'product';
        $this->load->config('content_page', TRUE);
        parent::__construct();
    }

    protected function check_existed($data) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " AND " : "") . 'name_' . $lang . ' = "' . $data['name_' . $lang] . '"';
        }
        $filter .= " AND product_category_id = \"" . $data['product_category_id'] . "\"";
        $product_info = $this->get_where($filter);
        if ($product_info['return_code'] == API_SUCCESS && !empty($product_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

    public function create($data) {
        if (!isset($data['created_at']) || empty($data['created_at'])) {
            $data['created_at'] = time();
        }
        return parent::create($data);
    }

}
