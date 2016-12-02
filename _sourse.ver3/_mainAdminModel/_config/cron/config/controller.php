<?php


class _config_cron_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->addModul('main',	'_config_cron_config_modul');

		// url
		$this->setSubmitUrl('/admin/config/cron/');
	}

}

?>