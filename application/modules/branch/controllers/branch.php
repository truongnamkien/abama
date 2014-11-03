<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends MY_Outer_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('branch'));
        $this->load->model(array('branch_model'));
    }

    public function _pagelet_branch_list() {
        $branch_list = $this->branch_model->get_all();
        if ($branch_list['return_code'] == API_SUCCESS && !empty($branch_list['data'])) {
            $branch_list = $branch_list['data'];
        } else {
            $branch_list = array();
        }
		if (empty($branch_list)) {
			return FALSE;
		}
        $data = array('branch_list' => $branch_list);

        return $this->load->view('pagelet_branch_list', $data, TRUE);
    }

}
