<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Outer_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('search', 'product_category', 'product'));
        $this->load->model(array('product_category_model', 'product_model', 'product_photo_model'));
        $this->load->config('content_page', TRUE);
    }

    /**
     * Hiển thị form để search Sản phẩm
     */
    public function _search_form() {
        $this->load->view('pagelet_search_form', array());
    }

    public function index() {
        $query = $this->input->get_post('query');
        $query = htmlspecialchars(strip_tags(trim($query)));
        $products = $this->_search_product($query);
        
        $category_list = array();
        $ret = array();
        foreach ($products as $product) {
            if (!isset($category_list[$product['product_category_id']])) {
                $category = $this->product_category_model->get($product['product_category_id']);
                if ($category['return_code'] == API_SUCCESS && !empty($category['data'])) {
                    $category = $category['data'];
                    $category_list[$category['product_category_id']] = $category;
                }
            }
            if (!isset($category_list[$product['product_category_id']])) {
                continue;
            }
            $product['category'] = $category_list[$product['product_category_id']];

            $photo = $this->product_photo_model->get_where(array('product_id' => $product['product_id']));
            if ($photo['return_code'] == API_SUCCESS && !empty($photo['data'])) {
                $photo = $photo['data'];
                $photo = array_shift($photo);
                $product['photo'] = Modules::run('photo/_get_photo_path', $photo['url'], 196);
                $ret[] = $product;
            }
        }

        $data = array(
            'query' => $query,
            'products' => $ret
        );
        $this->set_title(lang('search_result_title'));

        $this->load->view('search_list_view', $data);
    }

    /**
     * Hiển thị kết quả instant search
     * @param type $query
     */
    public function _instant_search($query) {
        $categories = $this->_search_category($query);
        $products = $this->_search_product($query);

        if (empty($categories) && empty($products)) {
            return '';
        }
        $data['categories'] = $categories;
        $data['products'] = $products;

        return $this->load->view('pagelet_instant_search', $data, TRUE);
    }

    /**
     * Search dữ liệu
     * @param type $query
     */
    private function _search_category($query) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $ret = array();
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " OR " : "") . 'category_name' . $lang . ' LIKE "%' . $query . '%"';
        }
        $filter .= ' AND status = "' . STATUS_ACTIVE . '"';

        $categories = $this->product_category_model->get_where($filter);
        if ($categories['return_code'] == API_SUCCESS && !empty($categories['data'])) {
            $categories = $categories['data'];
        } else {
            $categories = array();
        }
        foreach ($categories as &$category) {
            if (!empty($category['parent_id'])) {
                $category['count'] = $this->product_model->count_where(array(
                    'product_category_id' => $category['product_category_id'],
                    'status' => STATUS_ACTIVE
                ));
            } else {
                $sub_categories = $this->product_category_model->get_where(array('parent_id' => $category['product_category_id'], 'status' => STATUS_ACTIVE));
                if ($sub_categories['return_code'] == API_SUCCESS && !empty($sub_categories['data'])) {
                    $sub_categories = $sub_categories['data'];
                } else {
                    $sub_categories = array();
                }

                if (empty($sub_categories)) {
                    $category['count'] = 0;
                } else {
                    $filter = 'product_category_id IN (';
                    $count = 0;
                    foreach ($sub_categories as $sub_category) {
                        $filter .= $sub_category['product_category_id'];
                        if ($count < count($sub_categories) - 1) {
                            $filter .= ',';
                        }
                        $count++;
                    }
                    $filter .= ') AND status = \'' . STATUS_ACTIVE . '\'';
                    $category['count'] = $this->product_model->count_where($filter);
                }
            }
        }
        return $categories;
    }

    /**
     * Search theo tên Sản phẩm
     * @param type $query
     * @return array
     */
    private function _search_product($query) {
        $lang_list = $this->config->item('language_list', 'content_page');
        $ret = array();
        $filter = "";
        foreach ($lang_list as $lang) {
            $filter .= (!empty($filter) ? " OR " : "") . 'name_' . $lang . ' LIKE "%' . $query . '%"';
        }
        $filter .= ' AND status = "' . STATUS_ACTIVE . '"';

        $products = $this->product_model->get_where($filter);
        if ($products['return_code'] == API_SUCCESS && !empty($products['data'])) {
            $products = $products['data'];
        } else {
            $products = array();
        }

        return $products;
    }

}
