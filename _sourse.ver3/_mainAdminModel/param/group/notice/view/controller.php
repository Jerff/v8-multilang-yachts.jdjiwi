<?php


class param_group_notice_view_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->addModul('main',	'param_group_notice_view_modul');

		// url
		$this->setSubmitUrl(cmfPages::getMain());
	}

}

?>