<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Outer_Controller {

	private $product_per_page = 9;

	public function __construct() {
		parent::__construct();
		$this->load->model(array('product_model', 'product_photo_model', 'product_category_model', 'order_model', 'order_detail_model', 'customer_email_model'));
		$this->load->language('product');
		$this->load->config('product', TRUE);
		$this->load->library(array('cart'));
	}

	/**
	 * Trang chi tiết Sản phẩm
	 */
	public function detail() {
		$data = array();
		$product = $this->_get_product();
		$this->set_title($product['name_' . $this->_current_lang]);

		$category = $this->product_category_model->get($product['product_category_id']);
		if ($category['return_code'] == API_SUCCESS && !empty($category['data']) && $category['data']['status'] == STATUS_ACTIVE) {
			$category = $category['data'];
		} else {
			$category = array();
		}
		$product['category'] = $category;

		$product['photo_list'] = $this->_get_product_photos($product['product_id']);
		unset($product['display_order']);
		$data['product'] = $product;

		$this->load->view('product_view', $data);
	}

	public function _pagelet_checkout_view($first_time = FALSE) {
		$data = $this->_cart_content();
		if (empty($data['cart'])) {
			return FALSE;
		}
		$rules = array(
			array('field' => 'fullname', 'label' => lang('product_checkout_fullname'), 'rules' => 'trim|htmlspecialchars|strip_tags'),
			array('field' => 'email', 'label' => lang('product_checkout_email'), 'rules' => 'trim|valid_email'),
			array('field' => 'mobile', 'label' => lang('product_checkout_mobile'), 'rules' => 'trim|strip_tags|numeric|required'),
			array('field' => 'address', 'label' => lang('product_checkout_shipping_address'), 'rules' => 'trim|htmlspecialchars|strip_tags|required'),
			array('field' => 'delivery_time', 'label' => lang('product_order_delivery_time'), 'rules' => 'trim'),
			array('field' => 'note', 'label' => lang('product_checkout_note'), 'rules' => 'trim|htmlspecialchars|strip_tags'),
		);
		if (!$first_time) {
			$this->form_validation->set_rules($rules);
		}
		if ($this->form_validation->run()) {
			$order_data = array(
				'email' => $this->input->post('email'),
				'fullname' => $this->input->post('fullname'),
				'mobile' => $this->input->post('mobile'),
				'address' => $this->input->post('address'),
				'delivery_time' => $this->input->post('delivery_time'),
				'note' => $this->input->post('note'),
			);
			$order = $this->order_model->create($order_data);

			if ($order['return_code'] == API_SUCCESS && !empty($order['data'])) {
				$order = $order['data'];
				$product_list = array();

				foreach ($data['cart'] as $item) {
					$product = $this->product_model->get($item['id']);
					if ($product['return_code'] == API_SUCCESS && !empty($product['data']) && $product['data']['status'] == STATUS_ACTIVE) {
						$product = $product['data'];
						$product_list[$product['product_id']] = $product;
						$item_data = array(
							'order_id' => $order['order_id'],
							'product_id' => $item['id'],
							'quantity' => $item['qty'],
							'price' => isset($product['price_off']) && !empty($product['price_off']) ? $product['price_off'] : $product['price'],
						);
						$this->order_detail_model->create($item_data);
					}
				}
				$order_list = $this->order_detail_model->get_where(array('order_id' => $order['order_id']));
				if (!empty($order_data['email'])) {
					if ($order_list['return_code'] == API_SUCCESS && !empty($order_list['data'])) {
						$order_list = $order_list['data'];
						Modules::run('email/_send_order_email', $order, $order_list, $product_list);
					}
					$this->customer_email_model->create(array('email' => $order_data['email']));
				}
			}
			set_notice_message('success', lang('product_checkout_success'));
			$this->cart->destroy();
			return TRUE;
		}
		$this->load->view('pagelet_checkout_view', array());
	}

	/**
	 * Trang giỏ hàng
	 */
	public function cart() {
		$this->load->view('product_cart_view', array());
	}

	/**
	 * HTML phần view giỏ hàng
	 */
	public function _pagelet_cart_view() {
		$data = $this->_cart_content();
		return $this->load->view('pagelet_cart_view', $data, TRUE);
	}

	/**
	 * HTML item trong trang giỏ hàng
	 * @param type $item
	 */
	public function _pagelet_cart_item($item) {
		$item = $this->_cart_item($item);
		if (empty($item)) {
			return FALSE;
		}
		return $this->load->view('pagelet_cart_item', array('item' => $item), TRUE);
	}

	public function _pagelet_cart_edit($item) {
		$item = $this->_cart_item($item);

		if (empty($item)) {
			return FALSE;
		}
		return $this->load->view('pagelet_cart_edit', array('item' => $item), TRUE);
	}

	private function _get_product() {
		$product_id = $this->input->get_post('product_id');
		if ($product_id == FALSE || !is_numeric($product_id)) {
			show_404();
		} else {
			$product = $this->product_model->get($product_id);
			if ($product['return_code'] !== API_SUCCESS || empty($product['data']) || $product['data']['status'] !== STATUS_ACTIVE) {
				show_404();
			}
		}
		return $product['data'];
	}

	/**
	 * Lấy danh sách hình của Sản phẩm
	 * @param type $product_id
	 * @return type
	 */
	private function _get_product_photos($product_id) {
		$photo_list = $this->product_photo_model->get_where(array('product_id' => $product_id));
		return $photo_list['data'];
	}

	/**
	 * Tóm tắt thông tin giỏ hàng, hiển thị ở top bar
	 */
	public function _pagelet_cart_content() {
		$data = $this->_cart_content();
		foreach ($data['cart'] as &$item) {
			$item = $this->_cart_item($item);
		}
		$this->load->view('pagelet_cart_content', $data);
	}

	/**
	 * Lấy thông tin chi tiết của giỏ hàng
	 * @return type
	 */
	public function _cart_content() {
		$cart = $this->cart->contents();
		$total_price = 0;
		$total_product = 0;

		foreach ($cart as $rowid => &$item) {
			if (!isset($item['qty']) || !is_numeric($item['qty']) || $item['qty'] <= 0) {
				$item['qty'] = 0;
				$this->cart->update($item);
				unset($cart[$rowid]);
				continue;
			}
			$total_product += $item['qty'];
			$product = $this->product_model->get($item['id']);
			$product = $product['data'];
			$item['price'] = isset($product['price_off']) && !empty($product['price_off']) ? $product['price_off'] : $product['price'];
			$item['total_price'] = $item['price'] * $item['qty'];
			$total_price += $item['total_price'];
		}
		$data = array(
			'cart' => $cart,
			'total_price' => $total_price,
			'total_product' => $total_product,
		);
		return $data;
	}

	/**
	 * Lấy thông tin chi tiết 1 Sản phẩm trong giỏ hàng
	 * @param type $item
	 * @return array|boolean
	 */
	public function _cart_item($item) {
		if (!isset($item['id'])) {
			return FALSE;
		}

		$product = $this->product_model->get($item['id']);
		if ($product['return_code'] == API_SUCCESS && !empty($product['data']) && $product['data']['status'] == STATUS_ACTIVE) {
			$product = $product['data'];
			$item['price'] = isset($product['price_off']) && !empty($product['price_off']) ? $product['price_off'] : $product['price'];
			$item['total_price'] = $item['price'] * $item['qty'];

			$photo = $this->product_photo_model->get_where(array('product_id' => $product['product_id']));
			if ($photo['return_code'] == API_SUCCESS && !empty($photo['data'])) {
				$photo = $photo['data'];
				$photo = array_shift($photo);
				$product['photo'] = $photo;
			} else {
				$product['photo'] = array();
			}
			$category = $this->product_category_model->get($product['product_category_id']);
			if ($category['return_code'] == API_SUCCESS && !empty($category['data']) && $category['data']['status'] == STATUS_ACTIVE) {
				$category = $category['data'];
				$product['category'] = $category;
				$item['product'] = $product;
				return $item;
			} else {
				return FALSE;
			}
		}
		return FALSE;
	}

	public function _pagelet_hot_products() {
		$product_list = $this->product_model->get_where(array('hot' => STATUS_ACTIVE, 'status' => STATUS_ACTIVE, 'sold_out' => STATUS_INACTIVE));
		if ($product_list['return_code'] == API_SUCCESS && !empty($product_list['data'])) {
			$product_list = $product_list['data'];
		} else {
			$product_list = array();
		}

		$data['product_list'] = array();
		$category_list = array();
		foreach ($product_list as $product) {
			if (!isset($category_list[$product['product_category_id']])) {
				$category = $this->product_category_model->get($product['product_category_id']);
				if ($category['return_code'] == API_SUCCESS && !empty($category['data'])) {
					$category = $category['data'];
					$category_list[$category['product_category_id']] = $category;
				}
			}
			if (!isset($category_list[$product['product_category_id']])) {
				continue;
			}
			$product['category'] = $category_list[$product['product_category_id']];

			$photo = $this->product_photo_model->get_where(array('product_id' => $product['product_id']));
			if ($photo['return_code'] == API_SUCCESS && !empty($photo['data'])) {
				$photo = $photo['data'];
				$photo = array_shift($photo);
				$product['photo'] = Modules::run('photo/_get_photo_path', $photo['url'], 230);
				$data['product_list'][$product['product_id']] = $product;
			}
		}

		if (empty($data['product_list'])) {
			return FALSE;
		}
		return $this->load->view('pagelet_hot_products', $data, TRUE);
	}

	public function _pagelet_product_item($product) {
		$data = array(
			'product' => $product
		);
		return $this->load->view('pagelet_product_item', $data, TRUE);
	}

	public function _pagelet_suggest_product() {
		$product_list = $this->product_model->get_where(array(
			'status' => STATUS_ACTIVE,
			'sold_out' => STATUS_INACTIVE,
			'hot' => STATUS_INACTIVE,
		));
		if ($product_list['return_code'] == API_SUCCESS && !empty($product_list['data'])) {
			$product_list = $product_list['data'];
		} else {
			$product_list = array();
		}
		if (empty($product_list)) {
			return FALSE;
		}
		$product_list = array_reverse($product_list);

		$data['product_list'] = array();
		$category_list = array();
		foreach ($product_list as $product) {
			if (!isset($category_list[$product['product_category_id']])) {
				$category = $this->product_category_model->get($product['product_category_id']);
				if ($category['return_code'] == API_SUCCESS && !empty($category['data'])) {
					$category = $category['data'];
					$category_list[$category['product_category_id']] = $category;
				}
			}
			if (!isset($category_list[$product['product_category_id']])) {
				continue;
			}
			$product['category'] = $category_list[$product['product_category_id']];

			$photo = $this->product_photo_model->get_where(array('product_id' => $product['product_id']));
			if ($photo['return_code'] == API_SUCCESS && !empty($photo['data'])) {
				$photo = $photo['data'];
				$photo = array_shift($photo);
				$product['photo'] = Modules::run('photo/_get_photo_path', $photo['url'], 230);
				$data['product_list'][$product['product_id']] = $product;
			}

			if (count($data['product_list']) >= $this->product_per_page) {
				break;
			}
		}
		return $this->load->view('pagelet_suggest_product', $data, TRUE);
	}

}
