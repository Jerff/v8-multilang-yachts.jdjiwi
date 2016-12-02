<?php


class _administrator_group_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_administrator_group_list_modul');

		// url
		$this->setSubmitUrl('/admin/administrator/group/');
		$this->setEditUrl('/admin/administrator/group/edit/');

		$this->setUrl('user', '/admin/administrator/');
	}

	protected function getLimit() {
		return 1000;
	}

	public function getUserUrl() {
		$opt = array('admin'=>$this->getIndex());
		return $this->getUrl('user', $opt);
	}

	public function delete($id) {
		$id = cmfModulLoad('_administrator_group_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>