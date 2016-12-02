<?php


class menu_arenda_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'menu_arenda_modul');

		// url
		$this->setSubmitUrl('/admin/menu/arenda/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>