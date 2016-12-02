<?php


class _lang_list_config_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_lang_list_config_db');

		// формы
		$form = $this->getForm();

		//$form->add('preg',		new cmfFormText(array('max'=>255, '!empty')));
	}

}

?>