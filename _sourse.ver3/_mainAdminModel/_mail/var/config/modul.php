<?php


class _mail_var_config_modul extends driver_modul_edit {


	protected function init() {
		parent::init();

		$this->setDb('_mail_var_config_db');

		// формы
		$form = $this->getForm();

		$form->add('login',		new cmfFormText(array('max'=>255, '!empty')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'passsword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'passsword')));
		//$form->add('secure',		new cmfFormText(array('max'=>255, '!empty')));
		$form->add('host',		new cmfFormText(array('max'=>255, '!empty')));
		$form->add('port',		new cmfFormText(array('max'=>255, '!empty')));
	}

}

?>