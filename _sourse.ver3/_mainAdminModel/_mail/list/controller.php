<?php


class _mail_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_mail_list_modul');

		// url
		$this->setSubmitUrl('/admin/mail/');

		$this->callFuncWriteAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>