<?php

class brokerage_type_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'brokerage_type_edit_db';
    }

    protected function getTable() {
        return db_brokerage_type;
    }

    protected function getTableLang() {
        return db_brokerage_type_lang;
    }

    protected function getFields() {
        return array('id', 'pos', 'uri', 'visible');
    }

    protected function getFieldsLang() {
        return array('name', 'menu');
    }

}

?>