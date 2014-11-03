<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Inner_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'home';
        $this->data['has_order'] = FALSE;
        $this->data['has_activation'] = FALSE;
        $this->load->model(array('static_content_model'));
        $this->load->language(array('static_content'));
        $this->set_title(lang('manager_title') . ' - ' . lang('static_content_title'));
        $this->load->config('content_page', TRUE);

        // Config cho phần upload
        $this->load->config('upload', TRUE);
        $this->upload_config = $this->config->item('photo', 'upload');
        $this->upload_config['upload_path'] = $this->config->item('content_photo_path', 'upload');

        $this->load->library('upload', $this->upload_config);
    }

    protected function set_validation_rules($action) {
        $rules = array(
            array('field' => 'content_name', 'label' => lang('home_content_name'), 'rules' => 'required'),
        );

        return $rules;
    }

    protected function prepare_object($id = FALSE) {
        $object = array(
            'photo' => '',
            'new_photo' => '',
            'content_name' => $this->input->post('content_name'),
        );

        if ($id !== FALSE) {
            $object = array_merge($object, $this->get_object($id));
        }
        
        if (isset($object['photo']) && empty($object['photo'])) {
            unset($object['photo']);
        }
        if (isset($object['photo'])) {
            $photo_url = Modules::run('photo/_get_photo_path', $object['photo'], 900);
            $photo_url = base_url($photo_url);
            $photo = img(array('src' => $photo_url, 'width' => 70));
            $url = $object['photo'];
            $object['photo'] = anchor($photo_url, $photo, array('class' => 'colorbox'));
            $object['photo'] .= "<input type='hidden' name='photo' value='$url' />";
        }
        
        $banner_category = $this->config->item('banner_category', 'content_page');
        $ret = array();
        foreach ($banner_category as $category => $cate_data) {
            $ret[$category] = lang($category);
        }

        $specific_input = array(
            'photo' => array('input' => 'none'),
            'new_photo' => array('input' => 'upload'),
            'content_name' => array('input' => 'dropdown', 'options' => $ret),
        );
        unset($object['static_content_id']);
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

            $params = $this->_handle_upload_photo($params);
            if ($id !== FALSE) {
                $content = $this->get_object($id);
                if (isset($params['photo']) && !empty($content['photo']) && $params['photo'] != $content['photo']) {
                    @unlink($this->upload_config['upload_path'] . $content['photo']);
                }
            }

            if (is_string($params)) {
                set_notice_message('danger', $params);
            } else if ((!isset($params['photo']) || empty($params['photo'])) && (empty($id) || empty($content['photo']))) {
                set_notice_message('danger', lang('home_photo_empty_error'));
            } else {
                if (!isset($params['photo']) || empty($params['photo'])) {
                    $params['photo'] = $content['photo'];
                }
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
        unset($this->data['main_nav']['home_show']);
        $this->load->view('create_update_view', $this->data);
    }

    private function _handle_upload_photo($params) {
        if (empty($_FILES['new_photo']['name'])) {
            return $params;
        }
        if (!$this->upload->do_upload('new_photo')) {
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

        $params['photo'] = $this->upload_config['upload_path'] . $photo_name;
        rename($this->upload_config['upload_path'] . $photo['file_name'], $params['photo']);
        unset($params['new_photo']);
        @unlink($photo['full_path']);
        return $params;
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $limit = $this->data['per_page'];
        $model_name = 'static_content_model';
        if ($sort_order !== 'asc' && $sort_order !== 'desc') {
            $sort_order = 'asc';
        }
        $filter = array(
            'page' => 'home',
        );
        $objects = $this->$model_name->get_where($filter, $sort_by, $sort_order);

        $result = array();
        $total = 0;
        if ($objects['return_code'] == API_SUCCESS && !empty($objects['data'])) {
            $objects = $objects['data'];
            $total = count($objects);
            $objects = array_slice($objects, $offset, $limit);

            foreach ($objects as $obj) {
                $id = $obj['static_content_id'];
                $data = json_decode($obj['content'], TRUE);
                unset($obj['content']);
                $obj['photo'] = $data['photo'];
                $obj['content_name'] = lang($obj['content_name']);
                unset($obj['page']);
                unset($obj['type']);

                $photo_url = Modules::run('photo/_get_photo_path', $obj['photo'], 900);
                $photo_url = base_url($photo_url);
                $photo = img(array('src' => $photo_url, 'width' => 70));
                $photo = anchor($photo_url, $photo, array('class' => 'colorbox'));
                $obj['photo'] = $photo;
                $actions = $this->set_actions($id);
                unset($actions['show']);
                $obj['actions'] = $actions;
                $result[] = $obj;
            }
        }

        return array(
            'objects' => $result,
            'total' => $total
        );
    }

    public function show($id = FALSE) {
        set_notice_message('danger', lang('empty_list_data'));
        redirect(site_url('admin/home'));
    }

    public function remove($id, $redirect = TRUE) {
        // Xóa photo của content
        $content = $this->get_object($id);
        @unlink($this->upload_config['upload_path'] . $content['photo']);
        $this->static_content_model->delete($id);
        if ($redirect) {
            set_notice_message('success', lang('admin_remove_success'));
            redirect(site_url('admin/home'));
        }
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        if ($id == FALSE || !is_numeric($id)) {
            set_notice_message('danger', lang('empty_list_data'));
            redirect(site_url('admin/home'));
        } else {
            $model_name = 'static_content_model';
            $object = $this->$model_name->get($id);
            if ($object['return_code'] !== API_SUCCESS || empty($object['data'])) {
                set_notice_message('danger', lang('empty_list_data'));
                redirect(site_url('admin/home'));
            }
        }
        $object = $object['data'];
        $data = json_decode($object['content'], TRUE);
        unset($object['content']);
        $object['photo'] = $data['photo'];
        $object['content_name'] = lang($object['content_name']);
        unset($object['page']);
        unset($object['type']);

        return $object;
    }

    protected function handle_create_update_object($params, $action, $id = FALSE) {
        unset($params['submit']);
        $model_name = 'static_content_model';
        $object = array();
        $object['photo'] = $params['photo'];
        unset($params['photo']);
        $object = json_encode($object);
        $params['content'] = $object;
        $params['page'] = 'home';
        $params['type'] = Static_Content_Model::STATIC_CONTENT_TYPE_TEXT;

        if ($id === FALSE) {
            $ret = $this->$model_name->$action($params);
        } else {
            $ret = $this->$model_name->$action($id, $params);
        }
        if ($ret['return_code'] == API_SUCCESS) {
            set_notice_message('success', lang('admin_' . $action . '_success'));
            redirect(site_url('admin/' . $this->data['type']));
        }
        set_notice_message('danger', lang('admin_update_error'));
    }

}
