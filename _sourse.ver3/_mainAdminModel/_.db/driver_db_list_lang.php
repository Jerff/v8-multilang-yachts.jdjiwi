<?php

abstract class driver_db_list_lang extends driver_db_list {

    protected function saveSetId($id) {

    }

    protected function getWhereId($id) {
        return array('id' => $id, 'AND', 'lang' => cmfLang::getId());
    }

    // ORDER для запросов к данных БД ---------------
    protected function getSort() {
        return array();
    }

    // where для запросов списков к данных БД ---------------
    protected function getWhereFilter() {
        return array(1);
    }

    // выборка данных записи из базы для стрницы
    public function runList($id = null, $offset = null, $limit = null) {
        $data = $this->getSql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->getTable(), $this->getWhereId($id))
                ->fetchAssocAll('id', 'lang');
        foreach ($data as $id => $row) {
            $data[$id] = cmfLang::resultList($row, cmfAdminNotTranslate);
            $this->loadData($data[$id]);
        }
        return $data;
    }

    public function langLoadData($list) {
        return $this->getSql()->placeholder("SELECT * FROM ?t WHERE id ?@", $this->getTable(), $list)
                        ->fetchAssocAll('id', 'lang');
    }

}

?>