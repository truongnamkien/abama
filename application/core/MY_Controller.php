<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {

    public $_language_identity_key = 'kientrinhspa:language:';
    public $_current_lang = FALSE;
    public $_masterview_enabled = TRUE;
    public $_masterview = 'masterpage';
    public $_global_vars = array(
        'PAGE_TITLE' => '',
        'PAGE_CONTENT' => '',
        'PAGE_FOOTER' => '',
        'PAGE_LANG' => '',
    );

    public function __construct() {
        parent::__construct();
        // set header de prevent cache
        $this->output->set_header("Cache-Control: no-cache, must-revalidate");
        $this->output->set_header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
        $this->form_validation->CI = & $this;

        date_default_timezone_set('Asia/Bangkok');

        // Load lại theo ngôn ngữ thích hợp
        if (empty($this->_current_lang)) {
            $language = $this->session->userdata($this->_language_identity_key);
        } else {
            $language = $this->_current_lang;
        }
        if (!empty($language)) {
            $this->config->set_item('language', $language);
        }
        $this->_global_vars['PAGE_LANG'] = $this->_current_lang = $this->config->item('language');
        $this->load->language(array('authen', 'error', 'product', 'static_content'));
        $this->_global_vars['PAGE_FOOTER'] = lang('authen_copyright', '', Date("Y"), site_url());
    }

    public function set_title($title) {
        $this->_global_vars['PAGE_TITLE'] = $title;
    }

    public function enable_masterview() {
        $this->_masterview_enabled = TRUE;
    }

    public function disable_masterview() {
        $this->_masterview_enabled = FALSE;
    }

    /**
     *
     * @param type $params
     * @return type
     */
    protected function _collect($params) {
        $this->load->helper('clear');
        if (is_array($params)) {
            foreach ($params as $item) {
                $result[$item] = $this->input->get_post($item, TRUE);
            }
        } else {
            $result = $this->input->get_post($params, TRUE);
        }

        return clear_my_ass($result);
    }

}

/**
 * Controller hỗ trợ xử lý ajax
 */
class MY_Ajax extends MY_Controller {

    protected $_data = array();

    public function __construct() {
        parent::__construct();

        $this->_data['status'] = 0;
        $this->_data['onload'] = array();

        $this->load->library('MY_Asyncresponse');
        $this->response = $this->my_asyncresponse;
        $this->_masterview_enabled = FALSE;

        header("Content-type: text/html; charset=utf-8");
    }

}

class MY_Outer_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('static_content_model'));
        $value = Modules::run('construction/_static_content', 'construct', 'config');
        if ($value == STATUS_ACTIVE) {
            redirect('construction');
        }
    }

}

class MY_Admin_Controller extends MY_Controller {

    public function __construct() {
        $this->_current_lang = 'vietnamese';
        parent::__construct();
        if ($this->my_auth->logged_in()) {
            $this->_masterview = 'admin_masterpage_logged';
        } else {
            $this->_masterview = 'admin_masterpage_not_logged';
        }
        $this->load->language(array('admin'));
        $this->set_title(lang('manager_title'));
    }

}

/**
 * Controller dành cho phần Admin, yêu cầu login
 */
abstract class MY_Inner_Admin_Controller extends MY_Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->my_auth->login_required();

        $this->data = array();
        $this->data['per_page'] = 10;
        $this->data['search_fields'] = array();
        $this->data['select_fields'] = array();
    }

    public function index($offset = 0) {
        $filter = array();
        $posts = $this->input->post();
        $posts = empty($posts) ? array() : $posts;
        $gets = $this->input->get();
        $gets = empty($gets) ? array() : $gets;
        $inputs = array_merge($posts, $gets);

        $keyword = isset($inputs['keyword']) ? $inputs['keyword'] : '';
        $fields = $this->data['search_fields'];

        if ($keyword !== '' && !empty($fields)) {
            foreach ($fields as $field) {
                $filter[] = $field . ' LIKE \'%' . $keyword . '%\'';
            }
            if (!empty($filter)) {
                $filter = '(' . implode(' OR ', $filter) . ')';
            }
        } else {
            $filter = '';
        }

        $ret = $this->_parse_select_filter($inputs, $filter);
        $filter = $ret['filter'];
        $query = $ret['query'];

        $this->data['offset'] = $offset;
        $objects = $this->get_all_objects($filter, $offset);
        $data['keyword'] = $keyword;
        $data['objects'] = $objects['objects'];
        $data['total_rows'] = $objects['total'];
        $data['type'] = $this->data['type'];
        $data['search_fields'] = $this->data['search_fields'];
        $data['select_fields'] = $this->data['select_fields'];

        $url_query = '';
        if (!empty($keyword)) {
            $url_query .= 'keyword=' . $keyword . '&';
        }
        if (!empty($query)) {
            $url_query .= $query;
        }

        $config['base_url'] = site_url('admin/' . $this->data['type'] . '/index/');
        $config['total_rows'] = $data['total_rows'];
        $config['per_page'] = $this->data['per_page'];
        $config['suffix'] = empty($url_query) ? '' : '?' . $url_query;
        $config['first_url'] = site_url('admin/' . $this->data['type'] . '/index/' . $config['suffix']);
        $config['uri_segment'] = '4';
        $config['anchor_class'] = 'class="number"';
        $config['first_link'] = lang('admin_first_link');
        $config['last_link'] = lang('admin_last_link');
        $config['next_link'] = lang('admin_next_link');
        $config['prev_link'] = lang('admin_prev_link');
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $data['pagination'] = TRUE;

        $data['mass_action_options'] = $this->set_mass_action_options();
        $data['main_nav'] = $this->_main_nav('index');

        $this->load->view('list_view', $data);
    }

    public function show($id = FALSE) {
        $data['object'] = $this->get_object($id, TRUE);
        $data['id'] = $id;
        $data['type'] = $this->data['type'];
        $data['main_nav'] = $this->_main_nav('show', $id);
        $this->load->view('show_view', $data);
    }

    public function create() {
        $this->create_update();
    }

    public function update($id) {
        $this->create_update($id);
    }

    public function update_status($id, $status = STATUS_ACTIVE) {
        if ($this->data['has_activation']) {
            if ($status == STATUS_ACTIVE || $status == STATUS_INACTIVE) {
                $model_name = $this->data['type'] . '_model';
                $this->$model_name->update($id, array('status' => $status));
            }
            set_notice_message('success', lang('admin_update_success'));
        } else {
            set_notice_message('danger', lang('admin_update_error'));
        }
        redirect(site_url('admin/' . $this->data['type']));
    }

    public function remove($id, $redirect = TRUE) {
        $model_name = $this->data['type'] . '_model';
        $this->$model_name->delete($id);
        if ($redirect) {
            set_notice_message('success', lang('admin_remove_success'));
            redirect(site_url('admin/' . $this->data['type']));
        }
    }

    public function move_up($id, $offset = 0) {
        $this->handle_move($id);
        redirect(site_url('admin/' . $this->data['type'] . '/index/' . $offset));
    }

    public function move_down($id, $offset = 0) {
        $this->handle_move($id, 'down');
        redirect(site_url('admin/' . $this->data['type'] . '/index/' . $offset));
    }

    protected function handle_move($id, $direction = 'up', $filter_by = FALSE) {
        if ($this->data['has_order']) {
            if ($direction !== 'up' && $direction !== 'down') {
                $direction = 'up';
            }
            $object = $this->get_object($id);

            // Nếu không có field display_order thì thôi
            if (!isset($object['display_order'])) {
                redirect(site_url('admin/' . $this->data['type']));
            }

            if ($filter_by !== FALSE && !isset($object[$filter_by])) {
                $filter_by = FALSE;
            }
            $model_name = $this->data['type'] . '_model';

            // Lấy object kế bên
            $query = 'display_order ' . ($direction == 'up' ? '<' : '>') . ' ' . $object['display_order'];
            if ($filter_by !== FALSE) {
                $query .= ' AND (' . $filter_by . ' = ' . $object[$filter_by];
                if (empty($object[$filter_by])) {
                    $query .= ' OR ' . $filter_by . ' IS NULL';
                }
                $query .= ')';
            }
            $related_object = $this->$model_name->get_where($query, 'display_order', ($direction == 'up' ? 'desc' : 'asc'));
            if ($related_object['return_code'] == API_SUCCESS && !empty($related_object['data'])) {
                $related_object = $related_object['data'];
                $related_object = array_shift($related_object);
            } else {
                $related_object = array();
            }

            if (!empty($related_object)) {
                $this->$model_name->update($id, array('display_order' => -1));
                $this->$model_name->update($related_object[$this->data['type'] . '_id'], array('display_order' => $object['display_order']));
                $this->$model_name->update($id, array('display_order' => $related_object['display_order']));
                set_notice_message('success', lang('admin_update_success'));
            }
        } else {
            set_notice_message('danger', lang('admin_update_error'));
        }
    }

    public function mass() {
        $params = $this->handle_post_inputs();
        $ids = explode(',', $params['ids']);
        $action = $params['mass_action_dropdown'];
        if ($action == 'remove') {
            $model_name = $this->data['type'] . '_model';
            foreach ($ids as $id) {
                $this->remove($id, FALSE);
            }
            set_notice_message('success', lang('admin_remove_success'));
        }
        redirect(site_url('admin/' . $this->data['type']));
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
            $this->handle_create_update_object($params, $this->data['action'], $id);
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

    protected function set_mass_action_options() {
        $actions = array('remove');
        $options = array();
        foreach ($actions as $action) {
            $options[$action] = lang('admin_mass_' . $action);
        }
        return $options;
    }

    protected function set_actions($id) {
        $type = $this->data['type'];
        $actions = array(
            'update' => array(
                'url' => site_url('admin/' . $type . '/update/' . $id),
                'button' => 'success',
                'icon' => 'edit'
            ),
            'show' => array(
                'url' => site_url('admin/' . $type . '/show/' . $id),
                'button' => 'success',
                'icon' => 'zoom-in'
            ),
            'remove' => array(
                'url' => site_url('admin/' . $type . '/remove/' . $id),
                'button' => 'danger',
                'icon' => 'trash'
            ),
        );
        if ($this->data['has_order']) {
            $actions['move_up'] = array(
                'url' => site_url('admin/' . $type . '/move_up/' . $id . '/' . $this->data['offset']),
                'button' => 'info',
                'icon' => 'arrow-up'
            );
            $actions['move_down'] = array(
                'url' => site_url('admin/' . $type . '/move_down/' . $id . '/' . $this->data['offset']),
                'button' => 'info',
                'icon' => 'arrow-down'
            );
        }
        if ($this->data['has_activation']) {
            $object = $this->get_object($id);
            if (isset($object['status'])) {
                if ($object['status'] == STATUS_ACTIVE) {
                    $actions['deactivate'] = array(
                        'url' => site_url('admin/' . $type . '/update_status/' . $id . '/' . STATUS_INACTIVE),
                        'button' => 'danger',
                        'icon' => 'remove'
                    );
                } else if ($object['status'] == STATUS_INACTIVE) {
                    $actions['activate'] = array(
                        'url' => site_url('admin/' . $type . '/update_status/' . $id . '/' . STATUS_ACTIVE),
                        'button' => 'success',
                        'icon' => 'ok'
                    );
                }
            }
        }
        return $actions;
    }

    protected function get_all_objects($filter, $offset = 0, $sort_by = FALSE, $sort_order = 'asc') {
        $limit = $this->data['per_page'];
        $model_name = $this->data['type'] . '_model';
        if ($sort_order !== 'asc' && $sort_order !== 'desc') {
            $sort_order = 'asc';
        }
        if (is_string($filter) && empty($filter)) {
            $filter = array();
        }

        $objects = $this->$model_name->get_where($filter, $sort_by, $sort_order);

        $result = array();
        $total = 0;
        if ($objects['return_code'] == API_SUCCESS && !empty($objects['data'])) {
            $objects = $objects['data'];
            $total = count($objects);
            $objects = array_slice($objects, $offset, $limit);
            if ($this->data['has_order']) {
                $max_display_order = $this->get_available_display_order() - 1;
            }

            foreach ($objects as $obj) {
                $id = $obj[get_object_key($obj, $this->data['type'])];
                $actions = $this->set_actions($id);
                if ($this->data['has_order']) {
                    if (count($objects) <= 1 || !isset($obj['display_order'])) {
                        unset($actions['move_up']);
                        unset($actions['move_down']);
                    } else if ($obj['display_order'] <= 1) {
                        unset($actions['move_up']);
                    } else if ($obj['display_order'] >= $max_display_order) {
                        unset($actions['move_down']);
                    }
                }

                $result[] = array_merge($obj, array('actions' => $actions));
            }
        }

        return array(
            'objects' => $result,
            'total' => $total
        );
    }

    protected function get_object($id = FALSE, $parsed = FALSE) {
        if ($id == FALSE || !is_numeric($id)) {
            set_notice_message('danger', lang('empty_list_data'));
            redirect(site_url('admin/' . $this->data['type']));
        } else {
            $model_name = $this->data['type'] . '_model';
            $object = $this->$model_name->get($id);
            if ($object['return_code'] !== API_SUCCESS || empty($object['data'])) {
                set_notice_message('danger', lang('empty_list_data'));
                redirect(site_url('admin/' . $this->data['type']));
            }
        }
        return $object['data'];
    }

    protected function count_all_objects() {
        $model_name = $this->data['type'] . '_model';
        return $this->$model_name->count_all();
    }

    protected function handle_post_inputs() {
        $params = array();
        foreach ($this->input->post() as $key => $value) {
            if ($value !== FALSE) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    protected function handle_create_update_object($params, $action, $id = FALSE) {
        if ($this->data['has_order']) {
            if ($action == 'create' && $id == FALSE && (!isset($params['display_order']) || empty($params['display_order']))) {
                $params['display_order'] = $this->get_available_display_order();
            } else if ($action == 'update' && $id !== FALSE && isset($params['display_order']) && !empty($params['display_order'])) {
                $model_name = $this->data['type'] . '_model';
                if ($params['display_order'] >= $this->$model_name->count_all()) {
                    redirect(site_url('admin/' . $this->data['type']));
                }
            }
        }
        unset($params['submit']);
        $model_name = $this->data['type'] . '_model';

        if ($id !== FALSE) {
            $ret = $this->$model_name->$action($id, $params);
            if ($ret['return_code'] == API_SUCCESS) {
                set_notice_message('success', lang('admin_update_success'));
                redirect(site_url('admin/' . $this->data['type'] . '/show/' . $id));
            }
        } else {
            $ret = $this->$model_name->$action($params);
            if ($ret['return_code'] == API_SUCCESS) {
                set_notice_message('success', lang('admin_create_success'));
                redirect(site_url('admin/' . $this->data['type']));
            }
        }
        set_notice_message('danger', lang('admin_update_error'));
    }

    protected function _main_nav($page = 'index', $id = '') {
        $nav_list = array();
        $type = $this->data['type'];
        if ($page == 'index') {
            $nav_list['create'] = array(
                'url' => site_url('admin/' . $type . '/create'),
                'icon' => 'plus'
            );
        } else {
            $nav_list['back_list'] = array(
                'url' => site_url('admin/' . $type),
                'icon' => 'list-alt'
            );
        }
        if ($page == 'show') {
            $nav_list['update'] = array(
                'url' => site_url('admin/' . $type . '/update/' . $id),
                'icon' => 'edit'
            );
            $nav_list['remove'] = array(
                'url' => site_url('admin/' . $type . '/remove/' . $id),
                'icon' => 'trash'
            );
        }
        if ($page == 'update') {
            $nav_list['show'] = array(
                'url' => site_url('admin/' . $type . '/show/' . $id),
                'icon' => 'zoom-in'
            );
        }
        return $nav_list;
    }

    protected function get_available_display_order() {
        if ($this->data['has_order']) {
            $model_name = $this->data['type'] . '_model';
            return $this->$model_name->get_available_display_order();
        }
        return FALSE;
    }

    protected function _parse_select_filter($inputs, $filter) {
        $select_filter = array();
        $query = array();

        if (!empty($this->data['select_fields'])) {
            foreach ($this->data['select_fields'] as $field => $options) {
                $value = isset($inputs[$field]) ? $inputs[$field] : '';
                if ($value !== '') {
                    if (is_array($filter)) {
                        $select_filter[$field] = $value;
                    } else {
                        $select_filter[] = $field . ' = \'' . $value . '\'';
                    }
                    $query[] = $field . '=' . $value;
                }
            }
            if (!empty($select_filter)) {
                if (is_array($filter)) {
                    $filter = array_merge($filter, $select_filter);
                } else {
                    $select_filter = implode(' AND ', $select_filter);
                    if (!empty($filter)) {
                        $filter .= ' AND ';
                    }
                    $filter .= $select_filter;
                }
            }
        }
        if (!empty($query)) {
            $query = implode('&', $query);
        } else {
            $query = '';
        }
        return array('filter' => $filter, 'query' => $query);
    }

    protected function get_multi_lang_fields($fields) {
        $this->load->config('content_page', TRUE);
        $lang_list = $this->config->item('language_list', 'content_page');
        $ret = array();
        foreach ($fields as $field) {
            foreach ($lang_list as $lang) {
                $ret[] = $field . '_' . $lang;
            }
        }
        return $ret;
    }

    protected function set_validation_rules($action) {
        $rules = array();
        return $rules;
    }

    abstract protected function prepare_object($id = FALSE);
}
