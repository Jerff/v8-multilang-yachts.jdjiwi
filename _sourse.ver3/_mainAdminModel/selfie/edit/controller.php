<?php


class selfie_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'selfie_edit_modul');
        $this->addModul('lang',	'selfie_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/selfie/list/');
		$this->setCatalogUrl('/admin/selfie/list/');
	}

}

?>