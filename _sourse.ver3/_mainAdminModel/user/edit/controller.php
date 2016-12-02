<?php


class user_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'user_edit_modul');

		// url
		$this->setSubmitUrl('/admin/user/edit/');
		$this->setCatalogUrl('/admin/user/');
		$this->callFuncWriteAdd('activate');
	}

	public function getUserStat() {
		return cmfModulLoad('user_edit_db')->getUserStat($this->getId());
	}

	public function activate($id=0) {
		if(!$id) $id = $this->getId();
		$data = $this->getModul()->getDb()->getDataId($id);
		if(get($data, 'register')!=='no') return;
        cmfUserModel::save(array('register'=>'yes', 'visible'=>'yes'), $id);
        $data['name'] = cmfUser::generateName($data);

        $cmfMail = new cmfMail();
		$cmfMail->sendTemplates('Личный кабинет: Регистрация (активация из админки)', $data, $data['email']);

		$this->getResponse()->reload();
	}

}

?>