<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MY_Outer_Controller {

    public function __construct() {
        parent::__construct();
        $this->_masterview_enabled = FALSE;
        $this->load->language(array('email', 'product'));
        $this->load->config('email', TRUE);
        $this->load->library('email');
        $this->load->helper('email');
    }

    /**
     * Send email thông báo subscribe thành công
     * @param type $email
     * @return type
     */
    public function _send_subscribe_email($email) {
        $email_config = $this->_get_email_config();
        if (empty($email_config)) {
            return FALSE;
        }
        $this->email->initialize($email_config);

        $this->email->set_newline("\r\n");
        $this->email->subject(lang('email_subscribe_subject'));
        $this->email->message($this->_pagelet_email_view($this->load->view('email_subscribe_view', array('email' => $email), TRUE), $email));
        $this->email->from($email_config['smtp_user'], PAGE_TITLE);
        $this->email->to($email);

        if (!$this->email->send()) {
            $error = $this->email->print_debugger();
            return $error;
        }
        return TRUE;
    }

    /**
     * Gửi mail thông báo xác nhận đơn đặt hàng
     * @param type $order_info
     * @param type $order_list
     * @return boolean
     */
    public function _send_order_email($order_info, $order_list, $product_list) {
        $email_config = $this->_get_email_config();
        if (empty($email_config)) {
            return FALSE;
        }
        $this->email->initialize($email_config);

        $this->email->set_newline("\r\n");
        $this->email->subject(lang('email_order_subject'));
        $this->email->message($this->_pagelet_email_view($this->load->view('email_order_view', array(
                            'order_info' => $order_info,
                            'order_list' => $order_list,
                            'product_list' => $product_list,
                                ), TRUE)));
        $this->email->from($email_config['smtp_user'], PAGE_TITLE);
        $this->email->to($order_info['email']);
        $this->email->cc($email_config['smtp_user']);

        if (!$this->email->send()) {
            $error = $this->email->print_debugger();
            return $error;
        }
        return TRUE;
    }

    public function _send_newsletter_email($email_list, $content) {
        $email_config = $this->_get_email_config();
        if (empty($email_config)) {
            return FALSE;
        }
        $this->email->initialize($email_config);

        $this->email->set_newline("\r\n");
        $this->email->subject(lang('email_title'));
        $this->email->from($email_config['smtp_user'], PAGE_TITLE);
        foreach ($email_list as $email) {
            $this->email->message($this->_pagelet_email_view($content, $email));
            $this->email->to($email);
            $this->email->send();
        }
        return TRUE;
    }

    public function _send_reply_email($contact_info, $content) {
        $email_config = $this->_get_email_config();
        if (empty($email_config)) {
            return FALSE;
        }
        $this->email->initialize($email_config);

        $this->email->set_newline("\r\n");
        $this->email->subject(lang('email_reply_title'));
        $this->email->message($this->_pagelet_email_view($content));
        $this->email->from($email_config['smtp_user'], PAGE_TITLE);
        $this->email->to($contact_info['email']);

        if (!$this->email->send()) {
            $error = $this->email->print_debugger();
            return $error;
        }
        return TRUE;
    }

    public function _pagelet_email_view($content = FALSE, $to_email = FALSE) {
        $data = array(
            'header_bar' => FALSE,
            'email' => FALSE,
            'mobile' => FALSE,
            'facebook_page' => FALSE,
        );
        foreach ($data as $key => &$value) {
            $value = Modules::run('construction/_static_content', $key, 'config');
        }
        $data['content'] = $content;
        $data['to_email'] = $to_email;
        return $this->load->view('pagelet_email_view', $data, TRUE);
    }

    public function unsubscribe() {
        $this->load->model(array('customer_email'));
        $email = $this->input->get('email');
        $ret = $this->customer_email_model->update(FALSE, array('unsubscribed' => STATUS_ACTIVE), array('email' => $email));
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            set_notice_message('success', lang('static_content_home_email_unsubscribe_success'));
        } else {
            set_notice_message('error', lang('static_content_home_email_unsubscribe_failed'));
        }
        redirect(site_url());
    }

    private function _get_email_config() {
        $email_config = $this->config->item('email_config', 'email');
        $email_config['smtp_user'] = Modules::run('construction/_static_content', 'email', 'config');
        $email_config['smtp_pass'] = Modules::run('construction/_static_content', 'email_password', 'config');
        if (empty($email_config['smtp_user']) || empty($email_config['smtp_pass'])) {
            return FALSE;
        }
        return $email_config;
    }

}
