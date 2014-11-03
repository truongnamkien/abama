<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recruitment extends MY_Outer_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->language(array('recruitment', 'recruitment_application'));
		$this->load->model(array('recruitment_model', 'recruitment_application_model'));
	}

	public function index() {
		$this->set_title(lang('recruitment_recruitment'));
		$now = now();
		$filter = 'from_time <= ' . $now . ' AND to_time >= ' . $now;
		$recruitment_list = $this->recruitment_model->get_where($filter);
		if ($recruitment_list['return_code'] == API_SUCCESS && !empty($recruitment_list['data'])) {
			$recruitment_list = $recruitment_list['data'];
		} else {
			$recruitment_list = array();
		}

		foreach ($recruitment_list as &$recruitment) {
			$recruitment['from_time'] = date('d/m/Y', $recruitment['from_time']);
			$recruitment['to_time'] = date('d/m/Y', $recruitment['to_time']);
		}
		$data = array(
			'recruitment_list' => $recruitment_list,
		);
		$this->load->view('recruitment_list', $data);
	}

	/**
	 * Trang show detail của recruitment
	 */
	public function detail() {
		$recruitment = $this->_get_recruitment();
		$this->set_title(lang('recruitment_recruitment') . ' - ' . $recruitment['position']);

		$data['recruitment'] = $recruitment;
		$this->load->view('recruitment_view', $data);
	}

	/**
	 * Load data của recruitment
	 * @return type
	 */
	private function _get_recruitment() {
		$recruitment_id = $this->input->get_post('recruitment_id');
		if ($recruitment_id == FALSE || !is_numeric($recruitment_id)) {
			show_404();
		} else {
			$recruitment = $this->recruitment_model->get($recruitment_id);
			if ($recruitment['return_code'] !== API_SUCCESS || empty($recruitment['data'])) {
				show_404();
			}
		}
		return $recruitment['data'];
	}

	public function _pagelet_other_recruitment($recruitment) {
		$now = now();
		$filter = 'from_time <= ' . $now . ' AND to_time >= ' . $now . ' AND recruitment_id <> ' . $recruitment['recruitment_id'];
		$recruitment_list = $this->recruitment_model->get_where($filter);
		if ($recruitment_list['return_code'] == API_SUCCESS && !empty($recruitment_list['data'])) {
			$recruitment_list = $recruitment_list['data'];
		} else {
			$recruitment_list = array();
		}
		return $this->load->view('pagelet_other_recruitment', array('recruitment_list' => $recruitment_list), TRUE);
	}

	public function _pagelet_application_form($recruitment) {
		$rules = array(
			array('field' => 'fullname', 'label' => lang('recruitment_application_fullname'), 'rules' => 'trim|htmlspecialchars|strip_tags|max_length[50]|required'),
			array('field' => 'email', 'label' => lang('recruitment_application_email'), 'rules' => 'trim|valid_email|required|max_length[50]'),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$this->load->config('upload', TRUE);
			$tmp_path = $this->config->item('multi_file_upload_path', 'upload');
			$upload_path = $this->config->item('recruitment_path', 'upload');
			$file = $this->input->post('file');
			if (!empty($file) && glob($tmp_path . $file) != FALSE) {
				do {
					$new_name = '';
					while (strlen($new_name) < $this->config->item('max_name_length', 'upload')) {
						$new_name .= random_string('alnum', 1);
					}
					$new_name .= '.' . pathinfo($tmp_path . $file, PATHINFO_EXTENSION);
				} while (file_exists($upload_path . $new_name));
				rename($tmp_path . $file, $upload_path . $new_name);
				$file = $upload_path . $new_name;
			} else {
				$file = FALSE;
			}
			$now = now();
			$application = array(
				'recruitment_id' => $recruitment['recruitment_id'],
				'fullname' => $this->input->post('fullname'),
				'email' => $this->input->post('email'),
				'mobile' => $this->input->post('mobile'),
				'content' => $this->input->post('content'),
				'url' => (!empty($file) ? $file : ''),
				'created_at' => $now
			);
			$existed_application = $this->recruitment_application_model->get_where(array(
				'recruitment_id' => $recruitment['recruitment_id'],
				'email' => $application['email'],
			));
			if ($existed_application['return_code'] == API_SUCCESS && !empty($existed_application['data'])) {
				$existed_application = array_shift($existed_application['data']);
				if ($existed_application['created_at'] > $now - Recruitment_Application_Model::VALIDATE_TIME_AMOUNT) {
					if (!empty($application['url'])) {
						@unlink($application['url']);
					}
					return FALSE;
				}
				if (!empty($existed_application['url']) && $existed_application['url'] != $application['url']) {
					@unlink($existed_application['url']);
				}
				unset($application['recruitment_id']);
				unset($application['email']);
				$this->recruitment_application_model->update($existed_application['recruitment_application_id'], $application);
			} else {
				$this->recruitment_application_model->create($application);
			}
			return TRUE;
		}
		return $this->load->view('pagelet_application_form', array('recruitment' => $recruitment), TRUE);
	}

}
