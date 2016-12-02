<?php


class _administrator_group_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_administrator_group_edit_modul');

		// url
		$this->setSubmitUrl('/admin/administrator/group/edit/');
		$this->setCatalogUrl('/admin/administrator/group/');
	}

	public function delete($id) {
		$id = parent::delete($id);

		cmfModulLoad('_pages_access_read_db')->deleteAttach($id);
		cmfModulLoad('_pages_access_write_db')->deleteAttach($id);
		cmfModulLoad('_pages_access_delete_db')->deleteAttach($id);

		return $id;
	}

}

?>