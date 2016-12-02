<?php

class board_list_db extends driver_db_list {

    public function returnParent() {
        return 'board_edit_db';
    }

    protected function getTable() {
        return db_board;
    }

    protected function getSort() {
        return array('main', '`update`' => 'DESC');
    }

    protected function getFields() {
        return array('id', 'date', 'user', 'main', 'name', 'moder', 'visible');
    }

    public function loadData(&$row) {
        $row['date'] = date("d.m.Y", strtotime($row['date']));
        parent::loadData($row);
    }

    protected function getWhereFilter() {
        $where = array(1);

        if ($search = $this->getFilter('search')) {
            $where[] = 'AND';
            $where[] = $this->getSql()->getQuery("(name LIKE '%?s%' OR email LIKE '%?s%' OR notice LIKE '%?s%')", urldecode($search), urldecode($search), urldecode($search));
        }
        return $where;
    }

    public function updateData($list, $send) {

    }

}

?>