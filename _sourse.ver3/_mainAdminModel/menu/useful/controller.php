<?php


class menu_useful_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'menu_useful_modul');

		// url
		$this->setSubmitUrl('/admin/menu/useful/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>