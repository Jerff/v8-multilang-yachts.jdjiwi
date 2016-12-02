<?php


class news_edit_modul extends driver_modul_lang_edit {

	protected function init() {
		parent::init();

		$this->setDb('news_edit_db');

		// формы
		$form = $this->getForm();
		$form->add('date',		new cmfFormTextDateTime());

		$form->add('uri',		new cmfFormText(array('uri'=>95)));
		$form->add('main',		new cmfFormCheckbox());
		$form->add('visible',	new cmfFormCheckbox());

		$form->add('image',	new cmfFormFile(array('path'=>cmfPathNews, 'size'=>array(215, 215))));
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		parent::saveStartUri($send, array('news_edit_lang_modul', 'header'), 95);
	}

}

?>