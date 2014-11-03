<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Outer_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('contact'));
        $this->load->model(array('contact_model', 'customer_email_model'));
    }

    public function index() {
        $this->set_title(lang('contact_contact'));

        $rules = array(
            array('field' => 'email', 'label' => lang('contact_email'), 'rules' => 'trim|valid_email|required'),
            array('field' => 'contact_content', 'label' => lang('contact_content'), 'rules' => 'trim|htmlspecialchars|strip_tags|required'),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $info = array(
                'email' => $this->input->post('email'),
                'content' => $this->input->post('contact_content'),
                'ip_address' => $this->input->ip_address(),
            );
            $this->contact_model->create($info);
            $this->customer_email_model->create(array('email' => $info['email']));
            redirect(site_url());
        }

        $this->load->view('frm_contact', array());
    }

}
