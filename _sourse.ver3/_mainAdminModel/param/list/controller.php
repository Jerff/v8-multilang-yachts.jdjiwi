<?php


class param_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'param_list_modul');

		// url
		$this->setSubmitUrl('/admin/param/');
		$this->setEditUrl('/admin/param/edit/');
	}

	public function delete($id) {
		$id = cmfModulLoad('param_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>