<?php


class _pages_access_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_pages_access_modul', false);
		$this->addModul('read',	'_pages_access_read_modul');
		$this->addModul('write','_pages_access_write_modul');
		$this->addModul('delete','_pages_access_delete_modul');

		// url
		$this->setSubmitUrl('/admin/pages/access/');

		$this->callFuncWriteAdd('updatePageAccess');
	}

	public function getFormRead() {
		return $this->getModul('read')->getForm()->getId();
	}

	public function getFormWrite() {
		return $this->getModul('write')->getForm()->getId();
	}

	public function getFormDelete() {
		return $this->getModul('delete')->getForm()->getId();
	}


	protected function updatePageAccess() {
		cmfUpdatePages::start();
	}

}

?>