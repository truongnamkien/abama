<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends MY_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->my_auth->login_required();
        $this->load->model(array('static_content_model'));
        $this->set_title(lang('manager_title') . ' - ' . lang('static_content_config'));
        $this->load->config('content_page', TRUE);
        $this->config_list = array(
            'password' => 'password',
            'email' => 'text',
            'email_password' => 'password',
            'mobile' => 'text',
            'facebook_page' => 'text',
            'keyword' => 'text',
            'description' => 'text',
            'yahoo' => 'text',
            'skype' => 'text',
            'header_bar' => 'text',
        );
    }

    public function index() {
        $data = array(
            'config_list' => $this->config_list
        );
        $this->load->view('config/config_list', $data);
    }

    public function edit($type) {
        if (!isset($this->config_list[$type])) {
            set_notice_message('danger', lang('error_admin_config_not_found'));
            redirect(site_url('admin/config'));
        }
        $ret = $this->get_content($type, 'config');
        $value = !empty($ret) ? $ret['content'] : '';

        if ($this->input->post()) {
            $this->create_update($ret, $type, 'config');
            $ret = $this->get_content($type, 'config');
            $value = !empty($ret) ? $ret['content'] : '';
            set_notice_message('success', lang('admin_update_success'));
            redirect('admin/config');
        }
        $data = array(
            'type' => $type,
            'value' => $value,
            'input' => $this->config_list[$type]
        );
        $this->load->view('config/frm_edit_config', $data);
    }

    private function get_content($name, $page = 'home') {
        $content = $this->static_content_model->get_where(array('page' => $page, 'content_name' => $name));
        if ($content['return_code'] == API_SUCCESS && !empty($content['data'])) {
            return array_shift($content['data']);
        } else {
            return FALSE;
        }
    }

    private function create_update($content, $name, $page = 'home') {
        $ret = array();
        $default = $this->input->get_post($name);
        $ret['content'] = !empty($default) ? $default : '';

        if (empty($content)) {
            $ret['page'] = $page;
            $ret['content_name'] = $name;
            $ret['type'] = Static_Content_Model::STATIC_CONTENT_TYPE_TEXT;

            $this->static_content_model->create($ret);
        } else if (!empty($ret)) {
            $this->static_content_model->update($content['static_content_id'], $ret);
        }
    }

}
