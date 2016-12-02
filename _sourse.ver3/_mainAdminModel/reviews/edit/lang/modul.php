<?php


class reviews_edit_lang_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('reviews_edit_lang_db');

		// формы
		$form = $this->getForm();
		$form->add('header',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('content',		new cmfFormTextareaWysiwyng('reviews', $this->getId()));
	}

}

?>