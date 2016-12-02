<?php


class menu_footer_controller extends driver_controller_list_all_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'menu_footer_modul');
		$this->addModul('lang',	'menu_footer_lang_modul');

		// url
		$this->setSubmitUrl('/admin/menu/footer/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>