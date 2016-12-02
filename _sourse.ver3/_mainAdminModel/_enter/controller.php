<?php


class _enter_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_enter_modul');

		// url
		$this->setSubmitUrl('/admin/enter/');
	}

	public function run() {
	}

}

?>