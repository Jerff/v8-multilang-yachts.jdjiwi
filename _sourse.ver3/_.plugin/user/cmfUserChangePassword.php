<?php

cmfLoad('ajax/cmfMainAjaxSave');
cmfLoad('user/model/cmfUserModel');
class cmfUserChangePassword extends cmfMainAjaxSave {

	function __construct($formUrl=null, $name=null, $func=null) {

		if(!$name)		$name = 'changePassword';
		if(!$formUrl)	$formUrl = cmfControllerUrl .'/user/changePassword/?';
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func);
	}



	protected function init() {
		$form = $this->getForm();
		$form->add('email', new cmfFormTextMain(array('empty')));
        $form->add('passwordMain',	new cmfFormPasswordMain(array('name'=>'Пароль', '!empty', 'confirmName'=>'userPasssword1')));

		$form->add('password',		new cmfFormPasswordMain(array('name'=>'Пароль', '!empty', 'confirmName'=>'userPasssword')));
		$form->add('password2',		new cmfFormPasswordMain(array('!empty', 'confirmName'=>'userPasssword')));
	}

	public function run1() {
		$userData = $this->runStart();

        $user = cmfRegister::getUser();
		$id = $user->getId();
		$num = cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE id=? AND password=?", db_user, $id, cmfAuth::hash($userData['passwordMain']))
										->numRows();
		if(!$num) {
			$this->getForm()->setError('passwordMain', 'Неправильный пароль');
			$this->runEnd(true);
		}
		cmfUserModel::save(array('password'=>$userData['password']), $id);


		$email = array();
		$email['name'] = $user->name;
		$email['login'] = $user->login;
		$email['password'] = $userData['password'];

		$cmfMail = new cmfMail();
		$cmfMail->sendTemplates('Личный кабинет: Смена пароля пользователем', $email, $user->email);


		cmfAjax::get()->script($this->getForm()->jsUpdate());

		$this->runSaveOk();
		die();
	}

}

?>