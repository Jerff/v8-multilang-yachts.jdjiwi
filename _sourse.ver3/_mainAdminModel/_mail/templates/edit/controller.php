<?php


class _mail_templates_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_mail_templates_edit_modul');

		// url
		$this->setSubmitUrl('/admin/mail/templates/edit/');
		$this->setCatalogUrl('/admin/mail/templates/');
	}

	public function getEmailVar() {
        return cmfModulLoad('_mail_var_db')->getNameList(null, array('var'));
	}

}

?>