<?php


class _mail_var_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_mail_var_modul');

		// url
		$this->setSubmitUrl('/admin/mail/var/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>