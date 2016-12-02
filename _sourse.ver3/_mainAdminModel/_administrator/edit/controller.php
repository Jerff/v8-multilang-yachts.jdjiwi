<?php


class _administrator_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_administrator_edit_modul');

		// url
		$this->setSubmitUrl('/admin/administrator/edit/');
		$this->setCatalogUrl('/admin/administrator/');
	}

	public function delete($id) {
		cmfModelAdmin::accesIs($id);
		return parent::delete($id);
	}

}

?>