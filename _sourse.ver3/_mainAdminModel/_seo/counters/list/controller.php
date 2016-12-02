<?php


class _seo_counters_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_seo_counters_list_modul');

		// url
		$this->setSubmitUrl('/admin/seo/counters/');
		$this->setEditUrl('/admin/seo/counters/edit/');
	}

	public function delete($id) {
		$id = cmfModulLoad('_seo_counters_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>