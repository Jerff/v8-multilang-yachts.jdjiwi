<?php


class menu_footer_lang_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('menu_footer_lang_db');

		// формы
		$form = $this->getForm();
		$form->add('name',		new cmfFormText(array('max'=>150)));
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
	}

}

?>