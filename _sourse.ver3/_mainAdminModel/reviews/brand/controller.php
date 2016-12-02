<?php


class reviews_brand_controller extends driver_controller_list_all_lang {

	protected function init() {
		parent::init();
		$this->addModul('main',	'reviews_brand_modul');
		$this->addModul('lang',	'reviews_brand_lang_modul');

		// url
		$this->setSubmitUrl('/admin/reviews/brand/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>