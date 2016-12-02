<?php

class wallpapers_edit_lang_db extends driver_db_lang_edit {

    public function returnParent() {
        return 'wallpapers_edit_db';
    }

    protected function getTable() {
        return db_wallpapers_lang;
    }

    protected function getWhereId($id) {
        return array('id'=>$id, 'AND', 'lang'=>cmfLang::getId());
	}

}

?>