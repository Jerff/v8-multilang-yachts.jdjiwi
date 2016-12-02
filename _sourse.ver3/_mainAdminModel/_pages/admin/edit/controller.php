<?php


class _pages_admin_edit_controller extends driver_controller_edit_tree {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_pages_admin_edit_modul');

		// url
		$this->setSubmitUrl('/admin/pages/admin/');
		$this->setCatalogUrl('/admin/pages/admin/');

		$this->callFuncReadAdd('onchangeModul');
	}

	// страница списка
	public function getCatalogUrl($opt=null) {
		$opt['id'] = $this->getFilter('parent');
		return parent::getCatalogUrl($opt);
	}

	protected function onchangeModul() {
		$this->getModul()->onchangeModul();
	}

}

?>