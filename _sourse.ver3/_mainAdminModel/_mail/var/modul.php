<?php


class _mail_var_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_mail_var_db');

		// формы
		$form = $this->getForm();
		$form->add('var',	new cmfFormText(array('max'=>255, '!empty')));
		$form->add('name',	new cmfFormText(array('max'=>255, '!empty')));
		$form->add('value',	new cmfFormText(array('max'=>255, '!empty')));
	}

}

?>