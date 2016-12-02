<?php


class _pages_main_edit_controller extends driver_controller_edit_tree {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_pages_main_edit_modul');

		// url
		$this->setSubmitUrl('/admin/pages/main/');
		$this->setCatalogUrl('/admin/pages/main/');
	}

	// страница списка
	public function getCatalogUrl($opt=null) {
		$opt['id'] = $this->getFilter('parent');
		return parent::getCatalogUrl($opt);
	}

}

?>