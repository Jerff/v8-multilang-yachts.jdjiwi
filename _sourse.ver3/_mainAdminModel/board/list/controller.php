<?php

class board_list_controller extends driver_controller_list {

    protected function init() {
        parent::init();
        $this->addModul('main', 'board_list_modul');

        // url
        $this->setSubmitUrl('/admin/board/');
        $this->setEditUrl('/admin/board/edit/');
        $this->setUrl('user', '/admin/user/edit/');
        $this->callFuncWriteAdd('changeFilter|activate');
    }

    public function changeFilter() {
        $search = trim($this->getRequest()->getPost('search'));

        $opt = array();
        $opt['search'] = $search ? $search : null;
        $this->getResponse()->redirect($this->getSubmitUrl($opt));
    }

    public function viewListSiteUrl() {
        return parent::viewListSiteUrl('/board/item/', $this->getId());
    }

    public function delete($id) {
        $id = cmfModulLoad('board_edit_controller')->delete($id);
        return parent::delete($id);
    }

    public function activate($id) {
        cmfModulLoad('board_edit_controller')->activate($id);
    }

    public function getUserUrl($user) {
        $opt['id'] = $user;
        return $this->getUrl('user', $opt);
    }

    public function listUser() {
        $res = $this->getSql()->placeholder("SELECT id, email, login FROM ?t WHERE id IN(SELECT user FROM ?t WHERE id ?@ GROUP BY `user`)", db_user, db_board, $this->getDataId())
                ->fetchAssocAll('id');
        foreach ($res as $k => $v) {
            $res[$k] = cmfUser::generateName($v);
        }
        return $res;
    }

}

?>