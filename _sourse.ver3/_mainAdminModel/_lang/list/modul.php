<?php


class _lang_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_lang_list_db');

		// �����
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('name',	new cmfFormText(array('max'=>255, '!empty')));
		$form->add('uri',	new cmfFormText(array('max'=>50, '!empty')));
		$form->add('image',new cmfFormFile(array('path'=>cmfPathLang)));
		$form->add('small',new cmfFormFile(array('path'=>cmfPathLang)));
	}

}

?>