<?php


class brokerage_yachts_param_controller extends driver_controller_edit_param {

	protected function init() {
		parent::init();
		$this->addModul('main',	'brokerage_yachts_param_modul');

		// url
		$this->setSubmitUrl('/admin/brokerage/yachts/param/');
		$this->setCatalogUrl('/admin/brokerage/yachts/edit/');
	}

}

?>