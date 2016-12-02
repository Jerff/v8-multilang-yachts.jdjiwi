<?php


class menu_list_controller extends driver_controller_list_all_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'menu_list_modul');
		$this->addModul('lang',	'menu_list_lang_modul');

		// url
		$this->setSubmitUrl('/admin/menu/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>