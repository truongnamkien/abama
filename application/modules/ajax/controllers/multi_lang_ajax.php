<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Multi_Lang_Ajax extends MY_Ajax {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Set session lại theo ngôn ngữ mới
     */
    public function change_lang() {
        $language = $this->input->get_post('lang');
        $this->_current_lang = $this->config->item('language');
        if ($language !== $this->_current_lang) {
            $this->session->set_userdata($this->_language_identity_key, $language);
            $this->response->run('window.location.reload();');
            $this->response->send();
        }
    }

}