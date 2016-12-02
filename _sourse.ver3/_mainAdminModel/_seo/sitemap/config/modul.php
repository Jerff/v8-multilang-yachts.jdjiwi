<?php


class _seo_sitemap_config_modul extends driver_modul_edit {

	function __construct() {
		$this->setIdName('main');
		parent::__construct();
	}

	protected function init() {
		parent::init();

		$this->setDb('_seo_sitemap_config_db');

		// формы
		$form = $this->getForm();
	}

}

?>