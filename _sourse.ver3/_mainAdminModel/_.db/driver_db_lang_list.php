<?php

abstract class driver_db_lang_list extends driver_db_list {

    abstract protected function getTableLang();

    protected function getSortLang() {
        return array('id');
    }

    protected function getFieldsLang() {
        return array('*');
    }

    public function runList($id = null, $offset = null, $limit = null) {
        if (is_null($id)) {
            if ($this->getSort()) {
                $data = $this->getSql()->placeholder("SELECT SQL_CALC_FOUND_ROWS ?fields:a FROM ?t a LEFT JOIN ?t b ON(a.id=b.id) WHERE ?w:a GROUP BY a.id ORDER BY ?o:a, ?o:b LIMIT ?i, ?i ", $this->getFields(), $this->getTable(), $this->getTableLang(), $this->getWhereFilter(), $this->getSort(), $this->getSortLang(), $offset, $limit)
                        ->fetchAssocAll('id');
            } else {
                $data = $this->getSql()->placeholder("SELECT SQL_CALC_FOUND_ROWS ?fields:a FROM ?t a LEFT JOIN ?t b ON(a.id=b.id) WHERE ?w:a GROUP BY a.id ORDER BY ?o:b LIMIT ?i, ?i ", $this->getFields(), $this->getTable(), $this->getTableLang(), $this->getWhereFilter(), $this->getSortLang(), $offset, $limit)
                        ->fetchAssocAll('id');
            }
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

    public function getNameList($filter = null, $fileds = null, $isName = true) {
        if (isset($fileds[0]) and is_array($fileds[0])) {
            list($fileds, $filedsLang) = $fileds;
        } else {
            $filedsLang = array();
        }
        if (is_null($filter))
            $filter = $this->getWhereFilter();
        if (is_array($fileds)) {
            array_push($fileds, 'id');
        } else {
            $fileds = array('id');
        }
        if ($isName)
            $filedsLang[] = 'name';
        $filedsLang = array_combine($filedsLang, $filedsLang);
        //cmfLang::getId(),

        $data = $this->getSql()->placeholder("SELECT ?fields:i FROM ?t i LEFT JOIN ?t l ON(i.id=l.id) WHERE ?w:i ORDER BY ?o:i". ($this->getSort()? ', ': ' ') ."?o:l", $fileds, $this->getTable(), $this->getTableLang(), $filter, $this->getSort(), $this->getSortLang())
                ->fetchAssocAll('id');
        if (!$data)
            return $data;
        $list = $this->getSql()->placeholder("SELECT id, lang, ?f FROM ?t WHERE ?w", $filedsLang, $this->getTableLang(), $this->getWhereId(array_keys($data)))
                ->fetchAssocAll('id', 'lang');

        foreach ($data as $id => &$row) {
            cmfLang::completeList($row, get($list, $id), $filedsLang, cmfAdminNotTranslate);
        }
        return $data;
    }

}

?>