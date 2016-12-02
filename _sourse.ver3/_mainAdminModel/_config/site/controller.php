<?php


class _config_site_controller extends driver_controller_edit_param_of_record {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_config_site_modul');

		// url
		$this->setSubmitUrl('/admin/config/site/');
	}

}

?>