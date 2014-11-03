<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Blog_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'blog';
        $this->database = 'blog';
        $this->load->config('content_page', TRUE);
        parent::__construct();
    }

    protected function check_existed($data) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " AND " : "") . 'title_' . $lang . ' = "' . $data['title_' . $lang] . '"';
        }

        $filter .= " AND created_at > " . (now() - 60);
        $blog_info = $this->get_where($filter);
        if ($blog_info['return_code'] == API_SUCCESS && !empty($blog_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

    public function create($data) {
        if (!isset($data['created_at']) || empty($data['created_at'])) {
            $data['created_at'] = time();
        }
        return parent::create($data);
    }

}
