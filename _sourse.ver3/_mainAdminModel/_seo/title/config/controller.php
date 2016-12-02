<?php


class _seo_title_config_controller extends driver_controller_edit_param_of_record {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_title_config_modul');

		// url
		$this->setSubmitUrl('/admin/seo/');
	}

}

?>