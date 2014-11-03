<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

if (!function_exists('get_object_key')) {

    function get_object_key($object, $type) {
        foreach ($object as $key => $value) {
            if (strpos($key, $type) !== FALSE && strpos($key, '_id') !== FALSE) {
                return $key;
            }
        }
        return key($object);
    }

}

if (!function_exists('format_price')) {

    function format_price($price) {
        $str = '';
		$direction = $price >= 0 ? "" : "-";
		
        $price = trim(abs($price) . '');
        $length = strlen($price);
        $first_length = $length % 3;
        if ($first_length > 0) {
            $str .= substr($price, 0, $first_length);
        }

        while ($first_length < $length - 1) {
            if ($first_length > 0) {
                $str .= '.';
            }
            $str .= substr($price, $first_length, 3);
            $first_length += 3;
        }
        $str = $direction . $str . ' VND';
        return $str;
    }

}

if (!function_exists('set_notice_message')) {

    function set_notice_message($type, $content) {
        $ci = &get_instance();
        $notice = array(
            'type' => $type,
            'content' => $content,
        );
        $notice_list = $ci->session->userdata('notice_list');
        if (!empty($notice_list)) {
            $notice_list[] = $notice;
        } else {
            $notice_list = array($notice);
        }
        $ci->session->set_userdata('notice_list', $notice_list);
    }

}

