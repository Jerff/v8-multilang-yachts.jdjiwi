<?php


class menu_info_list_modul extends driver_modul_list_tree {

	protected function init() {
		parent::init();

		$this->setDb('menu_info_list_db');

		// �����
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>