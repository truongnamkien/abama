<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Recruitment_Application_Model extends Abstract_Model {

	const VALIDATE_TIME_AMOUNT = 60;

	public function __construct() {
		$this->type = 'recruitment_application';
		$this->database = 'recruitment_application';
		parent::__construct();
	}

	protected function check_existed($data) {
		$filter = array(
			'recruitment_id' => $data['recruitment_id'],
			'email' => $data['email'],
		);
		$application_info = $this->get_where($filter);
		if ($application_info['return_code'] == API_SUCCESS && !empty($application_info['data'])) {
			return $this->_ret(API_SUCCESS, TRUE);
		}
		return $this->_ret(API_SUCCESS, FALSE);
	}

}
