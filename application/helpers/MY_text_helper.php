<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

if (!function_exists('_utf8_encode')) {

	function _utf8_encode($text) {
		$clean_pieces = array();
		preg_match_all('/([\x09\x0a\x0d\x20-\x7e]' . // ASCII characters
				'|[\xc2-\xdf][\x80-\xbf]' . // 2-byte (except overly longs)
				'|\xe0[\xa0-\xbf][\x80-\xbf]' . // 3 byte (except overly longs)
				'|[\xe1-\xec\xee\xef][\x80-\xbf]{2}' . // 3 byte (except overly longs)
				'|\xed[\x80-\x9f][\x80-\xbf])+/', // 3 byte (except UTF-16 surrogates)
				$text, $clean_pieces);

		$clean_output = join('?', $clean_pieces[0]);

		return $clean_output;
	}

}

if (!function_exists('clean_message')) {

	function clean_message($message, $nl2br = TRUE) {
		$CI = & get_instance();
		$CI->load->helper('string');

		$message = reduce_multiples($message, "\n\n");
		$message = reduce_multiples($message, "\r\n\r\n");
		$message = reduce_multiples($message, " ");


		$message = htmlspecialchars(trim($message));

		if ($nl2br) {
			$message = nl2br($message);
		}

		return $message;
	}

}

if (!function_exists('text_to_link')) {

	function _text_to_link($link) {
		$link = prep_url($link[0]);
		return "<a href=\"$link\" onmousedown=\"UntrustedLink.bootstrap(this, '', event);\"  target=\"_blank\">$link</a>";
	}

	function text_to_link($message) {
		$c = '[http|ftp][a-z0-9\\-+.]+://';
		$h = 'www\\d{0,3}[.]';
		$b = '[a-z0-9.\\-]+[.][a-z]{2,4}\\/';
		$a = '\\([^\\s()<>]+\\)';
		$f = '[^\\s()<>]+';
		$e = '[^\\s`!()\\[\\]{};:\'".,<>?]';

		$d = "((?:$c|$h|$b)(?:$a|$f)*(?:$a|$e))";
		return preg_replace_callback($d, "_text_to_link", $message);
	}

}

if (!function_exists('url_to_link_tag')) {

	function url_to_link_tag($text) {
		$text = preg_replace("
            #(\s|\<p\>)(http://\S*?\.\S*?|https://\S*?\.\S*?|ftp://\S*?\.\S*?|www\.\S*?)(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", "'$1<a href=\"$2\" target=\"_blank\">$2</a>$3'", $text
		);
		return str_replace('href="www', 'href="http://www', $text);
	}

}

if (!function_exists('strip_unicode')) {

	function strip_unicode($str) {
		if (!$str)
			return false;
		$unicode = array(
			'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'A' => 'À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ',
			'd' => 'đ',
			'D' => 'Đ',
			'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'E' => 'È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ',
			'i' => 'í|ì|ỉ|ĩ|ị',
			'I' => 'Ì|Í|Ị|Ỉ|Ĩ',
			'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'O' => 'Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ',
			'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'U' => 'Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ',
			'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
			'Y' => 'Ỳ|Ý|Ỵ|Ỷ|Ỹ'
		);
		foreach ($unicode as $nonUnicode => $uni)
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		return $str;
	}

}

/**
 * chuyen co dau thanh ko dau
 */
if (!function_exists('slug_convert')) {

	function slug_convert($str) {
		$inputs = array(
			'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'd' => 'đ',
			'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'i' => 'í|ì|ỉ|ĩ|ị',
			'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
			'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'D' => 'Đ',
			'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
			'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
			'-' => '\(|\?|=',
			'' => '\)',
		);

		foreach ($inputs as $nonUnicode => $unicode) {
			$str = preg_replace("/($unicode)/i", $nonUnicode, $str);
			$str = str_replace(' ', '-', $str);
			$str = str_replace('/', '-', $str);
		}

		return $str;
	}

}

if (!function_exists('html_substr')) {

	function html_substr($s, $srt, $len = NULL, $strict = false, $suffix = NULL) {
		if (is_null($len)) {
			$len = strlen($s);
		}

		$f = 'static $strlen=0;
                if ( $strlen >= ' . $len . ' ) { return "><"; }
                $html_str = html_entity_decode( $a[1] );
                $subsrt = max(0, (' . $srt . '-$strlen));
                $sublen = ' . ( empty($strict) ? '(' . $len . '-$strlen)' : 'max(@strpos( $html_str, "' . ($strict === 2 ? '.' : ' ') . '", (' . $len . ' - $strlen + $subsrt - 1 )), ' . $len . ' - $strlen)' ) . ';
                $new_str = substr( $html_str, $subsrt,$sublen);
                $strlen += $new_str_len = strlen( $new_str );
                $suffix = ' . (!empty($suffix) ? '($new_str_len===$sublen?"' . $suffix . '":"")' : '""' ) . ';
                return ">" . htmlentities($new_str, ENT_QUOTES, "UTF-8") . "$suffix<";';

		return preg_replace(array("#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), "", trim(rtrim(ltrim(preg_replace_callback("#>([^<]+)<#", create_function(
																'$a', $f
														), ">$s<"), ">"), "<")));
	}

}

