<?php


class _profil_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_profil_edit_db');

		// формы
		$form = $this->getForm();
		$form->add('name',		new cmfFormTextarea(array('!empty', 'min'=>2, 'max'=>40, 'specialchars')));
		$form->add('main',	    new cmfFormPassword(array('confirmName'=>'mainPasssword')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('email',		new cmfFormText(array('!empty', 'email')));
		$form->add('isIp',		new cmfFormCheckbox());
	}

	protected function updateIsErrorData($data, &$isError) {
		if(isset($data['password'])) {
		    if(empty($data['main'])) {
    		    $isError = true;
    			$this->getForm()->setError('main', 'Заполните обязательное поле');
            } elseif(!cmfModelAdmin::isPassword($data['main'], $this->getId())) {
				$isError = true;
				$this->getForm()->setError('main', 'Поле заполнено неправильно');
			}
		}
		return $isError;
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		unset($send['main']);
	}

}

?>