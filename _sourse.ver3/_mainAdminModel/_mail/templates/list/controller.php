<?php


class _mail_templates_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_mail_templates_list_modul');

		// url
		$this->setSubmitUrl('/admin/mail/templates/');
		$this->setEditUrl('/admin/mail/templates/edit/');

	}

	public function filterSection() {
		$filter = $this->getModul()->getDb()->filterSection();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilterPart($filter, 'section', 'end');
	}

	public function delete($id) {
		$id = cmfModulLoad('_mail_templates_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>