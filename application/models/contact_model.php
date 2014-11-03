<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Contact_Model extends Abstract_Model {

    const VALIDATE_TIME_AMOUNT = 60;

    public function __construct() {
        $this->type = 'contact';
        $this->database = 'contact';
        parent::__construct();
    }

    protected function check_existed($data) {
        $filter = 'email = "' . $data['email'] . '"';
        $filter .= ' AND ip_address = "' . $data['ip_address'] . '"';
        $filter .= ' AND created_at > ' . (time() - self::VALIDATE_TIME_AMOUNT);

        $contact = $this->get_where($filter);
        if ($contact['return_code'] == API_SUCCESS && !empty($contact['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }
        return $this->_ret(API_SUCCESS, FALSE);
    }

    public function create($data) {
        if (!isset($data['email']) || empty($data['email']) || !isset($data['content']) || empty($data['content']) || !isset($data['ip_address']) || empty($data['ip_address'])) {
            return $this->_ret(API_FAILED);
        }
        if (!isset($data['created_at']) || empty($data['created_at'])) {
            $data['created_at'] = time();
        }
        return parent::create($data);
    }

}
