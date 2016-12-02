<?php

class selfie_list_db extends driver_db_gallery_list_lang {

    public function returnParent() {
        return 'selfie_edit_db';
    }

    protected function getTable() {
        return db_selfie;
    }

    protected function getTableLang() {
        return db_selfie_lang;
    }

    protected function getFields() {
        return array('id', 'pos', 'image', 'image_main', 'image_small', 'main', 'visible');
    }

    protected function getFieldsLang() {
        return array('name');
    }

    public function loadData(&$row) {
        if ($row['image_main']) {
            $row['image_main'] = cmfBaseImg . cmfPathSelfie . $row['image_main'];
        }
        if ($row['image_small']) {
            $row['image_small'] = cmfBaseImg . cmfPathSelfie . $row['image_small'];
        }
        parent::loadData($row);
    }

}

?>