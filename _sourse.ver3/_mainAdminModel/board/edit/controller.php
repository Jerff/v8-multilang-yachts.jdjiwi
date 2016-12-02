<?php

class board_edit_controller extends driver_controller_lang_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'board_edit_modul');

        // url
        $this->setSubmitUrl('/admin/board/edit/');
        $this->setCatalogUrl('/admin/board/');
        $this->setUrl('user', '/admin/user/edit/');

        $this->callFuncWriteAdd('activate');
    }

    public function viewSiteUrl() {
        $data = $this->getModul()->getData();
        return parent::viewSiteUrl('/board/item/', $this->getId());
    }

    public function getUserUrl($user) {
        $opt['id'] = $user;
        return $this->getUrl('user', $opt);
    }

	public function listUser() {
		$res = $this->getSql()->placeholder("SELECT id, email, login FROM ?t WHERE id IN(SELECT user FROM ?t WHERE id=? GROUP BY `user`)", db_user, db_board, $this->getId())
								->fetchAssocAll('id');
		foreach($res as $k=>$v) {
		    $res[$k] = cmfUser::generateName($v);
		}
		return $res;
	}

    public function moder() {
        $this->getModul()->moder();
    }

    public function activate($id = 0) {
        if ($id) {
            $this->setId($id);
        } else {
            $id = $this->getId();
        }
        $data = $this->getModul()->getDb()->getDataId($id);
//        if (get($data, 'visible') !== 'no')
//            return;
        $this->getModul()->getDb()->save(array('register' => 'yes', 'moder' => 'yes', 'visible' => 'yes'), $id);

//        $data['name'] = cmfUser::generateName($data);
//
//        $cmfMail = new cmfMail();
//        $cmfMail->sendTemplates('Личный кабинет: Регистрация (активация из админки)', $data, $data['email']);

        $this->getResponse()->reload();
    }

}

?>