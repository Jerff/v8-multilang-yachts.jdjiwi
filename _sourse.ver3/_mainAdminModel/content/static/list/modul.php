<?php


class content_static_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('content_static_list_db');

		// �����
		$form = $this->getForm();
	}

}

?>