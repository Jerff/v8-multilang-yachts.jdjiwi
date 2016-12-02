<?php


class _seo_head_controller extends driver_controller_list_all_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_head_modul');

		// url
		$this->setSubmitUrl('/admin/seo/head/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>