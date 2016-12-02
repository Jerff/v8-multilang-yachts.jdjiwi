<?php


class menu_pages_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('menu_pages_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('name',		new cmfFormText(array('max'=>150)));
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>