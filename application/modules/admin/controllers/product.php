<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'product';
        $this->data['has_order'] = TRUE;
        $this->data['has_activation'] = TRUE;
        $this->load->model(array('product_model', 'product_category_model', 'product_photo_model'));
        $this->load->language(array('product'));

        $this->load->config('content_page', TRUE);
        $this->data['search_fields'] = $this->get_multi_lang_fields(array('name'));

        $this->data['select_fields'] = array(
            'product_category_id' => $this->_get_all_categories(lang('product_product_category_id')),
        );
        $this->load->config('product', TRUE);
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));

        // Config cho phần upload
        $this->load->config('upload', TRUE);
        $this->upload_config = $this->config->item('photo', 'upload');
        $this->upload_config['upload_path'] = $this->config->item('product_photo_path', 'upload');
    }

    protected function set_validation_rules($action) {
        $rules = array(
            array('field' => 'price', 'label' => lang('product_price'), 'rules' => 'numeric|required|greater_than[0]'),
            array('field' => 'price_off', 'label' => lang('product_price_off'), 'rules' => "numeric|less_than[{$this->input->post('price')}]|greater_than[0]"),
            array('field' => 'product_category_id', 'label' => lang('product_product_category_id'), 'rules' => 'numeric|required'),
        );
        $fields = $this->get_multi_lang_fields(array('name'));
        foreach ($fields as $field) {
            $rules[] = array('field' => $field, 'label' => lang('page_' . $field), 'rules' => 'trim|strip_tags|max_length[100]|required');
        }

        return $rules;
    }

    protected function prepare_object($id = FALSE) {
        $object = array();
        $fields = $this->get_multi_lang_fields(array('name', 'description'));
        foreach ($fields as $field) {
            $object[$field] = $this->input->post($field);
        }
        $object = array_merge($object, array(
            'price' => $this->input->post('price'),
            'price_off' => $this->input->post('price_off'),
            'product_category_id' => $this->input->post('product_category_id'),
            'hot' => STATUS_INACTIVE,
            'best_seller' => STATUS_INACTIVE,
            'sold_out' => STATUS_INACTIVE,
        ));
        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
            unset($object['status']);
        }
        $object['photo'] = $this->_multi_photo_upload($id);

        $product_categories = $this->_get_all_categories();
        if (empty($product_categories)) {
            set_notice_message('danger', lang('error_admin_product_empty_category'));
            redirect(site_url('admin/product_category/create'));
        }

        $specific_input = array(
            'price' => array('input' => 'suffix', 'extra' => 'VNĐ'),
            'price_off' => array('input' => 'suffix', 'extra' => 'VNĐ'),
            'photo' => array('input' => 'none'),
            'product_category_id' => array('input' => 'dropdown', 'options' => $product_categories),
            'hot' => array('input' => 'checkbox_toggle'),
            'best_seller' => array('input' => 'checkbox_toggle'),
            'sold_out' => array('input' => 'checkbox_toggle'),
        );
        $fields = $this->get_multi_lang_fields(array('description'));
        foreach ($fields as $field) {
            $specific_input[$field] = array('input' => 'textarea');
        }
        unset($object['product_id']);
        unset($object['display_order']);
        unset($object['created_at']);

        return array(
            'object' => $object,
            'specific_input' => $specific_input
        );
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $result = parent::get_all_objects($filter, $offset, 'display_order');

        // Remove unused photos
        $this->_remove_photo();

        $categories = array();
        foreach ($result['objects'] as &$product) {
            if (!isset($categories[$product['product_category_id']])) {
                $category = $this->product_category_model->get($product['product_category_id']);
                if ($category['return_code'] !== API_SUCCESS || empty($category['data'])) {
                    continue;
                }
                $categories[$product['product_category_id']] = $category['data']['category_name_' . $this->_current_lang];
            }
            $product['product_category_id'] = $categories[$product['product_category_id']];
            $product['created_at'] = date($this->config->item('date_format'), $product['created_at']);
            $product['price'] = format_price($product['price']);
            $product['price_off'] = format_price($product['price_off']);
            $fields = $this->get_multi_lang_fields(array('description'));
            foreach ($fields as $field) {
                unset($product[$field]);
            }
        }
        return $result;
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        $product = parent::get_object($id, $parsed);
        if ($parsed) {
            $category = $this->product_category_model->get($product['product_category_id']);
            if ($category['return_code'] == API_SUCCESS && !empty($category['data'])) {
                $product['product_category_id'] = $category['data']['category_name_' . $this->_current_lang];
            }
            $product_photos = $this->product_photo_model->get_where(array('product_id' => $id));
            if ($product_photos['return_code'] == API_SUCCESS && !empty($product_photos['data'])) {
                $product_photos = $product_photos['data'];
                $product['photo'] = '';
                foreach ($product_photos as $photo) {
                    $product['photo'] .= Modules::run('admin/upload/render', $photo['product_photo_id']);
                }
            }
            $product['created_at'] = date($this->config->item('date_format'), $product['created_at']);
            $fields = $this->get_multi_lang_fields(array('description'));
            foreach ($fields as $field) {
                $product[$field] = character_limiter(strip_tags($product[$field]), 100);
            }

            $product['price'] = format_price($product['price']);
            $product['price_off'] = format_price($product['price_off']);
            unset($product['status']);
        }
        return $product;
    }

    public function remove($id, $redirect = TRUE) {
        $this->product_photo_model->delete_where(array('product_id' => $id));
        parent::remove($id, $redirect);
    }

    protected function handle_move($id, $direction = 'up', $filter_by = FALSE) {
        parent::handle_move($id, $direction, 'product_category_id');
    }

    /**
     * Upload multi photos with process bar
     * @param type $id
     * @param type $product_photo
     * @return type
     */
    private function _multi_photo_upload($id = FALSE, $product_photo = array()) {
        $data['photo_list'] = $product_photo;

        if ($id !== FALSE) {
            $photo_list = $this->product_photo_model->get_where(array('product_id' => $id));
            if ($photo_list['return_code'] == API_SUCCESS && !empty($photo_list['data'])) {
                $photo_list = $photo_list['data'];

                foreach ($photo_list as $photo) {
                    $data['photo_list'][] = $photo['product_photo_id'];
                }
            }
        }
        return $this->load->view('frm_multi_photo_upload', $data, TRUE);
    }

    protected function handle_create_update_object($params, $action, $id = FALSE) {
        if ($action == 'create' && $id == FALSE && (!isset($params['display_order']) || empty($params['display_order']))) {
            $params['display_order'] = $this->get_available_display_order();
        } else if ($action == 'update' && $id !== FALSE && isset($params['display_order']) && !empty($params['display_order'])) {
            if ($params['display_order'] >= $this->product_model->count_all()) {
                redirect(site_url('admin/' . $this->data['type']));
            }
        }
        if (isset($params['hot'])) {
            $params['hot'] = STATUS_ACTIVE;
        } else {
            $params['hot'] = STATUS_INACTIVE;
        }
        if (isset($params['sold_out'])) {
            $params['sold_out'] = STATUS_ACTIVE;
        } else {
            $params['sold_out'] = STATUS_INACTIVE;
        }
        if (isset($params['best_seller'])) {
            $params['best_seller'] = STATUS_ACTIVE;
        } else {
            $params['best_seller'] = STATUS_INACTIVE;
        }

        $product_photos = array();
        if (isset($params['product_photo']) && !empty($params['product_photo'])) {
            $product_photos = $params['product_photo'];
        }
        unset($params['product_photo']);
        unset($params['submit']);

        if ($id !== FALSE) {
            $product_id = $id;
            $product = $this->product_model->$action($id, $params);
        } else {
            $product = $this->product_model->$action($params);
        }
        if ($product['return_code'] == API_SUCCESS && !empty($product['data'])) {
            if ($id !== FALSE) {
                $product_id = $id;
            } else {
                $product_id = $product['data']['product_id'];
            }
        } else {
            set_notice_message('danger', lang('admin_update_error'));
            return FALSE;
        }

        foreach ($product_photos as $photo_id) {
            $this->product_photo_model->update($photo_id, array('product_id' => $product_id));
        }
        if ($id !== FALSE) {
            set_notice_message('success', lang('admin_update_success'));
            redirect(site_url('admin/' . $this->data['type'] . '/show/' . $id));
        } else {
            set_notice_message('success', lang('admin_create_success'));
            redirect(site_url('admin/' . $this->data['type']));
        }
    }

    /**
     * Remove unused photos or product photos
     * @param type $id
     */
    public function _remove_photo($id = 0) {
        if (empty($id)) {
            $filter = array('product_id' => $id);
        } else {
            $filter = array('product_photo_id' => $id);
        }

        $photo_list = $this->product_photo_model->get_where($filter);
        if ($photo_list['return_code'] == API_SUCCESS && !empty($photo_list['data'])) {
            $photo_list = $photo_list['data'];
            $this->product_photo_model->delete_where($filter);
        }

        // Remove những hình up tạm không được xử lý
        $temp_path = $this->config->item('multi_file_upload_path', 'upload');
        $temp_list = glob($temp_path . '*.*');
        foreach ($temp_list as $temp) {
            if ($temp !== $temp_path . 'index.html') {
                @unlink($temp);
            }
        }

        // Remove những hình up bị lỗi
        $photo_list = glob($this->upload_config['upload_path'] . '*.*');
        if (!empty($photo_list)) {
            foreach ($photo_list as $photo_path) {
                if ($photo_path !== $this->upload_config['upload_path'] . 'index.html') {
                    $length = strrpos($photo_path, '.');

                    $filter = array('url' => substr($photo_path, 0, $length));
                    $ret = $this->product_photo_model->get_where($filter);
                    if ($ret['return_code'] !== API_SUCCESS || empty($ret['data'])) {
                        @unlink($photo_path);
                    }
                }
            }
        }
    }

    protected function set_actions($id) {
        $actions = parent::set_actions($id);
        $actions['keyword'] = array(
            'url' => site_url('admin/keyword/update?target_type=product&target_id=' . $id),
            'button' => 'success',
            'icon' => 'tags'
        );
        return $actions;
    }

    /**
     * List tất cả danh mục Sản phẩm ra để select
     * @return type
     */
    private function _get_all_categories($extra_select = FALSE) {
        $product_categories = array();
        if (!empty($extra_select)) {
            $product_categories[""] = $extra_select;
        }

        $main_categories = $this->product_category_model->get_where('parent_id IS NULL OR parent_id = 0', 'display_order');
        if ($main_categories['return_code'] == API_SUCCESS && !empty($main_categories['data'])) {
            $main_categories = $main_categories['data'];
            foreach ($main_categories as $main_category) {
                $product_categories[$main_category['product_category_id']] = $main_category['category_name_' . $this->_current_lang];
                $sub_categories = $this->product_category_model->get_where(array('parent_id' => $main_category['product_category_id']), 'display_order');
                if ($sub_categories['return_code'] == API_SUCCESS && !empty($sub_categories['data'])) {
                    $sub_categories = $sub_categories['data'];
                    $ret = array();
                    foreach ($sub_categories as $sub_category) {
                        $product_categories[$sub_category['product_category_id']] = $sub_category['category_name_' . $this->_current_lang];
                    }
                }
            }
        }
        return $product_categories;
    }

}
