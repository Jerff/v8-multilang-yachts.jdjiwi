<?php

class _seo_copyright_db extends driver_db_edit {

    protected function getTable() {
        return db_seo_copyright;
    }

    protected function getWhereId($id) {
        return array('id' => $id, 'AND', 'lang' => cmfLang::getId());
    }

    public function updateData($list, $send) {
        cmfUpdateCache::update('seoCopyright');
    }

}

?>