<?php


class content_static_edit_controller extends driver_controller_lang_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'content_static_edit_modul');
		$this->addModul('lang',	'content_static_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/content/static/edit/');
		$this->setCatalogUrl('/admin/content/static/');
	}

}

?>