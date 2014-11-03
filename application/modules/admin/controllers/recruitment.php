<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recruitment extends MY_Inner_Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->data['type'] = 'recruitment';
		$this->data['has_order'] = FALSE;
		$this->data['has_activation'] = FALSE;
		$this->load->model(array('recruitment_application_model', 'recruitment_model'));
		$this->load->language(array('recruitment'));
		$this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));

		$this->data['search_fields'] = array('position');
	}

	protected function set_validation_rules($action) {
		$rules = array(
			array('field' => 'position', 'label' => lang('recruitment_position'), 'rules' => 'trim|strip_tags|htmlspecialchars|required'),
			array('field' => 'description', 'label' => lang('recruitment_description'), 'rules' => 'required'),
			array('field' => 'from_time', 'label' => lang('recruitment_from_time'), 'rules' => 'required'),
			array('field' => 'to_time', 'label' => lang('recruitment_to_time'), 'rules' => 'required'),
		);
		return $rules;
	}

	protected function prepare_object($id = FALSE) {
		$object = array(
			'position' => $this->input->post('position'),
			'description' => $this->input->post('description'),
			'from_time' => $this->input->post('from_time'),
			'to_time' => $this->input->post('to_time'),
		);
		if ($id !== FALSE) {
			$object = array_merge($object, $this->get_object($id, TRUE));
		}
		$specific_input = array(
			'description' => array('input' => 'textarea'),
			'from_time' => array('input' => 'datepicker'),
			'to_time' => array('input' => 'datepicker'),
		);
		unset($object['recruitment_id']);

		return array(
			'object' => $object,
			'specific_input' => $specific_input
		);
	}

	protected function get_object($id = FALSE, $parsed = FALSE) {
		$object = parent::get_object($id, $parsed);
		if ($parsed) {
			$object['from_time'] = date('d/m/Y', $object['from_time']);
			$object['to_time'] = date('d/m/Y', $object['to_time']);
		}
		return $object;
	}

	protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
		$result = parent::get_all_objects($filter, $offset);

		foreach ($result['objects'] as &$object) {
			$object['from_time'] = date('d/m/Y', $object['from_time']);
			$object['to_time'] = date('d/m/Y', $object['to_time']);
			$object['description'] = character_limiter(strip_tags($object['description']), 100);
		}
		return $result;
	}

	public function remove($id, $redirect = TRUE) {
		$recruitment = $this->get_object($id);
		$application_list = $this->recruitment_application_model->get_where(array('recruitment_id' => $id));
		if ($application_list['return_code'] == API_SUCCESS & !empty($application_list['data'])) {
			$application_list = $application_list['data'];
		} else {
			$application_list = array();
		}
		foreach ($application_list as $application) {
			Modules::run('admin/recruitment_application/remove', $application['recruitment_application_id'], FALSE);
		}
		parent::remove($id, $redirect);
	}

	protected function create_update($id = FALSE) {
		$this->data['action'] = ($id == FALSE ? 'create' : 'update');
		if ($id !== FALSE) {
			$this->data['id'] = $id;
		}
		$this->data['form_data'] = $this->prepare_object($id);
		$validation_rules = $this->set_validation_rules($this->data['action']);
		$this->form_validation->set_rules($validation_rules);

		if ($this->form_validation->run()) {
			$params = $this->handle_post_inputs();
			$params['from_time'] = strtotime(str_replace('/', '-', $params['from_time']));
			$params['to_time'] = strtotime(str_replace('/', '-', $params['to_time']));
			if ($params['to_time'] <= $params['from_time']) {
				set_notice_message('danger', lang('recruitment_time_error'));
			} else {
				$this->handle_create_update_object($params, $this->data['action'], $id);
			}
		}
		if ($message_error = validation_errors()) {
			set_notice_message('danger', $message_error);
		}
		if ($id == FALSE) {
			$id = '';
		}
		if (empty($this->data['form_data']['object']['from_time'])) {
			$this->data['form_data']['object']['from_time'] = date('d/m/Y');
		}
		if (empty($this->data['form_data']['object']['to_time'])) {
			$this->data['form_data']['object']['to_time'] = date('d/m/Y');
		}

		$this->data['main_nav'] = $this->_main_nav($this->data['action'], $id);
		$this->load->view('create_update_view', $this->data);
	}

}
