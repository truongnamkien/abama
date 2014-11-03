<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Static_Content_Model extends Abstract_Model {

    const STATIC_CONTENT_TYPE_TEXT = 'text';
    const STATIC_CONTENT_TYPE_IMAGE = 'image';

    public function __construct() {
        $this->type = 'static_content';
        $this->database = 'static_content';
        parent::__construct();
    }

    protected function check_existed($data) {
        return $this->_ret(API_SUCCESS, FALSE);
    }

}
