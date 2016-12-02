<?php

abstract class driver_db_list_all_lang extends driver_db_list {

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

        $data = $this->getSql()->placeholder("SELECT ?fields FROM ?t WHERE ?w ORDER BY ?o", $fileds, $this->getTable(), $filter, $this->getSort())
                ->fetchAssocAll('id');
        if (!$data)
            return $data;
        $list = $this->getSql()->placeholder("SELECT id, lang, ?f FROM ?t WHERE id ?@", $filedsLang, $this->getTableLang(), array_keys($data))
                ->fetchAssocAll('id', 'lang');

        foreach ($data as $id => &$row) {
            cmfLang::completeList($row, get($list, $id), $filedsLang, cmfAdminNotTranslate);
        }
        return $data;
    }

}

?>