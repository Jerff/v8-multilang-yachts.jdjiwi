<?php


class param_group_search_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'param_group_search_modul');

		// url
		$this->setSubmitUrl(cmfPages::getMain());
	}

}

?>