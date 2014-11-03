<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends MY_Outer_Controller {

	public function index() {
        $this->set_title(lang('authen_404_error_title'));
		$this->load->view('error_view', array());
	}

}
