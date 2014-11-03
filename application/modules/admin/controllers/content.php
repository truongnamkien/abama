<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'content';
        $this->data['has_order'] = TRUE;
        $this->data['has_activation'] = FALSE;
        $this->load->model(array('content_model', 'page_model'));
        $this->load->language(array('content', 'page'));
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));
        $this->load->config('content_page', TRUE);

        $this->data['select_fields'] = array(
            'page_id' => $this->_get_all_pages(lang('content_page_id')),
        );

        // Config cho phần upload
        $this->load->config('upload', TRUE);
        $this->upload_config = $this->config->item('photo', 'upload');
        $this->upload_config['upload_path'] = $this->config->item('content_photo_path', 'upload');

        $this->load->library('upload', $this->upload_config);
        $this->load->library('image_edit');
    }

    protected function set_validation_rules($action) {
        $rules = array(
            array('field' => 'page_id', 'label' => lang('content_page_id'), 'rules' => 'numeric|required'),
            array('field' => 'layout', 'label' => lang('content_layout'), 'rules' => 'numeric|required'),
        );
        $fields = $this->get_multi_lang_fields(array('content'));
        foreach ($fields as $field) {
            $rules[] = array('field' => $field, 'label' => lang('content_' . $field), 'rules' => 'trim');
        }

        return $rules;
    }

    protected function prepare_object($id = FALSE) {
        $object = array(
            'url' => '',
            'photo' => '',
            'page_id' => $this->input->post('page_id'),
            'layout' => $this->input->post('layout'),
        );
        $fields = $this->get_multi_lang_fields(array('content'));
        foreach ($fields as $field) {
            $object[$field] = $this->input->post($field);
        }
        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
        }
        if (isset($object['url']) && empty($object['url'])) {
            unset($object['url']);
        }

        if (isset($object['url'])) {
            $photo_url = Modules::run('photo/_get_photo_path', $object['url'], 900);
            $photo_url = base_url($photo_url);
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $url = $object['url'];
            $object['url'] = anchor($photo_url, $photo, array('class' => 'colorbox'));
            $object['url'] .= "<input type='hidden' name='url' value='$url' />";
        }

        $pages = $this->_get_all_pages();
        if (empty($pages)) {
            set_notice_message('danger', lang('error_admin_content_empty_page'));
            redirect(site_url('admin/page/create'));
        }

        $layouts = $this->config->item('page_content_layouts', 'content_page');
        $layout_content = array();
        foreach ($layouts as $key => $layout) {
            $layout_content[] = img(array('src' => asset_url('images/' . $layout), 'title' => lang('content_layout_' . $key), 'width' => 90));
        }

        $specific_input = array(
            'url' => array('input' => 'none'),
            'photo' => array('input' => 'upload'),
            'layout' => array('input' => 'radio_image', 'options' => $layout_content),
            'page_id' => array('input' => 'dropdown', 'options' => $pages),
        );
        foreach ($fields as $field) {
            $specific_input[$field] = array('input' => 'textarea');
        }
        unset($object['display_order']);
        unset($object['content_id']);

        return array(
            'object' => $object,
            'specific_input' => $specific_input
        );
    }

    protected function create_update($id = FALSE) {
        $this->data['action'] = ($id == FALSE ? 'create' : 'update');
        if ($id !== FALSE) {
            $this->data['id'] = $id;
        }
        $this->data['form_data'] = $this->prepare_object($id);
        $validation_rules = $this->set_validation_rules($this->data['action']);
        $this->form_validation->set_rules($validation_rules);

        if ($this->form_validation->run()) {
            $params = $this->handle_post_inputs();
            if (!isset($params['layout'])) {
                $params['layout'] = 0;
            }
            $params = $this->_handle_upload_photo($params);
            if ($id !== FALSE && isset($params['url'])) {
                $content = $this->get_object($id);
                if (!empty($content['url']) && $params['url'] != $content['url']) {
                    @unlink($this->upload_config['upload_path'] . $content['url']);
                }
            }

            if (is_string($params)) {
                set_notice_message('danger', $params);
            } else if ((!isset($params['url']) || empty($params['url'])) && (empty($params['content_vietnamese']) || empty($params['content_english']))) {
                set_notice_message('danger', lang('content_error_empty'));
            } else if ((!isset($params['url']) || empty($params['url'])) && !empty($params['layout'])) {
                set_notice_message('danger', lang('content_error_empty_photo'));
            } else {
                $this->handle_create_update_object($params, $this->data['action'], $id);
            }
        }
        if ($message_error = validation_errors()) {
            set_notice_message('danger', $message_error);
        }
        if ($id == FALSE) {
            $id = '';
        }
        $this->data['main_nav'] = $this->_main_nav($this->data['action'], $id);
        $this->load->view('create_update_view', $this->data);
    }

    private function _handle_upload_photo($params) {
        if (empty($_FILES['photo']['name'])) {
            return $params;
        }
        if (!$this->upload->do_upload('photo')) {
            return lang('content_photo_error_upload');
        }

        $photo = $this->upload->data();

        do {
            $photo_name = '';
            while (strlen($photo_name) < $this->config->item('max_name_length', 'upload')) {
                $photo_name .= random_string('alnum', 1);
            }
            $photo_name .= '.' . pathinfo($photo['file_name'], PATHINFO_EXTENSION);
        } while (file_exists($this->upload_config['upload_path'] . $photo_name));

        $params['url'] = $this->upload_config['upload_path'] . $photo_name;
        rename($this->upload_config['upload_path'] . $photo['file_name'], $params['url']);
        @unlink($photo['full_path']);
        unset($params['photo']);
        return $params;
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $result = parent::get_all_objects($filter, $offset, 'display_order');

        $pages = $this->_get_all_pages();
        $fields = $this->get_multi_lang_fields(array('content'));
        foreach ($result['objects'] as &$content) {
            $photo_url = '';
            if (isset($content['url']) && !empty($content['url'])) {
                $photo_url = Modules::run('photo/_get_photo_path', $content['url'], 900);
                $photo_url = base_url($photo_url);
            }
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $photo = anchor($photo_url, $photo, array('class' => 'colorbox'));

            $layouts = $this->config->item('page_content_layouts', 'content_page');
            $layout_url = asset_url('images/' . $layouts[$content['layout']]);
            $layout = img(array('src' => $layout_url, 'title' => lang('content_layout_' . $content['layout']), 'width' => 50));

            $new_content = array(
                'content_id' => $content['content_id'],
                'photo' => $photo,
                'layout' => $layout,
                'page_id' => $pages[$content['page_id']],
                'display_order' => $content['display_order'],
            );
            foreach ($fields as $field) {
                $new_content[$field] = character_limiter(strip_tags($content[$field]), 100);
            }
            $new_content['actions'] = $content['actions'];
            $content = $new_content;
        }
        return $result;
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        $content = parent::get_object($id, $parsed);
        if ($parsed) {
            $pages = $this->_get_all_pages();
            $photo_url = '';
            if (isset($content['url']) && !empty($content['url'])) {
                $photo_url = Modules::run('photo/_get_photo_path', $content['url'], 900);
                $photo_url = base_url($photo_url);
            }
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $photo = anchor($photo_url, $photo, array('class' => 'colorbox'));

            $layouts = $this->config->item('page_content_layouts', 'content_page');
            $layout_url = asset_url('images/' . $layouts[$content['layout']]);
            $layout = img(array('src' => $layout_url, 'title' => lang('content_layout_' . $content['layout']), 'width' => 70));
            $new_content = array(
                'content_id' => $content['content_id'],
                'photo' => $photo,
                'layout' => $layout,
                'page_id' => $pages[$content['page_id']],
                'display_order' => $content['display_order'],
            );
            $fields = $this->get_multi_lang_fields(array('content'));
            foreach ($fields as $field) {
                $new_content[$field] = character_limiter(strip_tags($content[$field]), 100);
            }
            $content = $new_content;
        }

        return $content;
    }

    public function remove($id, $redirect = TRUE) {
        // Xóa photo của content
        $content = $this->get_object($id);
        @unlink($this->upload_config['upload_path'] . $content['url']);
        parent::remove($id, $redirect);
    }

    private function _get_all_pages($extra_select = FALSE) {
        $ret = $this->page_model->get_all('display_order');
        $pages = array();
        if (!empty($extra_select)) {
            $pages[""] = $extra_select;
        }
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            foreach ($ret['data'] as $page) {
                $pages[$page['page_id']] = $page['name_' . $this->_current_lang];
            }
        }
        return $pages;
    }

    protected function handle_move($id, $direction = 'up', $filter_by = FALSE) {
        parent::handle_move($id, $direction, 'page_id');
    }

}
