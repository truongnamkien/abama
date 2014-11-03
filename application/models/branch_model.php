<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Branch_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'branch';
        $this->database = 'branch';
        parent::__construct();
    }

    protected function check_existed($data) {
        $filter = array('name' => $data['name']);
        $branch_info = $this->get_where($filter);
        if ($branch_info['return_code'] == API_SUCCESS && !empty($branch_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

}
