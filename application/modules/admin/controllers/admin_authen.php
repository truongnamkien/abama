<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Authen extends MY_Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login() {
        if ($ret = $this->my_auth->logged_in()) {
            redirect(site_url('admin/page'));
        }

        $this->form_validation->set_rules('username', 'lang:authen_username', 'trim|strip_tags|required');
        $this->form_validation->set_rules('password', 'lang:authen_password', 'trim|required|max_length[32]');

        $data = array();
        if ($this->form_validation->run()) {
            $inputs = $this->_collect(array('username', 'password'));
            if ($this->my_auth->login($inputs['username'], $inputs['password'])) {
                redirect(site_url('admin/page'));
            } else {
                $data['login_failed'] = array(
                    'title' => $this->lang->line('authen_login_fail'),
                    'messages' => array($this->lang->line('authen_login_fail_helper')),
                );
            }
        }
        $this->load->view('authen/frm_admin_login', $data);
    }

    public function logout() {
        $this->my_auth->logout();
        redirect(site_url('admin/login'));
    }

    public function _pagelet_notice() {
        $notice_list = $this->session->userdata('notice_list');
        $this->session->unset_userdata('notice_list');
        if (empty($notice_list)) {
            return FALSE;
        }
        $data = array('notice_list' => $notice_list);
        return $this->load->view('authen/pagelet_notice', $data, TRUE);
    }

    public function clear_cache() {
        $this->load->library('cache');
        $this->cache->clean();
        set_notice_message('success', lang('admin_remove_success'));
        redirect('admin');
    }

    public function clear_disk() {
        Modules::run('admin/product/_remove_photo');
        $this->load->config('upload', TRUE);
        $temp_path = $this->config->item('cache_photo_path', 'upload');
        $temp_list = glob($temp_path . '*.*');
        foreach ($temp_list as $temp) {
            if ($temp !== $temp_path . 'index.html') {
                @unlink($temp);
            }
        }
        set_notice_message('success', lang('admin_remove_success'));
        redirect('admin');
    }

    public function constructor($status) {
        $construction = Modules::run('construction/_static_content', 'construct', 'config');
        $this->load->model(array('static_content_model'));
        if (!empty($construction)) {
            $this->static_content_model->update(FALSE, array(
                'content' => $status
            ), array(
                'content_name' => 'construct',
                'page' => 'config'
            ));
        } else {
            $this->static_content_model->create(array(
                'content' => $status,
                'content_name' => 'construct',
                'page' => 'config',
                'type' => Static_Content_Model::STATIC_CONTENT_TYPE_TEXT
            ));
        }
        redirect('admin');
    }

}
