<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Multi_Lang extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('multi_language'));
        $this->load->config('content_page', TRUE);
    }

    public function _icon_lang_list() {
        $data['lang_list'] = $this->config->item('language_list', 'content_page');
        $this->load->view('pagelet_multi_lang_icon_list', $data);
    }

    public function _icon_lang_item($language) {
        $data['language'] = $language;
        $this->load->view('pagelet_multi_lang_icon_item', $data);
    }

}
