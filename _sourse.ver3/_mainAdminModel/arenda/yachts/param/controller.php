<?php


class arenda_yachts_param_controller extends driver_controller_edit_param {

	protected function init() {
		parent::init();
		$this->addModul('main',	'arenda_yachts_param_modul');

		// url
		$this->setSubmitUrl('/admin/arenda/yachts/param/');
		$this->setCatalogUrl('/admin/arenda/yachts/edit/');
	}

}

?>