<?php


class menu_useful_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('menu_useful_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('name',		new cmfFormText(array('max'=>150)));
		$form->add('url',		new cmfFormText(array('max'=>150)));
		$form->add('menu',		new cmfFormSelect());
		$form->add('level',		new cmfFormSelectInt());
		$form->add('visible',	new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();
		cmfAdminSiteMenu::initSiteUsefulMenu($form);

		$form->addElement('level', 0, 'Не показывать');
		$form->addElement('level', 1, 'Показывать');
	}

}

?>