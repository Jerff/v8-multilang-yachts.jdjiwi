<?php


class arenda_type_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('arenda_type_list_db');

		// �����
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>