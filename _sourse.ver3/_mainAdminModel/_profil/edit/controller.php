<?php


class _profil_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_profil_edit_modul');

		// url
		$this->setSubmitUrl('/admin/profil/');
		$this->setCatalogUrl('/admin/profil/');
	}

	public function delete($id) {
		return false;
	}

}

?>