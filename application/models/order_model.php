<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Order_Model extends Abstract_Model {

    const VALIDATE_TIME_AMOUNT = 60;
    const ORDER_STATUS_PENDING = 'pending';
    const ORDER_STATUS_PROGRESSING = 'progressing';
    const ORDER_STATUS_FINISHED = 'finished';
    const ORDER_STATUS_CANCELLED = 'cancelled';

    public function __construct() {
        $this->type = 'order';
        $this->database = 'order';
        parent::__construct();
    }

    protected function check_existed($data) {
        $filter = 'mobile = "' . $data['mobile'] . '"';
        $filter .= ' AND address = "' . $data['address'] . '"';
        $filter .= ' AND created_at > ' . (time() - self::VALIDATE_TIME_AMOUNT);

        $order = $this->get_where($filter);
        if ($order['return_code'] == API_SUCCESS && !empty($order['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }
        return $this->_ret(API_SUCCESS, FALSE);
    }

    public function create($data) {
        if (!isset($data['mobile']) || empty($data['mobile']) || !isset($data['address']) || empty($data['address'])) {
            return $this->_ret(API_FAILED);
        }
        $data['status'] = self::ORDER_STATUS_PENDING;
		$time = time();
        if (!isset($data['created_at']) || empty($data['created_at'])) {
            $data['created_at'] = $time;
        }
        return parent::create($data);
    }

}
