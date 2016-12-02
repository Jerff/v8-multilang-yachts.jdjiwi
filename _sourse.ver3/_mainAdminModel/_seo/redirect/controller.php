<?php


class _seo_redirect_controller extends driver_controller_list_all_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_redirect_modul');

		// url
		$this->setSubmitUrl('/admin/seo/redirect/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>