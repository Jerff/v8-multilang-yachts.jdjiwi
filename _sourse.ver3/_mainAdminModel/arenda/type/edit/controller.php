<?php


class arenda_type_edit_controller extends driver_controller_lang_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'arenda_type_edit_modul');
        $this->addModul('lang', 'arenda_type_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/arenda/type/edit/');
		$this->setCatalogUrl('/admin/arenda/type/');
	}

	public function delete($id) {
		$id = parent::delete($id);
		cmfConfigArendaType::delete($id);
		return $id;
	}

}

?>