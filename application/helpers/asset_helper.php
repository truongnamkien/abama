<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

if (!function_exists('asset_url')) {

    function asset_url($uri = '') {
        $CI = & get_instance();

        $asset_url = $CI->config->slash_item('asset_url');

        if ($uri == '')
            return $asset_url;

        return $asset_url . trim($uri, '/');
    }

}

if (!function_exists('asset_link_tag')) {

    function asset_link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '') {
        $CI = & get_instance();
        $link = '<link ';
        if (is_array($href)) {
            foreach ($href as $k => $v) {
                if ($k == 'href' AND strpos($v, '://') === FALSE) {
                    $link .= 'href="' . asset_url($v) . '" ';
                } else {
                    $link .= $k . '="' . $v . '" ';
                }
            }
        } else {
            if (strpos($href, '://') !== FALSE) {
                $link .= 'href="' . $href . '" ';
            } else {
                $link .= 'href="' . asset_url($href) . '" ';
            }
        }

        $link .= 'rel="' . $rel . '" type="' . $type . '" ';

        if ($media != '') {
            $link .= 'media="' . $media . '" ';
        }

        if ($title != '') {
            $link .= 'title="' . $title . '" ';
        }

        $link .= '/>';

        return $link;
    }

}

if (!function_exists('asset_js')) {

    function asset_js($src = '', $type = 'text/javascript') {
        $CI = & get_instance();

        $link = '<script ';

        if (is_array($src)) {
            foreach ($src as $k => $v) {
                if ($k == 'src' AND strpos($v, '://') === FALSE) {
                    $link .= 'src="' . asset_url($v) . '" ';
                } else {
                    $link .= "$k=\"$v\" ";
                }
            }

            $link .= "></script>";
        } else {
            if (strpos($src, '://') !== FALSE) {
                $link .= 'src="' . $src . '" ';
            } else {
                $link .= 'src="' . asset_url($src) . '" ';
            }

            $link .= 'type="' . $type . '" ';


            $link .= '></script>';
        }

        return $link;
    }

}

if (!function_exists('page_url')) {

    function page_url($page_info) {
        $ci = & get_instance();
        $ci->load->helper('text');

        if (is_numeric($page_info)) {
            $ci->load->model('page_model');
            $page_info = $ci->page_model->get($page_info);
            if ($page_info['return_code'] == API_SUCCESS && !empty($page_info['data'])) {
                $page_info = $page_info['data'];
            }
        }
        if (!isset($page_info['page_id'])) {
            return FALSE;
        }
        $name = preg_replace('/[^A-Za-z0-9() -]/', '', slug_convert($page_info['name_vietnamese']));
        return site_url('page/detail/' . htmlspecialchars($name) . '?page_id=' . $page_info['page_id']);
    }

}

if (!function_exists('product_category_url')) {

    function product_category_url($category_info) {
        $ci = & get_instance();
        $ci->load->helper('text');

        if (is_numeric($category_info)) {
            $ci->load->model('product_category_model');
            $category_info = $ci->product_category_model->get($category_info);
            if ($category_info['return_code'] == API_SUCCESS && !empty($category_info['data'])) {
                $category_info = $category_info['data'];
            } else {
                $category_info = array();
            }
        }
        if (!isset($category_info['product_category_id'])) {
            return FALSE;
        }
        $name = preg_replace('/[^A-Za-z0-9() -]/', '', slug_convert($category_info['category_name_vietnamese']));
        return site_url('category/detail/' . htmlspecialchars($name) . '?category_id=' . $category_info['product_category_id']);
    }

}

if (!function_exists('product_url')) {

    function product_url($product_info) {
        $ci = & get_instance();
        $ci->load->helper('text');

        if (is_numeric($product_info)) {
            $ci->load->model('product_model');
            $product_info = $ci->product_model->get($product_info);
            if ($product_info['return_code'] == API_SUCCESS && !empty($product_info['data'])) {
                $product_info = $product_info['data'];
            }
        }
        if (!isset($product_info['product_id'])) {
            return FALSE;
        }
        $name = preg_replace('/[^A-Za-z0-9() -]/', '', slug_convert($product_info['name_vietnamese']));
        return site_url('product/detail/' . htmlspecialchars($name) . '?product_id=' . $product_info['product_id']);
    }

}

if (!function_exists('blog_url')) {

    function blog_url($blog_info) {
        $ci = & get_instance();
        $ci->load->helper('text');

        if (is_numeric($blog_info)) {
            $ci->load->model('blog_model');
            $blog_info = $ci->blog_model->get($blog_info);
            if ($blog_info['return_code'] == API_SUCCESS && !empty($blog_info['data'])) {
                $blog_info = $blog_info['data'];
            }
        }
        if (!isset($blog_info['blog_id'])) {
            return FALSE;
        }
        $name = preg_replace('/[^A-Za-z0-9() -]/', '', slug_convert($blog_info['title_vietnamese']));
        return site_url('blog/detail/' . htmlspecialchars($name) . '?blog_id=' . $blog_info['blog_id']);
    }

}

if (!function_exists('blog_category_url')) {

    function blog_category_url($category_info) {
        $ci = & get_instance();
        $ci->load->helper('text');

        if (is_numeric($category_info)) {
            $ci->load->model('blog_category_model');
            $category_info = $ci->blog_category_model->get($category_info);
            if ($category_info['return_code'] == API_SUCCESS && !empty($category_info['data'])) {
                $category_info = $category_info['data'];
            }
        }
        if (!isset($category_info['blog_category_id'])) {
            return FALSE;
        }
        $name = preg_replace('/[^A-Za-z0-9() -]/', '', slug_convert($category_info['name_vietnamese']));
        return site_url('blog/category/' . htmlspecialchars($name) . '?blog_category_id=' . $category_info['blog_category_id']);
    }

}

