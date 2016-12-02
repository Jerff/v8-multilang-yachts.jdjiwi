<?php

class foto_edit_lang_db extends driver_db_lang_edit {

    public function returnParent() {
        return 'foto_edit_db';
    }

    protected function getTable() {
        return db_foto_lang;
    }

    protected function getWhereId($id) {
        return array('id'=>$id, 'AND', 'lang'=>cmfLang::getId());
	}

}

?>