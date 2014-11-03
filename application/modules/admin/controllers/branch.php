<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'branch';
        $this->data['has_order'] = FALSE;
        $this->data['has_activation'] = FALSE;
        $this->load->model(array('branch_model'));
        $this->load->language(array('branch'));
        $this->set_title(lang('manager_title') . ' - ' . lang('manager_' . $this->data['type']));

        $this->data['search_fields'] = array('name');

        // Config cho phần upload
        $this->load->config('upload', TRUE);
        $this->upload_config = $this->config->item('photo', 'upload');
        $this->upload_config['upload_path'] = $this->config->item('content_photo_path', 'upload');
        $this->load->library('upload', $this->upload_config);
    }

    protected function set_validation_rules($action) {
        $rules = array(
            array('field' => 'name', 'label' => lang('branch_name'), 'rules' => 'trim|strip_tags|max_length[100]|required')
        );
        return $rules;
    }

    protected function prepare_object($id = FALSE) {
        $object = array(
            'url' => '',
            'photo' => '',
            'name' => $this->input->post('name'),
        );
        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
        }
        if (isset($object['url']) && empty($object['url'])) {
            unset($object['url']);
        }
        if (isset($object['url'])) {
            $photo_url = Modules::run('photo/_get_photo_path', $object['url'], 220);
            $photo_url = base_url($photo_url);
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $url = $object['url'];
            $object['url'] = anchor($photo_url, $photo, array('class' => 'colorbox'));
            $object['url'] .= "<input type='hidden' name='url' value='$url' />";
        }
        $specific_input = array(
            'url' => array('input' => 'none'),
            'photo' => array('input' => 'upload'),
        );

        unset($object['branch_id']);

        return array(
            'object' => $object,
            'specific_input' => $specific_input
        );
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        $object = parent::get_object($id, $parsed);
        if ($parsed) {
            $photo_url = '';
            if (isset($object['url']) && !empty($object['url'])) {
                $photo_url = Modules::run('photo/_get_photo_path', $object['url'], 220);
                $photo_url = base_url($photo_url);
            }
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $object['url'] = anchor($photo_url, $photo, array('class' => 'colorbox'));
        }
        return $object;
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $result = parent::get_all_objects($filter, $offset, $sort_by, $sort_order);

        foreach ($result['objects'] as &$obj) {
            $photo_url = '';
            if (isset($obj['url']) && !empty($obj['url'])) {
                $photo_url = Modules::run('photo/_get_photo_path', $obj['url'], 220);
                $photo_url = base_url($photo_url);
            }
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $obj['url'] = anchor($photo_url, $photo, array('class' => 'colorbox'));
        }
        return $result;
    }

    public function remove($id, $redirect = TRUE) {
        $branch = $this->get_object($id);
        @unlink($this->upload_config['upload_path'] . $branch['url']);
        parent::remove($id, $redirect);
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
            $params = $this->_handle_upload_photo($params);
            if ($id !== FALSE && isset($params['url'])) {
                $branch = $this->get_object($id);
                if (!empty($branch['url']) && $params['url'] != $branch['url']) {
                    @unlink($this->upload_config['upload_path'] . $branch['url']);
                }
            }

            if (is_string($params)) {
                set_notice_message('danger', $params);
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
            if (!isset($params['url']) || empty($params['url'])) {
                return lang('branch_photo_empty_error');
            } else {
                unset($params['url']);
                return $params;
            }
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

}
