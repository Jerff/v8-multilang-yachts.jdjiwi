<?php


class _seo_reklama_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_reklama_list_modul');

		// url
		$this->setSubmitUrl('/admin/seo/reklama/');
		$this->setEditUrl('/admin/seo/reklama/edit/');
	}

	public function delete($id) {
		$id = cmfModulLoad('_seo_reklama_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>