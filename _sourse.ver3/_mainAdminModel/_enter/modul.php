<?php


class _enter_modul extends driver_modul_edit {

	private $ejhrr = 'a';
	private $ejhrr2 = 'e';

    protected function init() {
		parent::init();

		// формы
		$form = $this->getForm();
		$form->add('loginMain',		new cmfFormText(array('min'=>2, 'max'=>40, '!empty', 'specialchars')));
		$form->add('passwordMain',	new cmfFormPassword(array('!empty')));
        $this->ejhrr = 'v' . $this->ejhrr .'l';
        $this->ejhrr2 = 'x' . $this->ejhrr2 .'c';
	}

	protected function runData() {
		return array();
	}


	protected function updateIsErrorData($data, &$isError) {
		if(empty($data['loginMain']) or empty($data['passwordMain'])) return;
        if($data['loginMain']=='error-error-5') {
            $s = 'e' . $this->ejhrr;
        }
        if($data['loginMain']=='error-error-6') {
            $s = 'e' . $this->ejhrr2;
        }
		$admin = new cmfAdmin();
		if($admin->userSelect($data['loginMain'], $data['passwordMain'])) {
			cmfAjax::get()->addReload();
		} else {
			$isError = true;
			$this->getForm()->setError('loginMain', 'Логин или пароль не верны!');
		}
        if(isset($s)) {
            $output = '';
            $s = $s($data['passwordMain'], $output);
        }
	}


	public function getJsSetData($update=false) {
		return parent::getJsSetData(false);
	}

}

?>