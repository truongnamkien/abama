<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Admin_Controller {

	const LIMIT_PER_PAGE = 10;

	public function __construct() {
		parent::__construct();
		$this->my_auth->login_required();
		$this->load->model(array('order_model', 'order_detail_model', 'product_model', 'product_category_model'));
		$this->load->language(array('product'));
		$this->set_title(lang('manager_title') . ' - ' . lang('product_order_manager'));
	}

	/**
	 * Trang danh sách đơn đặt hàng
	 * @param type $status
	 */
	public function index($offset = 0, $status = Order_Model::ORDER_STATUS_PENDING) {
		$status_list = array(
			Order_Model::ORDER_STATUS_PENDING,
			Order_Model::ORDER_STATUS_PROGRESSING,
			Order_Model::ORDER_STATUS_FINISHED,
			Order_Model::ORDER_STATUS_CANCELLED,
		);

		$posts = $this->input->post();
		$posts = empty($posts) ? array() : $posts;
		$gets = $this->input->get();
		$gets = empty($gets) ? array() : $gets;
		$inputs = array_merge($posts, $gets);

		$keyword = isset($inputs['keyword']) ? $inputs['keyword'] : '';
		$fields = array('email', 'fullname', 'mobile');

		$filter = array();
		if ($keyword !== '' && !empty($fields)) {
			foreach ($fields as $field) {
				$filter[] = $field . ' LIKE \'%' . $keyword . '%\'';
			}
			if (!empty($filter)) {
				$filter = '(' . implode(' OR ', $filter) . ')';
			}
			$filter .= ' AND ';
		} else {
			$filter = '';
		}
		$filter .= 'status = "' . $status . '"';

		$limit = self::LIMIT_PER_PAGE;
		$orders = $this->order_model->get_where($filter, 'created_at');
		if ($orders['return_code'] == API_SUCCESS && !empty($orders['data'])) {
			$orders = $orders['data'];
		} else {
			$orders = array();
		}
		$total = count($orders);
		$orders = array_slice($orders, $offset, $limit);

		$config['base_url'] = site_url('admin/order/index/');
		$config['suffix'] = '/' . $status . '?keyword=' . $keyword;
		$config['total_rows'] = $total;
		$config['per_page'] = $limit;
		$config['first_url'] = site_url('admin/order/index/0/' . $status . '?keyword=' . $keyword);
		$config['uri_segment'] = '4';
		$config['anchor_class'] = 'class="number"';
		$config['first_link'] = lang('admin_first_link');
		$config['last_link'] = lang('admin_last_link');
		$config['next_link'] = lang('admin_next_link');
		$config['prev_link'] = lang('admin_prev_link');
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);

		$data = array(
			'orders' => $orders,
			'status_list' => $status_list,
			'current_status' => $status,
			'pagination' => TRUE,
			'keyword' => $keyword
		);
		$this->load->view('order/order_list_view', $data);
	}

	/**
	 * Trang chi tiết đơn hàng
	 */
	public function detail() {
		$order_id = $this->input->get_post('order_id');
		$order = $this->order_model->get($order_id);
		$data = array('status_list' => array());
		if ($order['return_code'] != API_SUCCESS || empty($order['data'])) {
			set_notice_message('danger', lang('empty_list_data'));
			redirect('admin/order');
		}
		$data['order'] = $order = $order['data'];
		$data['status_list'] = $this->_get_status_list($order['status']);
		$this->load->view('order/order_detail_view', $data);
	}

	public function _pagelet_order_add_button($status = Order_Model::ORDER_STATUS_PENDING) {
		if ($status != Order_Model::ORDER_STATUS_PENDING) {
			return FALSE;
		}
		return $this->load->view('order/pagelet_order_add_button', array(), TRUE);
	}

	public function _pagelet_order_add_product($category_id) {
		$category = $this->product_category_model->get($category_id);
		if ($category['return_code'] == API_SUCCESS && !empty($category['data'])) {
			$category = $category['data'];
			$product_list = Modules::run('category/_get_product_list', $category);
		} else {
			$category = array();
			$product_list = array();
		}
		$data = array();
		foreach ($product_list as $product) {
			$data['product_list'][$product['product_id']] = $product['name_' . $this->_current_lang];
		}

		return $this->load->view('order/pagelet_order_add_product', $data, TRUE);
	}

	public function _pagelet_order_add_row() {
		$category_list = $this->product_category_model->get_where(array('status' => STATUS_ACTIVE), 'display_order');
		if ($category_list['return_code'] == API_SUCCESS && !empty($category_list['data'])) {
			$category_list = $category_list['data'];
		} else {
			$category_list = array();
		}
		$data = array();
		foreach ($category_list as $category) {
			$data['category_list'][$category['product_category_id']] = $category['category_name_' . $this->_current_lang];
		}

		return $this->load->view('order/pagelet_order_add_row', $data, TRUE);
	}

	/**
	 * Remove 1 Sản phẩm ra khỏi đơn hàng
	 * @param type $detail_id
	 */
	public function remove_order($order_id, $detail_id) {
		$this->order_detail_model->update($detail_id, array('status' => STATUS_INACTIVE));
		set_notice_message('success', lang('admin_remove_success'));
		redirect('admin/order/detail?order_id=' . $order_id);
	}

	/**
	 * Đổi trạng thái đơn hàng
	 * @param type $order_id
	 * @param type $status
	 */
	public function update_status($order_id, $status) {
		$order = $this->order_model->get($order_id);
		$ret = FALSE;
		if ($order['return_code'] == API_SUCCESS && !empty($order['data'])) {
			$order = $order['data'];
			$status_list = $this->_get_status_list($order['status']);
			if (in_array($status, $status_list)) {
				if ($order['status'] == Order_Model::ORDER_STATUS_PENDING && $status == Order_Model::ORDER_STATUS_PROGRESSING) {
					$order_list = $this->order_detail_model->get_where(array('order_id' => $order_id));
					if ($order_list['return_code'] == API_SUCCESS && !empty($order_list['data'])) {
						$order_list = $order_list['data'];
					} else {
						$order_list = array();
					}

					$product_list = array();
					foreach ($order_list as $item) {
						$product = $this->product_model->get($item['product_id']);
						if ($product['return_code'] == API_SUCCESS && !empty($product['data']) && $product['data']['status'] == STATUS_ACTIVE) {
							$product = $product['data'];
							$product_list[$product['product_id']] = $product;
						}
					}

					if (!empty($order['email'])) {
						Modules::run('email/_send_order_email', $order, $order_list, $options, $product_list);
					}
				}

				set_notice_message('success', lang('admin_update_success'));
				$ret = TRUE;
				$this->order_model->update($order_id, array('status' => $status));
			}
		}
		if (!$ret) {
			set_notice_message('danger', lang('admin_update_error'));
		}
		redirect('admin/order/index/0/' . $status);
	}

	private function _get_status_list($status = FALSE) {
		$status_list = array();
		if ($status == FALSE) {
			$status_list[] = Order_Model::ORDER_STATUS_PENDING;
		} else {
			$status_list[] = $status;
		}
		if ($status == Order_Model::ORDER_STATUS_PENDING) {
			$status_list[] = Order_Model::ORDER_STATUS_PROGRESSING;
			$status_list[] = Order_Model::ORDER_STATUS_CANCELLED;
		} else if ($status == Order_Model::ORDER_STATUS_PROGRESSING) {
			$status_list[] = Order_Model::ORDER_STATUS_FINISHED;
			$status_list[] = Order_Model::ORDER_STATUS_CANCELLED;
		}
		return $status_list;
	}

	public function _pagelet_order_view($order_id) {
		$total_price = 0;
		$order_detail = $this->order_detail_model->get_where(array('order_id' => $order_id));
		$data = array();
		if ($order_detail['return_code'] == API_SUCCESS && !empty($order_detail['data'])) {
			$order_detail = $order_detail['data'];
			foreach ($order_detail as $index => &$detail) {
				$total_price += $detail['price'] * $detail['quantity'];
				$product = $this->product_model->get($detail['product_id']);
				if ($product['return_code'] != API_SUCCESS || empty($product['data'])) {
					unset($order_detail[$index]);
				}
				$detail['product'] = $product['data'];
			}
		} else {
			$order_detail = array();
		}
		$data['order_detail'] = $order_detail;

		$order = $this->order_model->get($order_id);
		if ($order['return_code'] !== API_SUCCESS || empty($order['data'])) {
			set_notice_message('danger', lang('empty_list_data'));
			redirect(site_url('admin/order'));
		}
		$order = $order['data'];
		$data['discount'] = $order['discount'];
		$data['discount_note'] = $order['discount_note'];
		$data['status'] = $order['status'];
		$data['total_price'] = $total_price;

		return $this->load->view('order/pagelet_order_view', $data, TRUE);
	}

	public function _pagelet_order_item($item, $status) {
		return $this->load->view('order/pagelet_order_item', array('item' => $item, 'status' => $status), TRUE);
	}

	public function _pagelet_order_edit($item) {
		return $this->load->view('order/pagelet_order_edit', array('item' => $item), TRUE);
	}

	public function update($order_id) {
		$rules = array(
			array('field' => 'discount', 'label' => lang('product_order_discount'), 'rules' => 'trim|numeric'),
			array('field' => 'discount_note', 'label' => lang('product_order_discount_note'), 'rules' => 'trim'),
		);
		$this->form_validation->set_rules($rules);
		$order = $this->order_model->get($order_id);
		if ($order['return_code'] !== API_SUCCESS || empty($order['data'])) {
			set_notice_message('danger', lang('empty_list_data'));
			redirect(site_url('admin/order'));
		}
		$order = $order['data'];

		if ($this->form_validation->run()) {
			$this->order_model->update($order_id, array(
				'discount' => $this->input->post('discount'),
				'discount_note' => $this->input->post('discount_note'),
			));
			set_notice_message('success', lang('admin_update_success'));
		}
		if ($message_error = validation_errors()) {
			set_notice_message('danger', $message_error);
		}
		redirect('admin/order/detail?order_id=' . $order_id);
	}

	public function export() {
		$rules = array(
			array('field' => 'from_time', 'label' => lang('product_order_export_from_time'), 'rules' => 'trim|required'),
			array('field' => 'to_time', 'label' => lang('product_order_export_to_time'), 'rules' => 'trim|required'),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$from_time = strtotime(str_replace('/', '-', $this->input->post('from_time')));
			$to_time = strtotime(str_replace('/', '-', $this->input->post('to_time'))) + 86400;

			$order_list = $this->order_model->get_where('created_at > ' . $from_time . ' AND created_at <= ' . $to_time);
			if ($order_list['return_code'] == API_SUCCESS && !empty($order_list['data'])) {
				$order_list = $order_list['data'];
			} else {
				$order_list = array();
			}

			if (empty($order_list)) {
				set_notice_message('danger', lang('empty_list_data'));
				redirect('admin/order/export');
			}
			$this->load->library('excel_xml');
			$this->excel_xml->addRow(array(lang('product_order_manager')));
			$this->excel_xml->addRow(array(lang('product_order_export_from_time'), date('d/m/Y', $from_time)));
			$this->excel_xml->addRow(array(lang('product_order_export_to_time'), date('d/m/Y', $to_time)));
			foreach ($order_list as $index => $order) {
				if (empty($index)) {
					$title_list = array();
					foreach ($order as $key => $value) {
						$title_list[] = lang('product_order_' . $key);
					}
					$title_list[] = lang('product_shopping_cart_total_price');
					$this->excel_xml->addRow($title_list);
				}
				$order['created_at'] = date('d/m/Y H:i:s', $order['created_at']);
				$order['status'] = lang('product_order_status_' . $order['status']);
				$detail_list = $this->order_detail_model->get_where(array('order_id' => $order['order_id']));
				if ($detail_list['return_code'] == API_SUCCESS && !empty($detail_list['data'])) {
					$detail_list = $detail_list['data'];
				} else {
					$detail_list = array();
				}
				$order['total_price'] = 0;
				foreach ($detail_list as $detail) {
					$order['total_price'] += $detail['quantity'] * $detail['price'];
				}
				$this->excel_xml->addRow($order);
			}
			$this->disable_masterview();
			$this->excel_xml->generateXML('order_list_' . date('Ymd', $from_time) . '_' . date('Ymd', $to_time));
			return FALSE;
		}
		$data = array(
			'status_list' => array(
				Order_Model::ORDER_STATUS_PENDING,
				Order_Model::ORDER_STATUS_PROGRESSING,
				Order_Model::ORDER_STATUS_FINISHED,
				Order_Model::ORDER_STATUS_CANCELLED,
			)
		);
		$this->load->view('order/frm_export', $data);
	}

}
