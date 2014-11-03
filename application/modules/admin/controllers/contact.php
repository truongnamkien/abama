<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'contact';
        $this->data['has_order'] = FALSE;
        $this->data['has_activation'] = FALSE;
        $this->load->model(array('contact_model'));
        $this->load->language(array('contact'));
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));
    }

    protected function prepare_object($id = FALSE) {
        return FALSE;
    }

    protected function create_update($id = FALSE) {
        redirect('admin/contact');
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $result = parent::get_all_objects($filter, $offset, $sort_by, $sort_order);

        foreach ($result['objects'] as &$obj) {
            $obj['content'] = character_limiter(strip_tags($obj['content']), 100);
            $obj['created_at'] = date('d/m/Y H:i:s', $obj['created_at']);
        }
        return $result;
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        $object = parent::get_object($id, $parsed);
        if ($parsed) {
            $object['content'] = character_limiter(strip_tags($object['content']), 100);
            $object['created_at'] = date('d/m/Y H:i:s', $object['created_at']);
        }
        return $object;
    }

    protected function _main_nav($page = 'index', $id = '') {
        $nav_list = parent::_main_nav($page, $id);
        unset($nav_list['create']);
        unset($nav_list['update']);
        return $nav_list;
    }

    protected function set_actions($id) {
        $actions = parent::set_actions($id);
        unset($actions['update']);
        $actions['reply'] = array(
            'url' => site_url('admin/contact/reply/' . $id),
            'button' => 'success',
            'icon' => 'share-alt'
        );
        return $actions;
    }

    public function reply($id) {
        $contact_info = $this->contact_model->get($id);
        if ($contact_info['return_code'] != API_SUCCESS || empty($contact_info['data'])) {
            set_notice_message('danger', lang('empty_list_data'));
            redirect('admin/contact');
        }
        $contact_info = $contact_info['data'];

        $rules = array(
            array('field' => 'content', 'label' => lang('contact_content'), 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $content = $this->input->post('content');
            if (!empty($content)) {
                $ret = Modules::run('email/_send_reply_email', $contact_info, $content);
                if ($ret) {
                    set_notice_message('success', lang('contact_send_success'));
                } else {
                    set_notice_message('danger', lang('contact_send_error'));
                }
                redirect('admin/contact');
            }
        }
        if ($message_error = validation_errors()) {
            set_notice_message('danger', $message_error);
        }
        $data = array(
            'contact_info' => $contact_info,
        );
        $this->load->view('contact/frm_send_email', $data);
    }

}
