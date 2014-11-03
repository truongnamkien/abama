<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Navigator extends MY_Controller {

	public static $controller = FALSE;
	public static $method = FALSE;

	public function __construct() {
		parent::__construct();
		$this->load->model(array('page_model', 'product_model', 'product_category_model', 'product_photo_model', 'blog_category_model', 'content_model', 'blog_content_model', 'keyword_model'));
		$this->load->language(array('product', 'static_content', 'blog', 'contact', 'recruitment'));
		$this->load->config('content_page', TRUE);
		self::$controller = $this->router->fetch_class();
		self::$method = $this->router->fetch_method();
	}

	public function _pagelet_main_menu($pos = 'top') {
		$top_nav = $this->_main_page_nav();
		if ($pos == 'top') {
			$sub_nav = $this->_product_nav();
			$nav = current($sub_nav);
			$top_nav[lang('product_product')] = array(
				'url' => ($pos == 'top' ? '#' : $nav['url']),
				'class' => ((self::$controller == 'category' || self::$controller == 'product') && self::$method == 'detail' ? 'selected' : '') . ' ' . ($pos == 'top' ? 'fake' : ''),
				'sub_nav' => $sub_nav,
			);
			$sub_nav = $this->_blog_nav();
			$nav = current($sub_nav);
			$top_nav[lang('blog_blog')] = array(
				'url' => ($pos == 'top' ? '#' : $nav['url']),
				'class' => (self::$controller == 'blog' ? 'selected' : '') . ' ' . ($pos == 'top' ? 'fake' : ''),
				'sub_nav' => $sub_nav,
			);
		}
		$top_nav = array_merge($top_nav, $this->_optional_page_nav());
		$top_nav[lang('recruitment_recruitment')] = array(
			'url' => site_url('recruitment'),
			'class' => (self::$controller == 'recruitment' ? 'selected' : ''),
		);
		$top_nav[lang('contact_contact')] = array(
			'url' => site_url('contact'),
			'class' => (self::$controller == 'contact' ? 'selected' : ''),
		);
		$this->load->view('pagelet_main_menu', array('top_nav' => $top_nav, 'pos' => $pos));
	}

	private function _blog_nav() {
		$category_list = $this->blog_category_model->get_all();
		if ($category_list['return_code'] == API_SUCCESS && !empty($category_list['data'])) {
			$category_list = $category_list['data'];
		} else {
			$category_list = array();
		}

		$sub_list = array();
		foreach ($category_list as $category) {
			$sub_list[$category['name_' . $this->_current_lang]] = array(
				'url' => blog_category_url($category),
				'class' => '',
			);
		}
		return $sub_list;
	}

	private function _product_nav() {
		$parent_categories = $this->product_category_model->get_where('(parent_id IS NULL OR parent_id = 0) AND status = "' . STATUS_ACTIVE . '"', 'display_order');
		if ($parent_categories['return_code'] == API_SUCCESS && !empty($parent_categories['data'])) {
			$parent_categories = $parent_categories['data'];
		} else {
			$parent_categories = array();
		}

		$page_nav = array();
		foreach ($parent_categories as $category) {
			$sub_categories = $this->product_category_model->get_where(array('parent_id' => $category['product_category_id'], 'status' => STATUS_ACTIVE), 'display_order');
			$sub_list = array();
			$current_main = FALSE;
			if ($sub_categories['return_code'] == API_SUCCESS && !empty($sub_categories['data'])) {
				$sub_categories = $sub_categories['data'];
				foreach ($sub_categories as $sub_category) {
					$current_sub = (self::$controller == 'category' && self::$method == 'detail' && $this->input->get('id') == $sub_category['product_category_id']);
					if ($current_sub) {
						$current_main = TRUE;
					}
					$sub_list[$sub_category['category_name_' . $this->_current_lang]] = array(
						'url' => product_category_url($sub_category),
						'class' => ($current_sub ? 'selected' : ''),
					);
				}
			}

			$page_nav[$category['category_name_' . $this->_current_lang]] = array(
				'url' => product_category_url($category),
				'sub_nav' => $sub_list,
				'class' => ($current_main || (self::$controller == 'category' && self::$method == 'detail' && $this->input->get('id') == $category['product_category_id']) ? 'selected' : ''),
			);
		}
		return $page_nav;
	}

	/**
	 * Menu item của các trang admin quản lý
	 * @return type
	 */
	private function _optional_page_nav() {
		$page_list = $this->page_model->get_where("status = \"" . STATUS_ACTIVE . "\" AND (parent_id IS NULL OR parent_id = 0)", 'display_order');
		$page_nav = array();
		if ($page_list['return_code'] == API_SUCCESS && !empty($page_list['data'])) {
			$page_list = $page_list['data'];
			foreach ($page_list as $page) {
				$sub_pages = $this->page_model->get_where(array('parent_id' => $page['page_id'], 'status' => STATUS_ACTIVE), 'display_order');
				$sub_list = array();
				$current_main = FALSE;
				if ($sub_pages['return_code'] == API_SUCCESS && !empty($sub_pages['data'])) {
					$sub_pages = $sub_pages['data'];
					foreach ($sub_pages as $sub_page) {
						$current_sub = (self::$controller == 'page' && self::$method == 'detail' && $this->input->get('id') == $sub_page['page_id']);
						if ($current_sub) {
							$current_main = TRUE;
						}
						$sub_list[$sub_page['name_' . $this->_current_lang]] = array(
							'url' => page_url($sub_page),
							'class' => ($current_sub ? 'selected' : ''),
						);
					}
				}

				$page_nav[$page['name_' . $this->_current_lang]] = array(
					'url' => page_url($page),
					'sub_nav' => $sub_list,
					'class' => ($current_main || (self::$controller == 'page' && self::$method == 'detail' && $this->input->get('id') == $page['page_id']) ? 'selected' : ''),
				);
			}
		}
		return $page_nav;
	}

	/**
	 * Menu item của các trang static
	 * @return type
	 */
	private function _main_page_nav() {
		$pages = $this->config->item('page_static_pages', 'content_page');
		$main_nav = array();
		foreach ($pages as $page => $url) {
			$main_nav[lang($page)] = array(
				'url' => site_url($url['controller'] . '/' . $url['method']),
				'class' => (self::$controller == $url['controller'] && self::$method == $url['method'] ? 'selected' : ''),
			);
		}
		return $main_nav;
	}

	/**
	 * Add meta tag photo src và description trên head tag
	 * @return type
	 */
	public function _pagelet_meta_tag() {
		$url = FALSE;
		$img_widths = $this->config->item('page_content_photo_default_width', 'content_page');
		$description = FALSE;
		$photo_path = FALSE;
		if ($category_id = $this->input->get_post('blog_category_id')) {
			$url = blog_category_url($category_id);
		} else if ($category_id = $this->input->get_post('category_id')) {
			$url = product_category_url($category_id);
			$target_type = 'product_category';
			$target_id = $category_id;
			$category = $this->product_category_model->get($category_id);
			if ($category['return_code'] == API_SUCCESS && !empty($category['data'])) {
				$category = $category['data'];
				$photo_path = Modules::run('photo/_get_photo_path', $category['url'], 470);
			}
		} else if ($product_id = $this->input->get_post('product_id')) {
			$url = product_url($product_id);
			$target_type = 'product';
			$target_id = $product_id;
			$product = $this->product_model->get($product_id);
			if ($product['return_code'] == API_SUCCESS && !empty($product['data'])) {
				$product = $product['data'];
				$description = character_limiter(strip_tags($product['description_' . $this->_current_lang]), 100);
			}
			$photo_list = $this->product_photo_model->get_where(array('product_id' => $product_id));
			if ($photo_list['return_code'] == API_SUCCESS && !empty($photo_list['data'])) {
				$photo_list = $photo_list['data'];
				$photo_path = array_rand($photo_list);
				$photo_path = $photo_list[$photo_path];
				$photo_path = Modules::run('photo/_get_photo_path', $photo_path['url'], 470);
			}
		} else if ($page_id = $this->input->get_post('page_id')) {
			$url = page_url($page_id);
			$target_type = 'page';
			$target_id = $page_id;
			$contents = $this->content_model->get_where(array('page_id' => $page_id), 'display_order');
			if ($contents['return_code'] == API_SUCCESS && !empty($contents['data'])) {
				$contents = $contents['data'];
			} else {
				$contents = array();
			}

			foreach ($contents as $content) {
				if (empty($description) && $content['layout'] < count($img_widths)) {
					$description = character_limiter(strip_tags($content['content_' . $this->_current_lang]), 100);
				}
				if (empty($photo_path) && $content['layout'] < 0 && !empty($content['url'])) {
					$photo_path = Modules::run('photo/_get_photo_path', $content['url'], 470);
				}
				if (!empty($description) && !empty($photo_path)) {
					break;
				}
			}
		} else if ($blog_id = $this->input->get_post('blog_id')) {
			$url = blog_url($blog_id);
			$target_type = 'blog';
			$target_id = $blog_id;
			$contents = $this->blog_content_model->get_where(array('blog_id' => $blog_id), 'display_order');
			if ($contents['return_code'] == API_SUCCESS && !empty($contents['data'])) {
				$contents = $contents['data'];
			} else {
				$contents = array();
			}

			foreach ($contents as $content) {
				if (empty($description) && $content['layout'] < count($img_widths)) {
					$description = character_limiter(strip_tags($content['content_' . $this->_current_lang]), 100);
				}
				if (empty($photo_path) && $content['layout'] < 0 && !empty($content['url'])) {
					$photo_path = Modules::run('photo/_get_photo_path', $content['url'], 470);
				}
				if (!empty($description) && !empty($photo_path)) {
					break;
				}
			}
		}
		if (isset($target_id) && isset($target_type)) {
			$keyword_info = $this->keyword_model->get_where(array('target_type' => $target_type, 'target_id' => $target_id));
			if ($keyword_info['return_code'] == API_SUCCESS && !empty($keyword_info['data'])) {
				$keyword_info = array_shift($keyword_info['data']);
			} else {
				$keyword_info = array();
			}
		}

		$data = array(
			'url' => $url,
			'keyword' => Modules::run('construction/_static_content', 'keyword', 'config') . (isset($keyword_info) && !empty($keyword_info) ? ', ' . $keyword_info['content'] : ''),
			'description' => (isset($description) && !empty($description) ? $description : Modules::run('construction/_static_content', 'description', 'config')),
			'photo_path' => (isset($photo_path) && !empty($photo_path) ? base_url($photo_path) : asset_url('images/watermark.png')),
			'display' => $this->input->get_post('display'),
		);
		return $this->load->view('pagelet_meta_tag', $data, TRUE);
	}

	/**
	 * HTML các kênh liên lạc ở footer
	 */
	public function _pagelet_connect($position = 'side') {
		$data = array(
			'support' => Modules::run('construction/_static_content', 'facebook_page', 'config'),
			'position' => $position,
		);
		if (empty($data['support'])) {
			return FALSE;
		}
		return $this->load->view('pagelet_connect', $data, TRUE);
	}

	/**
	 * HTML phần scroll to Top
	 * @return type
	 */
	public function _pagelet_back_top() {
		return $this->load->view('pagelet_back_top', array(), TRUE);
	}

	public function _pagelet_contact() {
		$data = array(
			'email' => FALSE,
			'mobile' => FALSE,
			'facebook_page' => FALSE,
		);
		foreach ($data as $key => &$value) {
			$value = Modules::run('construction/_static_content', $key, 'config');
		}

		return $this->load->view('pagelet_contact', $data, TRUE);
	}

	public function _pagelet_footer() {
		$data = array(
			'email' => FALSE,
			'mobile' => FALSE,
			'facebook_page' => FALSE,
		);
		foreach ($data as $key => &$value) {
			$value = Modules::run('construction/_static_content', $key, 'config');
		}

		return $this->load->view('pagelet_footer', $data, TRUE);
	}

	public function _pagelet_subscribe() {
		return $this->load->view('pagelet_subscribe', array(), TRUE);
	}

	public function _pagelet_communication() {
		$data = array(
			'email' => FALSE,
			'mobile' => FALSE,
			'yahoo' => FALSE,
			'skype' => FALSE,
		);
		foreach ($data as $key => &$value) {
			$value = Modules::run('construction/_static_content', $key, 'config');
		}
		return $this->load->view('pagelet_communication', $data, TRUE);
	}

	public function _pagelet_header_bar() {
		$data = array(
			'header_bar' => Modules::run('construction/_static_content', 'header_bar', 'config'),
		);
		return $this->load->view('pagelet_header_bar', $data, TRUE);
	}

	public function _pagelet_hotline() {
		$data = array(
			'mobile' => Modules::run('construction/_static_content', 'mobile', 'config'),
		);
		return $this->load->view('pagelet_hotline', $data, TRUE);
	}

}
