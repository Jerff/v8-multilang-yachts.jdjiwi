<?php


class wallpapers_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'wallpapers_edit_modul');
        $this->addModul('lang',	'wallpapers_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/wallpapers/list/');
		$this->setCatalogUrl('/admin/wallpapers/list/');
	}

}

?>