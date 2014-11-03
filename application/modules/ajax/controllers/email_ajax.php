<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_Ajax extends MY_Ajax {

    public function __construct() {
        parent::__construct();

        $this->load->language('static_content');
        $this->load->model('customer_email_model');
    }

    public function submit() {
        $this->form_validation->set_rules('email', 'lang:static_content_home_email', 'trim|required|valid_email');

        if ($this->form_validation->run()) {
            $email = $this->input->get_post('email');
            $customer_email = $this->customer_email_model->get_where(array('email' => $email));
            if ($customer_email['return_code'] == API_SUCCESS && !empty($customer_email['data'])) {
                $customer_email = array_shift($customer_email['data']);
                $this->customer_email_model->update($customer_email['customer_email_id'], array('unsubscribed' => STATUS_INACTIVE));
                $ret = TRUE;
            } else {
                $this->customer_email_model->create(array('email' => $email));
                $ret = Modules::run('email/_send_subscribe_email', $email);
            }
            if ($ret) {
                $this->response->run("show_alert('" . lang('static_content_home_email_submit_success') . "');");
            } else {
                $this->response->run("show_alert('" . lang('static_content_home_email_submit_false') . "');");
            }
            $this->response->run("$('input[name=\"email\"]').val('');");
        } else {
            $this->response->run("show_alert('" . form_error('email') . "');");
        }
        $this->response->send();
    }

}
