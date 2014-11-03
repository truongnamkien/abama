<?php

/**
 * Generate header use in email
 */
if (!function_exists('generate_header')) {

	function generate_header() {
		$ci = & get_instance();

		$ci->load->language(array('email'));
		return $ci->load->view('mail_header', NULL, TRUE);
	}

}

/**
 * Generate footer use in email
 */
if (!function_exists('generate_footer')) {

	function generate_footer() {
		$ci = & get_instance();
		$ci->load->language(array('email'));

		$ci->load->config('email', TRUE);
		$email_config = $ci->config->item('email_config', 'email');

		return $ci->load->view('mail_footer', array(
					'from_email' => $email_config['smtp_user']
						), TRUE);
	}

}

if (!function_exists('begin_line')) {

	function begin_line() {
		return '<p style="font-size: 14px; color:#555; line-height: 18px; padding: 5px 10px;">';
	}

}

if (!function_exists('end_line')) {

	function end_line() {
		return '</p>';
	}

}

if (!function_exists('open_link')) {

	function open_link($link, $text) {
		return '<a style="color: #444; font-weight:bold; text-decoration:none; font-size: 16px;" href="' . $link . '" target="_blank">' . $text . '</a>';
	}

}

if (!function_exists('button_link')) {

	function button_link($link, $text) {
		return '<a style="display:block; padding: 0 10px; font-size: 16px; text-decoration:none; color: #fff; font-weight:bold; background: #554234; padding: 15px 19px 15px 19px; float:left; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; margin-right:10px;" href="' . $link . '" target="_blank">' . $text . '</a>';
	}

}

