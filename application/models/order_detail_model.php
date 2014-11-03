<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Order_Detail_Model extends Abstract_Model {

	public function __construct() {
		$this->type = 'order_detail';
		$this->database = 'order_detail';
		parent::__construct();
	}

	protected function check_existed($data) {
		$order_detail = $this->get_where(array('order_id' => $data['order_id'], 'product_id' => $data['product_id']));
		if ($order_detail['return_code'] == API_SUCCESS && !empty($order_detail['data'])) {
			return $this->_ret(API_SUCCESS, TRUE);
		}
		return $this->_ret(API_SUCCESS, FALSE);
	}

	public function create($data) {
		if (!isset($data['order_id']) || empty($data['order_id'])
				|| !isset($data['price']) || empty($data['price'])
				|| !isset($data['quantity']) || empty($data['quantity'])
				|| !isset($data['product_id']) || empty($data['product_id'])) {
			return $this->_ret(API_FAILED);
		}
		return parent::create($data);
	}

	/**
	 * Lấy danh sách Sản phẩm bán chạy
	 * @return type
	 */
	public function get_best_seller_list() {
		$query = $this->db->query('SELECT SUM(order_detail.quantity) AS total, order_detail.product_id FROM order_detail GROUP BY order_detail.product_id ORDER BY total DESC;');
		if (!empty($query)) {
			return $query->result_array();
		}
		return array();
	}

}
