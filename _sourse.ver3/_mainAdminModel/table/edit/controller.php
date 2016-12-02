<?php


class table_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'table_edit_modul');

		// url
		$this->setSubmitUrl('/admin/table/edit/');
		$this->setCatalogUrl('/admin/table/');
	}


	public function delete($id) {
		$id = parent::delete($id);
        cmfModulLoad('table_data_controller')->deleteTable($id);
		return $id;
	}

}

?>