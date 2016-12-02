<?php


class param_group_notice_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'param_group_notice_modul');

		// url
		$this->setSubmitUrl(cmfPages::getMain());
	}

}

?>