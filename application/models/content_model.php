<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Content_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'content';
        $this->database = 'content';
        parent::__construct();
    }

    protected function check_existed($data) {
        return $this->_ret(API_SUCCESS, FALSE);
    }

}
