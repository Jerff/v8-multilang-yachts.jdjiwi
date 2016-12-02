<?php


class reviews_edit_controller extends driver_controller_lang_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'reviews_edit_modul');
		$this->addModul('lang',	'reviews_edit_lang_modul');

		// url
		$this->setSubmitUrl('/admin/reviews/edit/');
		$this->setCatalogUrl('/admin/reviews/');
	}

	public function delete($id) {
		$id = parent::delete($id);
//		cmfControllerArticle::delete($id);
		cmfModulLoad('main_slider_edit_controller')->deleteSlider('reviews/one', $id);
		return $id;
	}

}

?>