<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Product_Ajax extends MY_Ajax {

	public function __construct() {
		parent::__construct();
		$this->load->library(array('cart'));
		$this->load->language('product');
		$this->load->model(array('product_model', 'order_detail_model'));
	}

	/**
	 * Thêm vào giỏ hàng
	 * @param type $product_id
	 */
	public function add_to_cart($product_id) {
		$product = $this->product_model->get($product_id);
		if ($product['return_code'] == API_SUCCESS && !empty($product['data']) && $product['data']['status'] == STATUS_ACTIVE) {
			$product = $product['data'];
			if ($product['sold_out'] == STATUS_ACTIVE) {
				$this->response->run("show_alert('" . lang('product_add_to_cart_failed') . "');");
			} else {
				$rowid = md5($product_id);
				$item = $this->get_cart_row($rowid);
				$qty = $this->input->get_post('quantity');
				if (!empty($item)) {
					unset($item['rowid']);
					$item['qty'] += $qty;
				} else {
					$title = $product['name_' . $this->_current_lang];
					$item = array(
						'id' => $product_id,
						'qty' => $qty,
						'price' => isset($product['price_off']) && !empty($product['price_off']) ? $product['price_off'] : $product['price'],
						'name' => slug_convert($title),
					);
				}
				$this->cart->insert($item);
				$this->response->run("show_alert('" . lang('product_add_to_cart_success', '', $qty) . "');");
				$this->update_cart_content(FALSE);
			}
		} else {
			$this->response->run("show_alert('" . lang('product_add_to_cart_failed') . "');");
		}

		$this->response->send();
	}

	/**
	 * Remove Sản phẩm khỏi giỏ hàng
	 * @param type $rowid
	 */
	public function remove_from_cart($rowid) {
		$item = $this->get_cart_row($rowid);
		if (!empty($item)) {
			$item['qty'] = 0;
			$this->cart->update($item);
		}
		$this->response->run("$('#row_" . $rowid . "').fadeTo(500, 0, function() {\$('#row_" . $rowid . "').remove();});");
		$this->update_cart_content();
	}

	/**
	 * Hiện form chỉnh sửa giỏ hàng
	 * @param type $rowid
	 */
	public function edit_item($rowid) {
		$item = $this->get_cart_row($rowid);
		$html = Modules::run('product/_pagelet_cart_edit', $item);
		if (!empty($html)) {
			$this->response->html("#row_" . $rowid, $html);
		} else {
			$this->response->run("$('#row_" . $rowid . "').fadeTo(500, 0, function() {\$('#row_" . $rowid . "').remove();});");
		}
		$this->response->send();
	}

	/**
	 * Cập nhật thông tin giỏ hàng
	 * @param type $rowid
	 */
	public function edit($rowid) {
		$item = $this->get_cart_row($rowid);
		if (!empty($item)) {
			$item['qty'] = $this->input->get_post('qty');
			$this->cart->update($item);
		}
		$this->update_cart_content();
	}

	/**
	 * Xử lý form điền thông tin giao nhận
	 */
	public function checkout($first_time = FALSE) {
		$html = Modules::run('product/_pagelet_checkout_view', $first_time);
		if ($html === TRUE) {
			$this->response->run('window.location = "' . site_url() . '";');
		} else if (!empty($html)) {
			$this->response->html("#cart_content", $html);
		}
		$this->response->send();
	}

	/**
	 * Từ trang thanh toán back lại trang giỏ hàng
	 */
	public function cart() {
		$this->update_cart_content();
	}

	/**
	 * Lấy thông tin 1 item trong giỏ hàng
	 * @param type $rowid
	 * @return boolean
	 */
	private function get_cart_row($rowid) {
		$cart = $this->cart->contents();
		if (isset($cart[$rowid])) {
			$item = $cart[$rowid];

			$product = $this->product_model->get($item['id']);
			return $item;
		}
		return FALSE;
	}

	/**
	 * Cập nhật lại thông tin giỏ hàng khi có chỉnh sửa
	 */
	public function update_cart_content($cart_view = TRUE) {
		$html = Modules::run('product/_pagelet_cart_content');
		$this->response->html('#cart_mini', $html);
		if ($cart_view) {
			$html = Modules::run('product/_pagelet_cart_view');
			$this->response->html('#cart_content', $html);
		}
		$this->response->send();
	}

	public function admin_edit_item($item_id) {
		$item = $this->order_detail_model->get($item_id);
		if ($item['return_code'] == API_SUCCESS && !empty($item['data'])) {
			$item = $item['data'];
			$product = $this->product_model->get($item['product_id']);
			$item['product'] = $product['data'];
		} else {
			$item = array();
		}
		$html = Modules::run('admin/order/_pagelet_order_edit', $item);
		if (!empty($html)) {
			$this->response->html("#detail_" . $item_id, $html);
		} else {
			$this->response->run("$('#detail_" . $item_id . "').fadeTo(500, 0, function() {\$('#detail_" . $item_id . "').remove();});");
		}
		$this->response->send();
	}

	public function admin_edit($item_id) {
		$item = $this->order_detail_model->get($item_id);
		if ($item['return_code'] == API_SUCCESS && !empty($item['data'])) {
			$item = $item['data'];
			$qty = $this->input->get_post('qty');

			$product = $this->product_model->get($item['product_id']);
			$this->order_detail_model->update($item_id, array(
				'quantity' => $qty,
			));

			$this->update_admin_cart($item['order_id']);
		}
	}

	public function admin_remove($item_id) {
		$item = $this->order_detail_model->get($item_id);
		$this->order_detail_model->delete($item_id);
		$this->update_admin_cart($item['data']['order_id']);
	}

	public function update_admin_cart($order_id) {
		$html = Modules::run('admin/order/_pagelet_order_view', $order_id);
		if (!empty($html)) {
			$this->response->html("#cart_content", $html);
		}
		$this->response->send();
	}

	public function admin_add_cancel() {
		$html = Modules::run('admin/order/_pagelet_order_add_button');
		$this->response->run("
			$('#new_item').fadeTo(500, 0, function() {
				$('#new_item').remove();
				$('.product_row').after('" . json_encode($html) . "');
			});;
		");
		$this->response->send();
	}

	public function admin_add() {
		$html = Modules::run('admin/order/_pagelet_order_add_row');
		if (!empty($html)) {
			$this->response->html("#new_item", $html);
		}
		$this->response->send();
	}

	public function admin_add_product($category_id) {
		$html = Modules::run('admin/order/_pagelet_order_add_product', $category_id);
		if (!empty($html)) {
			$this->response->html("#product_list", $html);
		}
		$this->response->send();
	}

	public function admin_add_submit() {
		$product_id = $this->input->get_post('product_id');
		$qty = $this->input->get_post('qty');
		$order_id = $this->input->get_post('order_id');

		$product = $this->product_model->get($product_id);
		if ($product['return_code'] == API_SUCCESS && !empty($product['data']) && $product['data']['status'] == STATUS_ACTIVE) {
			$product = $product['data'];
			if ($product['sold_out'] == STATUS_ACTIVE) {
				$this->response->run("show_alert('" . lang('product_add_to_cart_failed') . "');");
			} else {
				$order_detail = $this->order_detail_model->get_where(array(
					'order_id' => $order_id,
					'product_id' => $product_id,
				));
				if ($order_detail['return_code'] == API_SUCCESS && !empty($order_detail['data'])) {
					$order_detail = array_shift($order_detail['data']);
					$this->order_detail_model->update($order_detail['order_detail_id'], array(
						'quantity' => ($order_detail['quantity'] + $qty),
						'price' => isset($product['price_off']) && !empty($product['price_off']) ? $product['price_off'] : $product['price'],
					));
				} else {
					$item_data = array(
						'order_id' => $order_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'price' => isset($product['price_off']) && !empty($product['price_off']) ? $product['price_off'] : $product['price'],
					);
					$this->order_detail_model->create($item_data);
				}
				$this->response->run('window.location = window.location;');
			}
		} else {
			$this->response->run("show_alert('" . lang('product_add_to_cart_failed') . "');");
		}
		$this->response->send();
	}

}
