<?php


class menu_info_edit_lang_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('menu_info_edit_lang_db');

		// формы
		$form = $this->getForm();
		$form->add('name',	    new cmfFormTextarea(array('!empty', 'max'=>150)));
		$form->add('menu',	    new cmfFormTextarea(array('max'=>150)));
		$form->add('content',	new cmfFormTextareaWysiwyng('info', $this->getId()));

		$form->add('title',			new cmfFormTextarea());
		$form->add('keywords',		new cmfFormTextarea());
		$form->add('description',	new cmfFormTextarea());
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);

	}

}

?>