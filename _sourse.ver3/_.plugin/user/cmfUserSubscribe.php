<?php

cmfLoad('ajax/cmfMainAjaxSave');
cmfLoad('subscribe/cmfSubscribe');
cmfLoad('user/model/cmfUserModel');
class cmfUserSubscribe extends cmfMainAjaxSave {

	function __construct($formUrl=null, $name=null, $func=null) {

		if(!$name)		$name = 'subscribe';
		if(!$formUrl)	$formUrl = cmfControllerUrl .'/user/subscribe/?';
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func);
	}

	protected function init() {
		$form = $this->getForm();
		foreach(cmfSubscribe::typeList() as $k=>$v) {
            $form->add($k,    new cmfFormCheckbox(array('label'=>$v)));
		}
	}

	public function loadData() {
		$user = cmfRegister::getUser();
		$isAll = true;
		foreach(cmfSubscribe::typeList() as $k=>$v) {
            if($v==='yes') {
                $this->getForm()->select($k, $v);
                $isAll = false;
            }
		}
		if($isAll) {
            cmfSubscribe::selectForm($this->getForm(), $user->getId(), $user->email);
		}
	}

	public function run1() {
		$userData = $this->runStart();

        $user = cmfRegister::getUser();
		$id = $user->getId();
        cmfUserModel::saveParam($userData, $user->getId());

        foreach(cmfSubscribe::typeList() as $k=>$v) {
            if(isset($userData[$k])) {
        		if($userData[$k]==='yes') {
        		    cmfSubscribe::addUser($user->getId(), $user->email, $k);
        		} else {
                    cmfSubscribe::delUser($user->getId(), $user->email, $k);
        		}
    		}
		}

		cmfAjax::get()->script($this->getForm()->jsUpdate());
		$this->runSaveOk();
	}

}

?>