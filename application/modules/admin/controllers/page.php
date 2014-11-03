<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'page';
        $this->data['has_order'] = TRUE;
        $this->data['has_activation'] = TRUE;
        $this->load->model(array('page_model', 'content_model'));
        $this->load->language(array('page'));
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));

        $this->load->config('content_page', TRUE);
        $this->data['search_fields'] = $this->get_multi_lang_fields(array('name'));

        $this->data['select_fields'] = array(
            'parent_id' => $this->_get_parent_pages(lang('page_parent_id')),
        );
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
        $object = array(
            'parent_id' => $this->input->post('parent_id'),
        );
        $fields = $this->get_multi_lang_fields(array('name'));
        foreach ($fields as $field) {
            $object[$field] = $this->input->post($field);
        }
        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
            unset($object['status']);
        }
        $parent_pages = $this->page_model->get_where('parent_id IS NULL OR parent_id = 0', 'display_order');
        $pages = array(0 => lang('page_empty_parent'));
        $specific_input = array();

        if ($parent_pages['return_code'] == API_SUCCESS && !empty($parent_pages['data'])) {
            foreach ($parent_pages['data'] as $page) {
                $pages[$page['page_id']] = $page['name_' . $this->_current_lang];
            }
            $specific_input['parent_id'] = array('input' => 'dropdown', 'options' => $pages);
        } else {
            unset($object['parent_id']);
        }
        unset($object['display_order']);
        unset($object['page_id']);

        return array(
            'object' => $object,
            'specific_input' => $specific_input
        );
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        $object = parent::get_object($id, $parsed);
        if ($parsed) {
            if (!empty($object['parent_id'])) {
                $parent_page = $this->page_model->get($object['parent_id']);
                if ($parent_page['return_code'] == API_SUCCESS && !empty($parent_page['data'])) {
                    $parent_page = $parent_page['data'];
                    $object['parent_id'] = $parent_page['name_' . $this->_current_lang];
                }
            } else {
                $object['parent_id'] = FALSE;
            }
            unset($object['status']);
        }
        return $object;
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $result = parent::get_all_objects($filter, $offset, 'display_order');

        foreach ($result['objects'] as &$page) {
            if (!empty($page['parent_id'])) {
                $parent_page = $this->page_model->get($page['parent_id']);
                if ($parent_page['return_code'] == API_SUCCESS && !empty($parent_page['data'])) {
                    $parent_page = $parent_page['data'];
                    $page['parent_id'] = $parent_page['name_' . $this->_current_lang];
                }
            } else {
                $page['parent_id'] = FALSE;
            }
        }
        return $result;
    }

    public function remove($id, $redirect = TRUE) {
        $page = $this->get_object($id);

        // Xóa tất cả content của page
        $contents = $this->content_model->get_where(array('page_id' => $id));
        if ($contents['return_code'] == API_SUCCESS && !empty($contents['data'])) {
            $contents = $contents['data'];
        } else {
            $contents = array();
        }

        foreach ($contents as $content) {
            Modules::run('admin/content/remove', $content['content_id'], FALSE);
        }

        // Nếu là Category cấp 1 thì xóa hết category cấp 2
        if (empty($page['parent_id'])) {
            $sub_pages = $this->page_model->get_where(array('parent_id' => $page['page_id']));
            if ($sub_pages['return_code'] == API_SUCCESS && !empty($sub_pages['data'])) {
                $sub_pages = $sub_pages['data'];
            } else {
                $sub_pages = array();
            }
            foreach ($sub_pages as $sub_page) {
                $this->remove($sub_page['page_id'], FALSE);
            }
        }
        parent::remove($id, $redirect);
    }

    protected function set_actions($id) {
        $actions = parent::set_actions($id);
        $actions['keyword'] = array(
            'url' => site_url('admin/keyword/update?target_type=page&target_id=' . $id),
            'button' => 'success',
            'icon' => 'tags'
        );
        return $actions;
    }

    private function _get_parent_pages($extra_select = FALSE) {
        $pages = array();
        if (!empty($extra_select)) {
            $pages[""] = $extra_select;
        }

        $main_pages = $this->page_model->get_where('parent_id IS NULL OR parent_id = 0', 'display_order');
        if ($main_pages['return_code'] == API_SUCCESS && !empty($main_pages['data'])) {
            $main_pages = $main_pages['data'];
            foreach ($main_pages as $page) {
                $pages[$page['page_id']] = $page['name_' . $this->_current_lang];
            }
        }

        return $pages;
    }

}
