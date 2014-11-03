<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Outer_Controller {

    private $blog_per_page = 6;

    public function __construct() {
        parent::__construct();
        $this->load->language(array('blog', 'blog_content'));
        $this->load->model(array('blog_model', 'blog_content_model', 'blog_category_model'));
        $this->load->config('content_page', TRUE);
    }

    public function category($name = FALSE, $offset = 0) {
        $category = $this->_get_category();
        $this->set_title($category['name_' . $this->_current_lang]);

        $blog_list = $this->blog_model->get_where(array('status' => STATUS_ACTIVE, 'blog_category_id' => $category['blog_category_id']), 'created_at', 'desc');
        if ($blog_list['return_code'] == API_SUCCESS && !empty($blog_list['data'])) {
            $blog_list = $blog_list['data'];
        } else {
            $blog_list = array();
        }
        $config['base_url'] = site_url('blog/category/' . $name);
        $config['total_rows'] = count($blog_list);
        $config['per_page'] = $this->blog_per_page;
        $config['first_url'] = blog_category_url($category);
        $config['uri_segment'] = '4';
        $config['first_link'] = '&laquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&raquo;';
        $config['prev_link'] = '&laquo;';
        $config['suffix'] ='?blog_category_id=' . $category['blog_category_id'];

        $this->pagination->initialize($config);

        $data = array(
            'category' => $category,
            'blog_list' => array_slice($blog_list, $offset * $this->blog_per_page, $this->blog_per_page),
        );
        foreach ($data['blog_list'] as &$blog) {
            $content_list = $this->blog_content_model->get_where(array('blog_id' => $blog['blog_id']), 'display_order');
            if ($content_list['return_code'] == API_SUCCESS && !empty($content_list['data'])) {
                $content_list = $content_list['data'];
            } else {
                $content_list = array();
            }
            $photo_url = '';
            $content = FALSE;
            foreach ($content_list as $sub_content) {
                if (empty($content) && !empty($sub_content) && !empty($sub_content['content_' . $this->_current_lang]) && $sub_content['layout'] < 7) {
                    $content = $sub_content['content_' . $this->_current_lang];
                }
                if (empty($photo_url) && !empty($sub_content['url']) && !empty($sub_content['layout'])) {
                    $photo_url = Modules::run('photo/_get_photo_path', $sub_content['url'], 650);
                    $photo_url = base_url($photo_url);
                }
                if (!empty($content) && !empty($photo_url)) {
                    break;
                }
            }
            $blog['photo_url'] = $photo_url;
            $blog['content'] = $content;
        }

        $this->load->view('blog_list', $data);
    }

    /**
     * Trang show detail của blog
     */
    public function detail() {
        $blog = $this->_get_blog();
        $this->set_title($blog['title_' . $this->_current_lang]);

        $data['blog'] = $blog;
        $this->load->view('blog_view', $data);
    }

    public function _display_content($blog_id, $except_layout = array()) {
        $contents = $this->blog_content_model->get_where(array('blog_id' => $blog_id), 'display_order');
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
                    $data['contents'][$content['blog_content_id']] = $this->load->view('content_' . $content['layout'] . '_view', array('content' => $content['content_' . $this->_current_lang], 'photo' => $photo_path), TRUE);
                }
            }
        }
        $this->load->view('content_list_view', $data);
    }

    public function _pagelet_hot_blog() {
        $blog_list = $this->blog_model->get_where(array('status' => STATUS_ACTIVE, 'hot' => STATUS_ACTIVE), 'created_at', 'desc', 0, $this->blog_per_page);
        if ($blog_list['return_code'] == API_SUCCESS && !empty($blog_list['data'])) {
            $blog_list = $blog_list['data'];
        } else {
            $blog_list = array();
        }
        foreach ($blog_list as &$blog) {
            $content_list = $this->blog_content_model->get_where(array('blog_id' => $blog['blog_id']), 'display_order');
            if ($content_list['return_code'] == API_SUCCESS && !empty($content_list['data'])) {
                $content_list = $content_list['data'];
            } else {
                $content_list = array();
            }
            $content = FALSE;
            foreach ($content_list as $sub_content) {
                if (empty($content) && !empty($sub_content) && !empty($sub_content['content'])) {
                    $content = $sub_content['content'];
                }
            }
            $blog['content'] = $content;
        }
        return $this->load->view('pagelet_hot_blog', array('blog_list' => $blog_list), TRUE);
    }

    /**
     * Load data của blog
     * @return type
     */
    private function _get_blog() {
        $blog_id = $this->input->get_post('blog_id');
        if ($blog_id == FALSE || !is_numeric($blog_id)) {
            show_404();
        } else {
            $blog = $this->blog_model->get($blog_id);
            if ($blog['return_code'] !== API_SUCCESS || empty($blog['data']) || $blog['data']['status'] !== STATUS_ACTIVE) {
                show_404();
            }
        }
        return $blog['data'];
    }

    private function _get_category() {
        $category_id = $this->input->get_post('blog_category_id');
        if ($category_id == FALSE || !is_numeric($category_id)) {
            show_404();
        } else {
            $category = $this->blog_category_model->get($category_id);
            if ($category['return_code'] !== API_SUCCESS || empty($category['data'])) {
                show_404();
            }
        }
        return $category['data'];
    }

}
