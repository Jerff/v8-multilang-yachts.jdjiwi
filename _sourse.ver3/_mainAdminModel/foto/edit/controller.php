<?php


class foto_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'foto_edit_modul');
        $this->addModul('lang',	'foto_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/foto/list/');
		$this->setCatalogUrl('/admin/foto/list/');
	}

}

?>