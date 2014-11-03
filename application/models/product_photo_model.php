<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Product_Photo_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'product_photo';
        $this->database = 'product_photo';
        parent::__construct();
    }

    protected function check_existed($data) {
        return $this->_ret(API_SUCCESS, FALSE);
    }

}
