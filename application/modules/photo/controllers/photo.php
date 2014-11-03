<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Photo extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('image_edit');
        $this->load->config('upload', TRUE);
    }

    /**
     * Kiểm tra xem hình có chưa, nếu chưa có thì tạo và trả về đường dẫn của hình
     * @param type $photo_url
     * @param type $target_width
     * @return boolean
     */
    public function _get_photo_path($photo_url, $target_width) {
        $photo_name = $this->_get_photo_name($photo_url);
        $cache_folder = $this->config->item('cache_photo_path', 'upload');

        // Check xem có cache hình chưa
        $ret = glob($cache_folder . $photo_name . '_' . $target_width . '*.*');
        if (!empty($ret)) {
            $photo_path = array_shift($ret);
            return $photo_path;
        }

        // Nếu chưa có cache thì tạo cache
        $raw_photo = glob($photo_url . '*.*');
        if (empty($raw_photo)) {
            return FALSE;
        }
        $raw_photo = array_shift($raw_photo);
        $size = @getimagesize($raw_photo);
        $original_width = $size[0];
        $original_height = $size[1];
        if ($original_width <= $target_width) {
            $photo_path = $raw_photo;
        } else {
            $target_heigth = $original_height * $target_width / $original_width;

            // Resize hình
            $resized_path = $this->image_edit->resize_image($raw_photo, $target_width, $target_heigth, TRUE, $photo_name);

            // Move qua folder cache
            $resized_name = $resized_path;
            while ($pos = strpos($resized_name, '/') !== FALSE) {
                $resized_name = substr($resized_name, $pos);
            }
            $photo_path = $cache_folder . $resized_name;
            rename($resized_path, $photo_path);
        }
        return $photo_path;
    }

    /**
     * Bỏ đường dẫn và chỉ lấy tên file
     * @param type $photo_path
     */
    private function _get_photo_name(&$photo_path) {
        $photo_name = $photo_path;
        while ($pos = strpos($photo_name, '/') !== FALSE) {
            $photo_name = substr($photo_name, $pos);
        }
        $extension = substr($photo_name, strlen($photo_name) - 3);
        if (in_array(strtolower($extension), array('jpg', 'png', 'gif'))) {
            $photo_name = substr($photo_name, 0, strlen($photo_name) - 4);
            $photo_path = substr($photo_path, 0, strlen($photo_path) - 4);
        }
        return $photo_name;
    }
    
}
