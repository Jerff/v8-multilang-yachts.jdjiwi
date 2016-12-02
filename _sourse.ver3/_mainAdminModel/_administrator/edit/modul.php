<?php


class _administrator_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_administrator_edit_db');

		// формы
		$form = $this->getForm();
		$form->add('admin',		new cmfFormSelectCheckbox());

		$form->add('name',		new cmfFormTextarea(array('!empty', 'min'=>2, 'max'=>40, 'specialchars')));
		$form->add('login',		new cmfFormTextarea(array('!empty', 'specialchars')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('email',		new cmfFormText(array('!empty', 'email')));
		$form->add('isIp',		new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		parent::loadForm();

		$name = cmfModulLoad('_administrator_group_list_db')->getNameList();
		$form = $this->getForm();
		foreach($name as $k=>$v) {
            $form->addElement('admin', $k, $v['name']);
		}
		$form->select('admin', $this->getFilter('admin'));
	}

	protected function updateIsErrorData($data, &$isError) {
		if(!empty($data['login'])) {
			if(!cmfModelAdmin::isNew($data['login'], $this->getId())) {
				$isError = true;
				$this->getForm()->setError('login', 'Такой пользователь уже существует');
			}
		}
		return $isError;
	}

}

?>