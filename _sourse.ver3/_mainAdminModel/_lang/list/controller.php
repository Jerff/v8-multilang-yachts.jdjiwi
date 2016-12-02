<?php


class _lang_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_lang_list_modul');

		// url
		$this->setSubmitUrl('/admin/lang/');

		$this->callFuncWriteAdd('newLine|updatePages');
	}

	protected function updatePages() {
		cmfUpdatePages::updateMain();
	}

	public function delete($id) {
		//parent::deleteList($id);
		//return parent::delete($id);
	}

}

?>