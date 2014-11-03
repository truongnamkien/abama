<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Keyword extends MY_Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->my_auth->login_required();
        $this->load->model(array('keyword_model'));
        $this->load->language(array('keyword'));
        $this->set_title(lang('manager_keyword'));
    }

    public function update() {
        $target_id = $this->input->get_post('target_id');
        $target_type = $this->input->get_post('target_type');

        $model = $target_type . '_model';
        if (!file_exists(APPPATH . "models/$model.php")) {
            set_notice_message('danger', lang('empty_list_data'));
            redirect('admin');
        }

        $this->load->model($model);
        $object = $this->$model->get($target_id);
        if ($object['return_code'] != API_SUCCESS || empty($object['data'])) {
            set_notice_message('danger', lang('empty_list_data'));
            redirect('admin');
        }
        $keyword_info = $this->keyword_model->get_where(array('target_id' => $target_id, 'target_type' => $target_type));
        if ($keyword_info['return_code'] == API_SUCCESS && !empty($keyword_info['data'])) {
            $keyword_info = $keyword_info['data'];
        } else {
            $keyword_info = array();
        }

        if ($keyword_content = $this->input->post('content')) {
            $keyword_content = strip_tags(trim($keyword_content));
            if (!empty($keyword_info)) {
                $this->keyword_model->update(FALSE, array('target_id' => $target_id, 'target_type' => $target_type), array('content' => $keyword_content));
            } else {
                $this->keyword_model->create(array(
                    'target_id' => $target_id,
                    'target_type' => $target_type,
                    'content' => $keyword_content
                ));
            }
            set_notice_message('success', lang('admin_update_success'));
        } else {
            $keyword_content = isset($keyword_info['content']) ? $keyword_info['content'] : "";
        }
        $data = array(
            'target_id' => $target_id,
            'target_type' => $target_type,
            'content' => $keyword_content
        );

        $hidden_inputs = "<input type='hidden' name='target_type' value='" . $target_type . "' />";
        $hidden_inputs .= "<input type='hidden' name='target_id' value='" . $target_id . "' />";
        $target_url = call_user_func($target_type . '_url', $target_id) . '&display=1';

        $this->data = array(
            'main_nav' => array(),
            'type' => 'keyword',
            'action' => 'update',
            'form_data' => array(
                'object' => array(
                    'target' => $hidden_inputs . "<a target='_blank' href='" . $target_url . "'>" . $target_url . "</a>",
                    'content' => $keyword_content,
                ),
                'specific_input' => array(
                    'target' => array('input' => 'none')
                ),
            )
        );
        $this->load->view('create_update_view', $this->data);
    }

}
