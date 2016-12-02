<?php


class user_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('user_edit_db');

		// формы
		$form = $this->getForm();
		$form->add('login',		new cmfFormText(array('!empty', 'email', 'specialchars')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'userPasssword')));

		$form->add('isIp',		new cmfFormCheckbox());
		$form->add('visible',	new cmfFormCheckbox());
	}


	protected function updateIsErrorData($data, &$isError) {
		if(!empty($data['login'])) {
			if(!cmfUserModel::isNew($data['login'], $this->getId())) {
				$isError = true;
				$this->getForm()->setError('login', 'Такой пользователь уже существует');
			}
		}
		return $isError;
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(!empty($send['login'])) {
			 $send['email'] = $send['login'];
		}
	}

}

?>