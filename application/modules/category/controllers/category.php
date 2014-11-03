<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Outer_Controller {

	private $product_per_page = 9;

	public function __construct() {
		parent::__construct();
		$this->load->language(array('product_category', 'product'));
		$this->load->model(array('product_model', 'product_category_model', 'product_photo_model'));
	}

	public function detail() {
		$data = array();
		$offset = 0;
		$category = $this->_get_product_category();
		$this->set_title($category['category_name_' . $this->_current_lang]);

		$data['offset'] = $offset;
		$data['category'] = $category;
		$this->load->view('category_view', $data);
	}

	public function _product_list($category_id, $offset = 0) {
		$category = $this->product_category_model->get($category_id);
		if ($category['return_code'] !== API_SUCCESS || empty($category['data']) || $category['data']['status'] !== STATUS_ACTIVE) {
			return FALSE;
		}
		$category = $category['data'];

		$product_list = $this->_get_product_list($category);
		if (empty($product_list)) {
			$product_list = array();
		}

		// Nếu là Category cấp 1 thì load thêm sub category
		if (empty($category['parent_id'])) {
			$sub_categories = $this->product_category_model->get_where(array('parent_id' => $category['product_category_id'], 'status' => STATUS_ACTIVE));
			if ($sub_categories['return_code'] == API_SUCCESS && !empty($sub_categories['data'])) {
				$sub_categories = $sub_categories['data'];
			} else {
				$sub_categories = array();
			}

			foreach ($sub_categories as $sub_category) {
				$sub_product_list = $this->_get_product_list($sub_category);
				if (!empty($sub_product_list)) {
					$product_list = array_merge($product_list, $sub_product_list);
				}
			}
		}
        function compare_display_order($p1, $p2) {
            return $p1['display_order'] < $p2['display_order'];
        }
        usort($product_list, 'compare_display_order');

		$product_list = array_slice($product_list, $offset * $this->product_per_page, $this->product_per_page);
		$data = array(
			'offset' => $offset
		);
		$ret = array();
		foreach ($product_list as &$product) {
			$photo = $this->product_photo_model->get_where(array('product_id' => $product['product_id']));
			if ($photo['return_code'] == API_SUCCESS && !empty($photo['data'])) {
				$photo = $photo['data'];
				$photo = array_shift($photo);
				$product['photo'] = Modules::run('photo/_get_photo_path', $photo['url'], 196);
				$ret[] = $product;
			}
		}
		if ($offset > 0 && empty($ret)) {
			return FALSE;
		}

		$data['category'] = $category;
		$data['product_list'] = $ret;
		$this->load->view('pagelet_product_list', $data);
	}

	private function _get_product_category() {
		$category_id = $this->input->get_post('category_id');

		if ($category_id == FALSE || !is_numeric($category_id)) {
			show_404();
		} else {
			$category = $this->product_category_model->get($category_id);
			if ($category['return_code'] !== API_SUCCESS || empty($category['data']) || $category['data']['status'] !== STATUS_ACTIVE) {
				show_404();
			}
		}
		return $category['data'];
	}

	public function _get_product_list($category) {
		$product_list = $this->product_model->get_where(array('product_category_id' => $category['product_category_id'], 'status' => STATUS_ACTIVE), 'display_order');
		if ($product_list['return_code'] == API_SUCCESS && !empty($product_list['data'])) {
			$product_list = $product_list['data'];
		} else {
			$product_list = array();
		}
		foreach ($product_list as &$product) {
			$product['category'] = $category;
		}
		return $product_list;
	}

	public function _pagelet_category_list($position = 'top') {
		$parent_categories = $this->product_category_model->get_where('(parent_id IS NULL OR parent_id = 0) AND status = "' . STATUS_ACTIVE . '"', 'display_order');
		if ($parent_categories['return_code'] == API_SUCCESS && !empty($parent_categories['data'])) {
			$parent_categories = $parent_categories['data'];
		} else {
			$parent_categories = array();
		}

		foreach ($parent_categories as &$category) {
			$sub_categories = $this->product_category_model->get_where(array('parent_id' => $category['product_category_id'], 'status' => STATUS_ACTIVE), 'display_order');
			if ($sub_categories['return_code'] == API_SUCCESS && !empty($sub_categories['data'])) {
				$sub_categories = $sub_categories['data'];
			} else {
				$sub_categories = array();
			}
			$category['sub_categories'] = $sub_categories;
		}
		$data = array(
			'category_list' => $parent_categories,
			'position' => $position,
		);
		return $this->load->view('pagelet_category_list', $data, TRUE);
	}

	public function _pagelet_sub_category_list($category) {
		if (isset($category['parent_id']) && !empty($category['parent_id'])) {
			$filter = array(
				'parent_id' => $category['parent_id'],
				'status' => STATUS_ACTIVE
			);
		} else {
			$filter = array(
				'parent_id' => $category['product_category_id'],
				'status' => STATUS_ACTIVE
			);
		}
		$category_list = $this->product_category_model->get_where($filter, 'display_order');
		if ($category_list['return_code'] == API_SUCCESS && !empty($category_list['data'])) {
			$category_list = $category_list['data'];
		} else {
			$category_list = array();
		}
		if (empty($category_list)) {
			return FALSE;
		}
		
		$data = array(
			'category' => $category,
			'category_list' => $category_list
		);
		return $this->load->view('pagelet_sub_category_list', $data, TRUE);
	}

}
