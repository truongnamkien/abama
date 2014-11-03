<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_Ajax extends MY_Ajax {

    public function __construct() {
        parent::__construct();

        $this->my_auth->login_required(TRUE);

        $this->load->config('upload', TRUE);
        $this->load->config('content_page', TRUE);
        $this->load->model(array('product_photo_model'));
        $this->upload_config = $this->config->config['upload'];
        $this->load->helper(array('file'));
        $this->load->language(array('product'));
    }

    /**
     * Render html preview
     */
    public function render($file = FALSE) {
        if (empty($file)) {
            $file = $this->input->get_post('file');
        }

        $uploaded_file_fullpath = $this->upload_config['multi_file_upload_path'] . $file;
        $file_extension = pathinfo($uploaded_file_fullpath, PATHINFO_EXTENSION);
        if (glob($uploaded_file_fullpath) == FALSE) {
            $this->response->run("show_alert('" . lang('product_photo_upload_error') . "');");
            $this->response->send();
        }

        // Generate tên file
        do {
            $photo_name = '';
            while (strlen($photo_name) < $this->upload_config['max_name_length']) {
                $photo_name .= random_string('alnum', 1);
            }
        } while (file_exists($this->upload_config['product_photo_path'] . $photo_name . '.' . $file_extension));

        if ($handle = opendir($this->upload_config['product_photo_path'])) {
            rename($uploaded_file_fullpath, $this->upload_config['product_photo_path'] . $photo_name . '.' . $file_extension);
            closedir($handle);
        }
        @unlink($uploaded_file_fullpath);

        $this->create_product_photo($photo_name);
    }

    /**
     * Remove hình Sản phẩm hay hình banner
     * @param type $id
     * @param type $type
     * @return type
     */
    public function remove_upload($id = FALSE, $type = 'product_photo') {
        if (empty($id)) {
            return;
        }
        if ($type == 'product_photo') {
            Modules::run('admin/product/_remove_photo', $id);
        } else {
            $this->remove_banner($id);
        }

        $this->response->run("
            $('#file_$id').fadeTo(500, 0, function () { $(this).remove(); });
            ");
        $this->response->send();
    }

    /**
     * Render banner trong trang setting home
     * @param type $file
     */
    public function render_banner($file = FALSE) {
        if (empty($file)) {
            $file = $this->input->get_post('file');
        }
        $this->load->model(array('static_content_model'));

        $uploaded_file_fullpath = $this->upload_config['multi_file_upload_path'] . $file;
        $file_extension = pathinfo($uploaded_file_fullpath, PATHINFO_EXTENSION);

        // Generate tên file
        do {
            $photo_name = '';
            while (strlen($photo_name) < $this->upload_config['max_name_length']) {
                $photo_name .= random_string('alnum', 1);
            }
        } while (file_exists($this->upload_config['content_photo_path'] . $photo_name . '.' . $file_extension));

        $content = array(
            'page' => 'home',
            'content_name' => 'slide_images',
            'type' => Static_Content_Model::STATIC_CONTENT_TYPE_IMAGE,
        );
        $photo_path = $this->upload_config['content_photo_path'] . $photo_name;
        rename($uploaded_file_fullpath, $photo_path . '.' . $file_extension);
        $content['content'] = $photo_path;

        $ret = $this->static_content_model->create($content);
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            $html = Modules::run('admin/upload/render_banner', $ret['data']['static_content_id']);
            $this->response->append('#uploaded_files', $html);
        }

        $this->response->send();
    }

    /**
     * Remove banner trang home
     * @param type $id
     */
    public function remove_banner($id) {
        $this->load->model(array('static_content_model'));
        $content = $this->static_content_model->get($id);
        if ($content['return_code'] == API_SUCCESS && !empty($content['data'])) {
            $content = $content['data'];
            $photo_name = $content['content'] . '*.*';

            $file_list = glob($photo_name);
            if (!empty($file_list)) {
                foreach ($file_list as $photo_path) {
                    @unlink($photo_path);
                }
            }
        }
        $this->static_content_model->delete($id);
    }

    /**
     * Add watermark vào hình
     * @param type $original_image
     * @param type $original_watermark
     * @param type $destination
     */
    private function _watermark($original_image, $original_watermark, $destination = "") {
        $filetype = substr($original_image, strlen($original_image) - 4, 4);
        $filetype = strtolower($filetype);
        if ($filetype == ".gif") {
            $image = @imagecreatefromgif($original_image);
        } else if ($filetype == ".jpg" || $filetype == "jpeg") {
            $image = @imagecreatefromjpeg($original_image);
        } else if ($filetype == ".png") {
            $image = @imagecreatefrompng($original_image);
        }
        list($imagewidth, $imageheight) = getimagesize($original_image);

        $watermark = imagecreatefrompng($original_watermark);
        list($watermarkwidth, $watermarkheight) = getimagesize($original_watermark);

        if ($imagewidth < $this->upload_config['origin_width']) {
            $water_resize_factor = $imagewidth / $this->upload_config['origin_width'];
            $new_watermarkwidth = $watermarkwidth * $water_resize_factor;
            $new_watermarkheight = $watermarkheight * $water_resize_factor;

            $new_watermark = imagecreatetruecolor($new_watermarkwidth, $new_watermarkheight);

            imagealphablending($new_watermark, false);
            imagecopyresampled($new_watermark, $watermark, 0, 0, 0, 0, $new_watermarkwidth, $new_watermarkheight, $watermarkwidth, $watermarkheight);

            $watermarkwidth = $new_watermarkwidth;
            $watermarkheight = $new_watermarkheight;
            $watermark = $new_watermark;
        }
        $startwidth = ($imagewidth - $watermarkwidth) - 10;
        $startheight = ($imageheight - $watermarkheight) - 10;

        imagecopy($image, $watermark, $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
        if (!empty($destination)) {
            imagejpeg($image, $destination);
        } else {
            imagejpeg($image);
        }
    }

    /**
     * Chọn từ những hình Sản phẩm đã upload để làm hình Sản phẩm
     * @param type $photo_name
     */
    public function select_photo($photo_name) {
        $this->response->run("$(this.getRelativeTo()).parent('div').remove();");
        $this->create_product_photo($photo_name);
    }

    /**
     * Lưu hình Sản phẩm vào database
     * @param type $photo_name
     */
    public function create_product_photo($photo_name) {
        $product_photo = array();
        $product_photo['url'] = $this->upload_config['product_photo_path'] . $photo_name;

        $ret = $this->product_photo_model->create($product_photo);
        if ($ret['return_code'] == API_SUCCESS && !empty($ret['data'])) {
            $product_photo_id = $ret['data']['product_photo_id'];
            $html = Modules::run('admin/upload/render', $product_photo_id);
            $this->response->append('#uploaded_files', $html);
        }
        $this->response->send();
    }

}
