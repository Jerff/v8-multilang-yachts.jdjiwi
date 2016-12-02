<?php


class menu_sale_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'menu_sale_modul');

		// url
		$this->setSubmitUrl('/admin/menu/sale/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>