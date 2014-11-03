<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Search_Ajax extends MY_Ajax {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Instant search
	 */
	public function search() {
		$query = $this->input->get_post('query');
		$html = Modules::run('search/_instant_search', $query);
		$this->response->html("#search_result", '');

		if (!empty($html)) {
			$this->response->html("#search_result", $html);
			$this->response->run("$('#search_result').show();");
		}
		$this->response->send();
	}

}