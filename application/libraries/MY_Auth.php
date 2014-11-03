<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Auth {

    private static $CI = NULL;
    private $admin_identity_key = 'admin:username';

    /**
     * Constructor for this class.
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper('cookie');
        $this->CI->load->library(array('session'));
    }

    public function login_required() {
        if (!$this->logged_in()) {
            redirect('admin/login');
        }
        return TRUE;
    }

    /**
     * login
     * Thực hiện đăng nhập với một user nào đó.
     * @param string $username
     * @param string $password
     */
    public function login($username, $password) {
        $this->CI->config->load('admin', TRUE);
        $this->CI->load->model('static_content_model');
        $content = $this->CI->static_content_model->get_where(array('page' => 'config', 'content_name' => 'username_password'));
        $system_pass = FALSE;
        if ($content['return_code'] == API_SUCCESS && !empty($content['data'])) {
            $system_pass = array_shift($content['data']);
            $system_pass = $system_pass['content'];
        }
        if (empty($system_pass)) {
            $system_pass = $this->CI->config->item('admin_password', 'admin');
        }

        if ($username == $this->CI->config->item('admin_username', 'admin') && $password == $system_pass) {
            $this->CI->session->set_userdata($this->admin_identity_key, $username);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * logout
     * Đăng xuất khỏi hệ thống.
     */
    public function logout() {
        $this->CI->session->sess_destroy();
    }

    /**
     * logged_in
     * Kiểm tra xem login chưa ??
     * @return bool
     */
    public function logged_in() {
        $session = $this->CI->session->userdata($this->admin_identity_key);
        if (!empty($session)) {
            return TRUE;
        }

        return FALSE;
    }

}
