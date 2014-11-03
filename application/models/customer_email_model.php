<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Customer_Email_Model extends Abstract_Model {

	public function __construct() {
		$this->type = 'customer_email';
		$this->database = 'customer_email';
		parent::__construct();
	}

	protected function check_existed($data) {
		$email = $this->get_where(array('email' => $data['email']));
		if ($email['return_code'] == API_SUCCESS && !empty($email['data'])) {
			return $this->_ret(API_SUCCESS, TRUE);
		}
		return $this->_ret(API_SUCCESS, FALSE);
	}

	public function create($data) {
		if (!isset($data['email']) || empty($data['email'])) {
			return $this->_ret(API_FAILED);
		}
		if (!isset($data['unsubscribed']) || empty($data['unsubscribed'])) {
			$data['unsubscribed'] = STATUS_INACTIVE;
		}
		return parent::create($data);
	}

}
