<?php

class main_slider_edit_lang_db extends driver_db_lang_edit {

    public function returnParent() {
        return 'main_slider_edit_db';
    }

    protected function getTable() {
        return db_main_slider_lang;
    }

    protected function getWhereId($id) {
        return array('id'=>$id, 'AND', 'lang'=>cmfLang::getId(), 'AND', 'page' => $this->getFIlter('pageData'), 'AND', 'parent' => cmfAdminMenu::getSubMenuId());
	}

}

?>