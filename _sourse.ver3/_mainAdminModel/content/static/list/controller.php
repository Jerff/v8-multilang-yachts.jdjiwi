<?php


class content_static_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'content_static_list_modul');

		// url
		$this->setSubmitUrl('/admin/content/static/');
		$this->setEditUrl('/admin/content/static/edit/');

	}

	public function filterSection() {
		$filter = $this->getModul()->getDb()->filterSection();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'section', 'end');
	}

	public function delete($id) {
		$id = cmfModulLoad('content_static_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>