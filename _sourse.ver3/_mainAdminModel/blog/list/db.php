<?php

class blog_list_db extends driver_db_lang_list {

    public function returnParent() {
        return 'blog_edit_db';
    }

    protected function getTable() {
        return db_blog;
    }

   protected function getTableLang() {
        return db_blog_lang;
    }

    protected function getSort() {
        return array('main', 'date' => 'DESC');
    }

    protected function getFields() {
        return array('id', 'autor', 'date', 'uri', 'main', 'visible');
    }

    protected function getFieldsLang() {
        return array('name');
    }

    protected function getWhereFilter() {
        $filter = array();

        $type = $this->getFilter('autor');
        if ($type) {
            $filter['autor'] = $type;
            $filter[] = 'AND';
        }

        $filter[] = 1;
        return $filter;
    }

    public function loadData(&$row) {
        $row['date'] = date("d.m.Y", strtotime($row['date']));
        parent::loadData($row);
    }

}

?>