<?php

abstract class driver_db_gallery_list_lang extends driver_db_gallery_list {

    abstract protected function getTableLang();

    protected function getSortLang() {
        return array('id');
    }

    protected function getFieldsLang() {
        return array('*');
    }

    public function runList($id = null, $offset = null, $limit = null) {
        if (is_null($id)) {
            $data = $this->getSql()->placeholder("SELECT SQL_CALC_FOUND_ROWS ?fields:a FROM ?t a LEFT JOIN ?t b ON(a.id=b.id) WHERE ?w:a GROUP BY a.id ORDER BY ?o:a, ?o:b LIMIT ?i, ?i ", $this->getFields(), $this->getTable(), $this->getTableLang(), $this->getWhereFilter(), $this->getSort(), $this->getSortLang(), $offset, $limit)
                    ->fetchAssocAll('id');
        } else {
            if (empty($id))
                return false;
            $data = $this->getSql()->placeholder("SELECT ?fields:a FROM ?t a WHERE ?w:a", $this->getFields(), $this->getTable(), $this->getWhereId($id))
                    ->fetchAssocAll('id');
        }                 //cmfAdminNotTranslate
        if ($data) {
            $list = $this->getSql()->placeholder("SELECT id, lang, ?f FROM ?t WHERE ?w", $this->getFieldsLang(), $this->getTableLang(), $this->getWhereId(array_keys($data)))
                    ->fetchAssocAll('id', 'lang');
            if (is_null($id)) {
                $this->getSql()->placeholder("SELECT SQL_CALC_FOUND_ROWS 1 FROM ?t a WHERE ?w GROUP BY a.id LIMIT 0, 1", $this->getTable(), $this->getWhereFilter())
                        ->fetchAssocAll();
            }
            foreach ($data as $id => &$row) {
                cmfLang::result($row, get($list, $id), cmfAdminNotTranslate);
                $this->loadData($row);
            }
        }
        return $data;
    }

    protected function runListQuery($id = null, $offset = null, $limit = null) {

    }

    protected function &runListData($res) {
        $data = array();
        if ($res) {
            while ($row = $res->fetchAssoc()) {
                $this->loadData($row);
                $this->loadSetDataList($data, $row);
            }
            $res->free();
        }
        return $data;
    }

}

?>