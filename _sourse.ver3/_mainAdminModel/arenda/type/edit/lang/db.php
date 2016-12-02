<?php

class arenda_type_edit_lang_db extends driver_db_lang_edit {

    public function returnParent() {
        return 'arenda_type_edit_db';
    }

    protected function getTable() {
        return db_arenda_type_lang;
    }

}

?>