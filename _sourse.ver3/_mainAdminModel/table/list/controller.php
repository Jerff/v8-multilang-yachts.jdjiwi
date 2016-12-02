<?php


class table_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'table_list_modul');

		// url
		$this->setSubmitUrl('/admin/table/');
		$this->setEditUrl('/admin/table/edit/');

		$this->setUrl('data', '/admin/table/data/');
	}

	public function getDataUrl() {
		$opt = array('table'=>$this->getIndex());
		return $this->getUrl('data', $opt);
	}

	public function delete($id) {
		$id = cmfModulLoad('table_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>