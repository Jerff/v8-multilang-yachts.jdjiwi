<?php


class reviews_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'reviews_list_modul');

		// url
		$this->setSubmitUrl('/admin/reviews/');
		$this->setEditUrl('/admin/reviews/edit/');

	}

	public function delete($id) {
		$id = cmfModulLoad('reviews_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>