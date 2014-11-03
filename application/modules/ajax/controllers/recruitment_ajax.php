<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Recruitment_Ajax extends MY_Ajax {

	public function __construct() {
		parent::__construct();
		$this->load->language(array('recruitment', 'recruitment_application'));
		$this->load->model(array('recruitment_model', 'recruitment_application_model'));
	}

	public function apply() {
		$recruitment_id = $this->input->get_post('recruitment_id');
		$recruitment = $this->recruitment_model->get($recruitment_id);
		if ($recruitment['return_code'] != API_SUCCESS || empty($recruitment['data'])) {
			$this->response->run("show_alert('" . lang('recruitment_application_apply_error') . "');");
		} else {
			$recruitment = $recruitment['data'];
			$html = Modules::run('recruitment/_pagelet_application_form', $recruitment);
			if (empty($html)) {
				$this->response->run("show_alert('" . lang('recruitment_application_apply_error') . "');");
			} else if ($html === TRUE) {
				$this->response->run("show_alert('" . lang('recruitment_application_apply_success') . "');");
				$this->response->run("load_application_form();");
			} else {
				$this->response->html("#apply_form_html", $html);
			}
		}
		$this->response->send();
	}

}
