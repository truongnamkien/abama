<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Keyword_Model extends Abstract_Model {

    const KEYWORD_TYPE_PAGE = 'page';
    const KEYWORD_TYPE_BLOG = 'blog';
    const KEYWORD_TYPE_PRODUCT_CATEGORY = 'product_category';
    const KEYWORD_TYPE_PRODUCT = 'product';

    public function __construct() {
        $this->type = 'keyword';
        $this->database = 'keyword';
        parent::__construct();
    }

    protected function check_existed($data) {
        $filter = array(
            'target_id' => $data['target_id'],
            'target_type' => $data['target_type'],
        );
        $keyword_info = $this->get_where($filter);
        if ($keyword_info['return_code'] == API_SUCCESS && !empty($keyword_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

}
