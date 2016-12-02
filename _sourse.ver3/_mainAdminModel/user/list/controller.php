<?php


class user_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->addModul('main',	'user_list_modul');

		// url
		$this->setSubmitUrl('/admin/user/');
		$this->setEditUrl('/admin/user/edit/');
		$this->callFuncWriteAdd('activate');
		$this->callFuncWriteAdd('userUnban|userExit|changeFilter');
	}

	public function changeFilter() {
        $name = trim($this->getRequest()->getPost('name'));
        $email = trim($this->getRequest()->getPost('email'));

        $opt = array();
        $opt['name'] = $name ? $name : null;
		$opt['email'] = $email ? $email : null;
		$this->getResponse()->redirect($this->getSubmitUrl($opt));
	}

	public function filterGroup() {
		$filter = cmfModulLoad('user_group_list_db')->getNameList();
		$filter[0]['name'] = 'Все клиенты';
		return parent::abstractFilterPart($filter, 'main', 'end');
	}

	public function delete($id) {
		cmfUserModel::accesIs($id);
		$id = cmfModulLoad('user_edit_controller')->delete($id);
		return parent::delete($id);
	}

	protected function userUnban($id){
		cmfUserModel::accesIs($id);
		cmfUserModel::userUnban($id);
		$this->getResponse()->script("
\$('#userUnban{$id}').hide();
\$('#userExit{$id}').show();");
	}

	protected function userExit($id){
		cmfUserModel::accesIs($id);
		cmfUserModel::userExit($id);
	}

	public function activate($id) {
		cmfModulLoad('user_edit_controller')->activate($id);
	}

}

?>