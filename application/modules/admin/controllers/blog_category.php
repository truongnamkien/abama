<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_Category extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'blog_category';
        $this->data['has_order'] = TRUE;
        $this->data['has_activation'] = FALSE;
        $this->load->model(array('blog_category_model', 'blog_model'));
        $this->load->language(array('blog_category'));
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));

        $this->load->config('content_page', TRUE);
        $this->data['search_fields'] = $this->get_multi_lang_fields(array('name'));

        $this->data['select_fields'] = array();
    }

    protected function set_validation_rules($action) {
        $rules = array();
        $fields = $this->get_multi_lang_fields(array('name'));
        foreach ($fields as $field) {
            $rules[] = array('field' => $field, 'label' => lang('page_' . $field), 'rules' => 'trim|strip_tags|max_length[100]|required');
        }
        return $rules;
    }

    protected function prepare_object($id = FALSE) {
        $object = array();
        $fields = $this->get_multi_lang_fields(array('name'));
        foreach ($fields as $field) {
            $object[$field] = $this->input->post($field);
        }
        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
        }
        $specific_input = array();

        unset($object['display_order']);
        unset($object['blog_category_id']);

        return array(
            'object' => $object,
            'specific_input' => $specific_input
        );
    }

    public function remove($id, $redirect = TRUE) {
        $blog_category = $this->get_object($id);

        // Xóa tất cả blog của blog_category
        $blogs = $this->blog_model->get_where(array('blog_category_id' => $id));
        if ($blogs['return_code'] == API_SUCCESS && !empty($blogs['data'])) {
            $blogs = $blogs['data'];
        } else {
            $blogs = array();
        }

        foreach ($blogs as $blog) {
            Modules::run('admin/blog/remove', $blog['blog_id'], FALSE);
        }
        parent::remove($id, $redirect);
    }

}
