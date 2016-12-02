<?php


class _seo_sitemap_config_db extends driver_db_edit_param_of_record {

	function __construct() {
		$this->setIdName('main');
		parent::__construct();
	}

	protected function getTable() {
		return db_sys_config;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('config');
	}

}

?>