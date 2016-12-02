<?php


class menu_rigth_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('menu_rigth_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
//		$form->add('name',		new cmfFormText(array('max'=>150)));
		$form->add('url',		new cmfFormText(array('max'=>150)));
		$form->add('class',		new cmfFormText());
		$form->add('menu',		new cmfFormSelect());
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();
		cmfAdminSiteMenu::initSiteListMenu($form);
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
        if (!$this->getId()) {
            $send['level'] = $this->getFilter('section');
        }
	}

}

?>