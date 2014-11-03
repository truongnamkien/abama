<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Navigator extends MY_Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->my_auth->login_required();
	}

	public function _main_nav() {
		$data['main_navs'] = array(
			'content' => $this->_content_sub_nav(),
			'product' => $this->_product_sub_nav(),
			'user' => $this->_user_sub_nav(),
			'config' => $this->_config_sub_nav(),
		);
		$controller = $this->router->fetch_class();
		$action = $this->router->fetch_method();
		if ($action == 'config' && $controller == 'home') {
			$controller = 'config';
		}
		$data['controller'] = $controller;

		$this->load->view('navigator/main_nav_view', $data);
	}

	private function _content_sub_nav() {
		return array('navs' => array(
				'home' => array(
					'url' => site_url('admin/home'),
					'icon' => 'home',
				),
				'page' => array(
					'url' => site_url('admin/page'),
					'icon' => 'book',
				),
				'content' => array(
					'url' => site_url('admin/content'),
					'icon' => 'bookmark',
				),
				'blog_category' => array(
					'url' => site_url('admin/blog_category'),
					'icon' => 'list-alt',
				),
				'blog' => array(
					'url' => site_url('admin/blog'),
					'icon' => 'file',
				),
				'blog_content' => array(
					'url' => site_url('admin/blog_content'),
					'icon' => 'pencil',
				),
		));
	}

	private function _config_sub_nav() {
		return array('navs' => array(
				'config' => array(
					'url' => site_url('admin/config'),
					'icon' => 'wrench',
				),
		));
	}

	private function _product_sub_nav() {
		return array('navs' => array(
				'branch' => array(
					'url' => site_url('admin/branch'),
					'icon' => 'qrcode',
				),
				'product_category' => array(
					'url' => site_url('admin/product_category'),
					'icon' => 'list',
				),
				'product' => array(
					'url' => site_url('admin/product'),
					'icon' => 'glass',
				),
				'order' => array(
					'url' => site_url('admin/order'),
					'icon' => 'shopping-cart',
				),
		));
	}

	private function _user_sub_nav() {
		return array('navs' => array(
				'recruitment' => array(
					'url' => site_url('admin/recruitment'),
					'icon' => 'asterisk',
				),
				'recruitment_application' => array(
					'url' => site_url('admin/recruitment_application'),
					'icon' => 'folder-close',
				),
				'customer_email' => array(
					'url' => site_url('admin/customer_email'),
					'icon' => 'user',
				),
				'contact' => array(
					'url' => site_url('admin/contact'),
					'icon' => 'envelope',
				),
		));
	}

	public function _pagelet_admin_navigator() {
		return $this->load->view('navigator/pagelet_admin_navigator', array(), TRUE);
	}

	public function _pagelet_theme_selector() {
		return $this->load->view('navigator/pagelet_theme_selector', array(), TRUE);
	}

	public function _pagelet_support_selector() {
		$data = array(
			'constructor' => Modules::run('construction/_static_content', 'construct', 'config')
		);
		return $this->load->view('navigator/pagelet_support_selector', $data, TRUE);
	}

}
