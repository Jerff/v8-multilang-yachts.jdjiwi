<?php

cmfLoad('contact/cmfContact');
cmfLoad('subscribe/cmfSubscribe');
cmfLoad('ajax/cmfMainAjaxSave');
cmfLoad('user/model/cmfUserModel');
class cmfUserInfo extends cmfMainAjaxSave {

	function __construct($formUrl=null, $name=null, $func=null) {

		if(!$name)		$name = 'userInfo';
		if(!$formUrl)	$formUrl = cmfControllerUrl .'/user/info/?';
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func, 2);
	}


	public function get($n) {
		return parent::get($n);
	}


	protected function init() {
		$form = $this->getForm();
    	$form->add('name',		new cmfFormText(array('name'=>'Имя', '!empty', 'specialchars', 'max'=>250)));
		$form->add('family',	new cmfFormText(array('name'=>'Фамилия', '!empty', 'specialchars', 'max'=>250)));
    	$form->add('login',		new cmfFormText(array('!empty', 'name'=>'E-mail', 'email', 'min'=>4, 'max'=>100)));

		$form = $this->getForm(2);
		$form->add('cod',		new cmfFormText(array('errorHide1', 'phoneCod', 'min'=>4, 'max'=>4)));
		$form->add('phone',		new cmfFormText(array('errorHide1', 'name'=>'Телефон', 'phonePostPrefix', 'min'=>7, 'max'=>7)));
        $form->add('gorod',     new cmfFormText(array('name'=>'Город', 'max'=>100)));
        $form->add('index',     new cmfFormText(array('name'=>'Индекс', 'max'=>15)));        
	}

	public function loadData() {
		$user = cmfRegister::getUser();

		$this->getForm(1)->selectAll($user->all);
		$this->getForm(2)->selectAll($user->all);
	}


	public function run1() {
		list($userData, $userValue) = $this->runStart();

		$user = cmfRegister::getUser();
		if($userData) {
			if(isset($userData['login'])) {
				if(!cmfUserModel::isNew($userData['login'], $user->getId())) {
		            $this->getForm()->setError('login', 'Такой пользователь уже существует');
		            $this->runEnd(true);
		        }
            	$userData['email'] = $userData['login'];
            }

			cmfUserModel::save($userData, $user->getId());
			cmfAjax::get()->addScript('cmf.user.view();');
		}

		if($userValue) {
			cmfUserModel::saveParam($userValue, $user->getId());
		}

		$user->reset();
		$this->loadData();

		if($userData or $userValue) {
			$this->runSaveOk();
		} else {
			$this->runEnd();
		}
	}
    
	protected function runStart() {
        $r = cmfRegister::getRequest();

		$isError = $isUpdate = false;
		$data = array();
		foreach($this->getFormAll() as $form) {
			$form->setRequest($r);
			$send = $form->handler();
			$isUpdate |= count($send);
			$data[] = $send;
		    $isError |= $form->isError();
		}
 
		if(!$isError and $isUpdate) {
            return $this->getFormCount()>1 ? $data : $data[0];
		}
		$this->runEnd(true);
	}    

	protected function runEnd($error=false) {
		parent::runEnd($error);
	}    

}

?>