<?php


class menu_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('menu_list_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
//		$form->add('name',		new cmfFormText(array('max'=>150)));
		$form->add('url',		new cmfFormText(array('max'=>150)));
		$form->add('class',		new cmfFormText());
		$form->add('menu',		new cmfFormSelect());
		$form->add('level',		new cmfFormSelectInt());
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();
		cmfAdminSiteMenu::initSiteListMenu($form);

		$form->addElement('level', 1, 'Уровень 1');
		$form->addElement('level', 2, 'Уровень 2');
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
	}

}

?>