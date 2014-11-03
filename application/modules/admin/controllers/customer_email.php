<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_Email extends MY_Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->my_auth->login_required();
        $this->load->model(array('customer_email_model', 'order_model'));
        $this->load->language(array('customer_email'));
        $this->set_title(lang('manager_customer_email'));
    }

    public function index() {
        $objects = $this->customer_email_model->get_all();
        if ($objects['return_code'] == API_SUCCESS && !empty($objects['data'])) {
            $objects = $objects['data'];
        } else {
            $objects = array();
        }

        foreach ($objects as &$obj) {
            if ($obj['unsubscribed'] == STATUS_INACTIVE) {
                $obj['actions'] = array(
                    'send_email' => array(
                        'url' => site_url('admin/customer_email/mass?email=' . $obj['email']),
                        'button' => 'success',
                        'icon' => 'pencil'
                    ),
                );
            } else {
                $obj['actions'] = array();
            }
            unset($obj['unsubscribed']);
        }

        $data = array(
            'type' => 'customer_email',
            'objects' => $objects,
            'pagination' => FALSE,
            'main_nav' => array(
                'export' => array(
                    'url' => site_url('admin/customer_email/export'),
                    'icon' => 'download-alt'
                )
            )
        );

        $data['mass_action_options'] = array(
            'send_email' => lang('admin_action_send_email'),
        );
        $this->load->view('list_view', $data);
    }
    
    public function mass() {
        $email_list = $this->input->get_post('email');
        $id_list = $this->input->get_post('ids');
        if (empty($email_list) && empty($id_list)) {
            redirect('admin/customer_email');
        }
        if (!empty($email_list)) {
            $email_list = explode(',', $email_list);
            $filter = 'email IN ("' . implode('","', $email_list) . '")';
        } else if (!empty($id_list)) {
            $id_list = explode(',', $id_list);
            $filter = 'customer_email_id IN ("' . implode('","', $id_list) . '")';
        }
        $filter .= ' AND unsubscribed = "' . STATUS_INACTIVE . '"';

        $valid_email_list = $this->customer_email_model->get_where($filter);
        if ($valid_email_list['return_code'] == API_SUCCESS && !empty($valid_email_list['data'])) {
            $valid_email_list = $valid_email_list['data'];
        } else {
            $valid_email_list = array();
        }

        if ((!empty($email_list) && count($valid_email_list) != count($email_list)) || (!empty($id_list) && count($valid_email_list) != count($id_list))) {
            set_notice_message('danger', lang('customer_email_send_unsubscribed'));
        }
        if (count($valid_email_list) <= 0) {
            redirect('admin/customer_email');
        }
        $email_array = array();
        foreach ($valid_email_list as $email) {
            $email_array[] = $email['email'];
        }

        $rules = array(
            array('field' => 'subject', 'label' => lang('customer_email_subject'), 'rules' => 'trim|required'),
            array('field' => 'content', 'label' => lang('customer_email_content'), 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $subject = $this->input->post('subject');
            $content = $this->input->post('content');
            if (!empty($content)) {
                $ret = Modules::run('email/_send_newsletter_email', $email_array, $content);
                if ($ret) {
                    set_notice_message('success', lang('customer_email_send_success'));
                } else {
                    set_notice_message('danger', lang('customer_email_send_error'));
                }
                redirect('admin/customer_email');
            }
        }
        if (!empty($email_list) && $message_error = validation_errors()) {
            set_notice_message('danger', $message_error);
        }
        $data = array(
            'email_list' => implode(", ", $email_array),
        );
        $this->load->view('customer_email/frm_send_email', $data);
    }

    public function export() {
        $this->disable_masterview();
        $this->load->library('excel_xml');
        $objects = $this->customer_email_model->get_all();
        if ($objects['return_code'] == API_SUCCESS && !empty($objects['data'])) {
            $objects = $objects['data'];
        } else {
            $objects = array();
        }

        $this->excel_xml->addRow(array(lang('customer_email_manager_title')));
        foreach ($objects as $obj) {
            $this->excel_xml->addRow(array($obj['email']));
        }
        $this->excel_xml->generateXML('customer_email_' . date('Ymd'));
    }

}
