<?php


class _config_cron_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_config_cron_list_modul');

		// url
		$this->setSubmitUrl('/admin/config/cron/');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>