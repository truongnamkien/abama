<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
require_once(APPPATH . 'models/abstract_model.php');

class Page_Model extends Abstract_Model {

    public function __construct() {
        $this->type = 'page';
        $this->database = 'page';
        $this->load->config('content_page', TRUE);
        parent::__construct();
    }

    protected function check_existed($data) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " AND " : "") . 'name_' . $lang . ' = "' . $data['name_' . $lang] . '"';
        }
        if (isset($data['parent_id']) && !empty($data['parent_id'])) {
            $filter .= " AND parent_id = \"" . $data['parent_id'] . "\"";
        } else {
            $filter .= ' AND (parent_id = 0 OR parent_id IS NULL)';
        }
        $page_info = $this->get_where($filter);
        if ($page_info['return_code'] == API_SUCCESS && !empty($page_info['data'])) {
            return $this->_ret(API_SUCCESS, TRUE);
        }

        return $this->_ret(API_SUCCESS, FALSE);
    }

}
