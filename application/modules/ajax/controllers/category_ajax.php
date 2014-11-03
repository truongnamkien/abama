<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category_Ajax extends MY_Ajax {

    public function __construct() {
        parent::__construct();
    }

    public function product_list() {
        $offset = $this->input->get_post('offset');
        $product_category_id = $this->input->get_post('product_category_id');
        $html = Modules::run('category/_product_list', $product_category_id, $offset);

        if (!empty($html)) {
            $this->response->append("#product_list", $html);
            $this->response->run("scroll_loading = false;");
        }
        $this->response->send();
    }

}
