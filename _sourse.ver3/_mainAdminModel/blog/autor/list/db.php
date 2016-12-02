<?php

class blog_autor_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'blog_autor_edit_db';
    }

    protected function getTable() {
        return db_blog_autor;
    }

    protected function getTableLang() {
        return db_blog_autor_lang;
    }

    protected function getFields() {
        return array('id', 'isBlog', 'visible');
    }

    protected function getFieldsLang() {
        return array('name');
    }

    protected function getSort() {
        return array('id');
    }

//    protected function getSortLang() {
//        return array('name');
//    }

}

?>