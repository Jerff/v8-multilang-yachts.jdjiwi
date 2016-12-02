<?php

class shipyards_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'shipyards_edit_db';
    }

    protected function getTable() {
        return db_shipyards;
    }

    protected function getTableLang() {
        return db_shipyards_lang;
    }

    protected function getFields() {
        return array('id', 'pos', 'uri', 'isProduct', 'isHeader', 'main', 'visible');
    }

    protected function getFieldsLang() {
        return array('name');
    }

    protected function getSort() {
        return array('main', 'pos');
    }

    protected function getWhereFilter() {
        $filter = array();

        $logo = $this->getFilter('logo');
        if ($logo) {
            $filter[] = "(logo IS NULL OR logo='')";
            $filter[] = 'AND';
        }

        $filter[] = 1;
        return $filter;
    }

}

?>