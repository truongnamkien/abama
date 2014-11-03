<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends MY_Outer_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language(array('content', 'page'));
        $this->load->model(array('content_model', 'page_model'));
        $this->load->config('content_page', TRUE);
    }

    /**
     * Trang show detail của page
     */
    public function detail() {
        $page = $this->_get_page();
        $this->set_title($page['name_' . $this->_current_lang]);

        $data['page'] = $page;
        $this->load->view('page_view', $data);
    }

    /**
     * Load data của page
     * @return type
     */
    private function _get_page() {
        $page_id = $this->input->get_post('page_id');
        if ($page_id == FALSE || !is_numeric($page_id)) {
            show_404();
        } else {
            $page = $this->page_model->get($page_id);
            if ($page['return_code'] !== API_SUCCESS || empty($page['data']) || $page['data']['status'] !== STATUS_ACTIVE) {
                show_404();
            }
        }
        return $page['data'];
    }

    /**
     * HTML phần content của page
     * @param type $page_id
     * @param type $except_layout
     */
    public function _display_content($page_id, $except_layout = array()) {
        $contents = $this->content_model->get_where(array('page_id' => $page_id), 'display_order');
        $data['contents'] = array();
        if ($contents['return_code'] == API_SUCCESS && !empty($contents['data'])) {
            $contents = $contents['data'];

            $img_widths = $this->config->item('page_content_photo_default_width', 'content_page');
            foreach ($contents as $content) {
                if (!in_array($content['layout'], $except_layout) && $content['layout'] < count($img_widths)) {
                    $photo_path = FALSE;
                    $width = $img_widths[$content['layout']];
                    if (!empty($content['url'])) {
                        $photo_path = Modules::run('photo/_get_photo_path', $content['url'], $width);
                    } else {
                        $content['layout'] = 0;
                    }
                    $data['contents'][$content['content_id']] = $this->load->view('content_' . $content['layout'] . '_view', array('content' => $content['content_' . $this->_current_lang], 'photo' => $photo_path), TRUE);
                }
            }
        }
        $this->load->view('content_list_view', $data);
    }

}
