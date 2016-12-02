<?php


class content_static_edit_lang_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('content_static_edit_lang_db');

		// формы
		$form = $this->getForm();
		$form->add('content',	new cmfFormTextareaWysiwyng('static', $this->getId()));
	}

}

?>