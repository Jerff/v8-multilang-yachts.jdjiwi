<?php


class arenda_list_modul extends driver_modul_list_tree {

	protected function init() {
		parent::init();

		$this->setDb('arenda_list_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>