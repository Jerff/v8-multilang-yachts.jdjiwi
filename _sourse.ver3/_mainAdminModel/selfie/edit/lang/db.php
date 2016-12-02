<?php

class selfie_edit_lang_db extends driver_db_lang_edit {

    public function returnParent() {
        return 'selfie_edit_db';
    }

    protected function getTable() {
        return db_selfie_lang;
    }

    protected function getWhereId($id) {
        return array('id'=>$id, 'AND', 'lang'=>cmfLang::getId());
	}

}

?>