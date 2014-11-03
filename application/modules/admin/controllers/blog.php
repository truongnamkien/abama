<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'blog';
        $this->data['has_order'] = FALSE;
        $this->data['has_activation'] = TRUE;
        $this->load->model(array('blog_model', 'blog_content_model', 'blog_category_model'));
        $this->load->language(array('blog'));
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));
        $this->load->config('content_page', TRUE);
        $this->data['search_fields'] = $this->get_multi_lang_fields(array('title'));
        $this->data['select_fields'] = array(
            'blog_category_id' => $this->_get_all_blog_categories(lang('blog_blog_category_id')),
        );
    }

    protected function set_validation_rules($action) {
        $rules = array();
        $fields = $this->get_multi_lang_fields(array('title'));
        foreach ($fields as $field) {
            $rules[] = array('field' => $field, 'label' => lang('blog_' . $field), 'rules' => 'trim|strip_tags|max_length[100]|required');
        }

        return $rules;
    }

    protected function prepare_object($id = FALSE) {
        $object = array(
            'blog_category_id' => $this->input->post('blog_category_id'),
        );
        $fields = $this->get_multi_lang_fields(array('title'));
        foreach ($fields as $field) {
            $object[$field] = $this->input->post($field);
        }

        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
            unset($object['status']);
        }

        $blog_categories = $this->_get_all_blog_categories();
        if (empty($blog_categories)) {
            set_notice_message('danger', lang('error_admin_blog_empty_category'));
            redirect(site_url('admin/blog_category/create'));
        }

        $specific_input = array(
            'blog_category_id' => array('input' => 'dropdown', 'options' => $blog_categories),
        );
        unset($object['blog_id']);
        unset($object['created_at']);

        return array(
            'object' => $object,
            'specific_input' => $specific_input
        );
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $result = parent::get_all_objects($filter, $offset, $sort_by, $sort_order);

        $blog_categories = $this->_get_all_blog_categories();
        foreach ($result['objects'] as &$obj) {
            $obj['created_at'] = date('d/m/Y H:i:s', $obj['created_at']);
            $obj['blog_category_id'] = $blog_categories[$obj['blog_category_id']];
        }
        return $result;
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        $blog = parent::get_object($id, $parsed);
        if ($parsed) {
            $blog['created_at'] = date('d/m/Y H:i:s', $blog['created_at']);
            $blog_categories = $this->_get_all_blog_categories();
            $blog['blog_category_id'] = $blog_categories[$blog['blog_category_id']];

            unset($blog['status']);
        }
        return $blog;
    }

    protected function set_actions($id) {
        $actions = parent::set_actions($id);
        $actions['keyword'] = array(
            'url' => site_url('admin/keyword/update?target_type=blog&target_id=' . $id),
            'button' => 'success',
            'icon' => 'tags'
        );
        return $actions;
    }

    public function remove($id, $redirect = TRUE) {
        $page = $this->get_object($id);

        // Xóa tất cả content của page
        $contents = $this->blog_content_model->get_where(array('blog_id' => $id));
        if ($contents['return_code'] == API_SUCCESS && !empty($contents['data'])) {
            $contents = $contents['data'];
        } else {
            $contents = array();
        }

        foreach ($contents as $content) {
            Modules::run('admin/blog_content/remove', $content['blog_content_id'], FALSE);
        }
        parent::remove($id, $redirect);
    }

    private function _get_all_blog_categories($extra_select = FALSE) {
        $ret = $this->blog_category_model->get_all('display_order');
        $blog_categories = array();
        if (!empty($extra_select)) {
            $blog_categories[""] = $extra_select;
        }
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            foreach ($ret['data'] as $blog_category) {
                $blog_categories[$blog_category['blog_category_id']] = $blog_category['name_' . $this->_current_lang];
            }
        }
        return $blog_categories;
    }

}
