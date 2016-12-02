<?php


class _seo_reklama_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_reklama_edit_modul');

		// url
		$this->setSubmitUrl('/admin/seo/reklama/edit/');
		$this->setCatalogUrl('/admin/seo/reklama/');
	}

}

?>