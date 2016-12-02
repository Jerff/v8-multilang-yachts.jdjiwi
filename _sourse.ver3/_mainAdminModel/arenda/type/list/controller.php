<?php


class arenda_type_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'arenda_type_list_modul');

		// url
		$this->setSubmitUrl('/admin/arenda/type/');
		$this->setEditUrl('/admin/arenda/type/edit/');
	}

	public function delete($id) {
		$id = cmfModulLoad('arenda_type_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>