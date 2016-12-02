<?php


class reviews_edit_modul extends driver_modul_lang_edit {

	protected function init() {
		parent::init();

		$this->setDb('reviews_edit_db');

		// формы
		$form = $this->getForm();
		$form->add('date',		new cmfFormTextDateTime());

		$form->add('main',		new cmfFormCheckbox());
		$form->add('visible',	new cmfFormCheckbox());

		$form->add('image',	new cmfFormFile(array('path'=>cmfPathReviews)));
	}

}

?>