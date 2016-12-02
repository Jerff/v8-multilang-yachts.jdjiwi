<?php

class arenda_list_db extends driver_db_list_lang_tree {

    public function returnParent() {
        return 'arenda_edit_db';
    }

    protected function getTable() {
        return db_arenda;
    }

    protected function getTableLang() {
        return db_arenda_lang;
    }

    protected function getFields() {
        return array('id', 'parent', 'level', 'pos', 'isUri', 'isProduct', 'visible');
    }

    protected function getFieldsLang() {
        return array('name');
    }

    public function filterStartView() {
        return (int) $this->getFilter('parent');
    }

    protected function getWhereFilter() {
        $filter = array();

        $arenda = $this->getFilter('parent');
        if ($arenda) {
            $filter[] = $this->getSql()->getQuery("path LIKE '%[?i]%'", $arenda);
            $filter[] = 'AND';
        }

        $filter[] = 1;
        return $filter;
    }

}

?>