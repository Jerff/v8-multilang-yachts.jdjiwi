<?php

class shipyards_type_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'shipyards_type_edit_db';
    }

    protected function getTable() {
        return db_shipyards_type;
    }

    protected function getTableLang() {
        return db_shipyards_type_lang;
    }

    protected function getFields() {
        return array('id', 'pos', 'uri', 'visible');
    }

    protected function getFieldsLang() {
        return array('menu', 'name');
    }

}

?>