<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recruitment_Application extends MY_Inner_Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->data['type'] = 'recruitment_application';
		$this->data['has_order'] = FALSE;
		$this->data['has_activation'] = FALSE;
		$this->load->model(array('recruitment_application_model', 'recruitment_model'));
		$this->load->language(array('recruitment_application'));
		$this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));

		$this->data['search_fields'] = array('position');
	}

	protected function prepare_object($id = FALSE) {
		return FALSE;
	}

	protected function create_update($id = FALSE) {
		redirect('admin/recruitment_application');
	}

	protected function get_object($id = FALSE, $parsed = FALSE) {
		$object = parent::get_object($id, $parsed);
		if ($parsed) {
			$object['created_at'] = date('d/m/Y H:i:s', $object['created_at']);
			$recruitment = $this->recruitment_model->get($object['recruitment_id']);
			if ($recruitment['return_code'] == API_SUCCESS && !empty($recruitment['data'])) {
				$object['recruitment_id'] = '<a target="_blank" href="' . site_url('admin/recruitment/show/' . $object['recruitment_id']) . '">' . $recruitment['data']['position'] . '</a>';
			}
			$object['url'] = '<a class="btn btn-info" href="' . site_url('admin/recruitment_application/download/' . $id) . '" title="' . lang('admin_action_download') . '"><i class="glyphicon glyphicon-download-alt icon-white"></i></a>';
		}
		return $object;
	}

	protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
		$result = parent::get_all_objects($filter, $offset);

		$recruitment_list = array();
		foreach ($result['objects'] as &$object) {
			$object['created_at'] = date('d/m/Y H:i:s', $object['created_at']);
			unset($object['content']);
			unset($object['url']);
			if (!isset($recruitment_list[$object['recruitment_id']])) {
				$recruitment = $this->recruitment_model->get($object['recruitment_id']);
				if ($recruitment['return_code'] == API_SUCCESS && !empty($recruitment['data'])) {
					$recruitment_list[$object['recruitment_id']] = $recruitment['data']['position'];
				}
			}
			if (isset($recruitment_list[$object['recruitment_id']])) {
				$object['recruitment_id'] = '<a target="_blank" href="' . site_url('admin/recruitment/show/' . $object['recruitment_id']) . '">' . $recruitment_list[$object['recruitment_id']] . '</a>';
			}
		}
		return $result;
	}

	protected function set_actions($id) {
		$actions = parent::set_actions($id);
		unset($actions['update']);
		$actions['download'] = array(
			'url' => site_url('admin/recruitment_application/download/' . $id),
			'button' => 'info',
			'icon' => 'download-alt'
		);
		return $actions;
	}

	protected function _main_nav($page = 'index', $id = '') {
		$nav_list = parent::_main_nav($page, $id);
		unset($nav_list['create']);
		unset($nav_list['update']);
		return $nav_list;
	}

	public function download($id) {
		$object = $this->get_object($id, FALSE);
		if (!isset($object['url']) || empty($object['url'])) {
			set_notice_message('danger', lang('empty_list_data'));
			redirect('admin/recruitment_application');
		}

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($object['url']) . '"');
		header('Content-Length: ' . filesize($object['url']));
		readfile($object['url']);
	}

	public function remove($id, $redirect = TRUE) {
		$application = $this->get_object($id);
		@unlink($application['url']);
		parent::remove($id, $redirect);
	}

}
