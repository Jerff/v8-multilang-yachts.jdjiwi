<?php


class _seo_counters_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_counters_edit_modul');

		// url
		$this->setSubmitUrl('/admin/seo/counters/edit/');
		$this->setCatalogUrl('/admin/seo/counters/');
	}

}

?>