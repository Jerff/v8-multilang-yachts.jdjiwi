<?php


class shipyards_yachts_param_controller extends driver_controller_edit_param {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_param_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/param/');
		$this->setCatalogUrl('/admin/shipyards/yachts/edit/');
	}

}

?>