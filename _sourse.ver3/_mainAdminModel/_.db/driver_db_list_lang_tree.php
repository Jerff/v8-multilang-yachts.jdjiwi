<?php


abstract class driver_db_list_lang_tree extends driver_db_list_tree {


	protected function _runListData(&$data) {
	    if($data) {
            $list = $this->getSql()->placeholder("SELECT id, lang, ?f FROM ?t WHERE ?w", $this->getFieldsLang(), $this->getTableLang(), $this->getWhereId(array_keys($data)))
                                        ->fetchAssocAll('id', 'lang');
            foreach($data as $id=>&$row) {
                cmfLang::result($row, get($list, $id), cmfAdminNotTranslate);
            }
		}
	}

	public function getNameList($filter=null, $fileds=null, $isName=true) {
		if(isset($fileds[0]) and is_array($fileds[0])) {
		    list($fileds, $filedsLang) = $fileds;
		} else {
		    $filedsLang = array();
		}
		if(is_null($filter)) $filter = $this->getWhereFilter();
		if(is_array($fileds)) {
			array_push($fileds, 'id', 'parent');
		} else {
			$fileds = array('id', 'parent');
		}
		if($isName) $filedsLang[] = 'name';
		$filedsLang = array_combine($filedsLang, $filedsLang);

		$name = $this->getSql()->placeholder("SELECT ?f FROM ?t WHERE ?w ORDER BY ?o", $fileds, $this->getTable(), $filter, $this->getSort())
									->fetchAssocAll('parent', 'id');
        $lang = $this->getSql()->placeholder("SELECT id, lang, ?f FROM ?t WHERE id IN(SELECT id FROM ?t WHERE ?w)", $filedsLang, $this->getTableLang(), $this->getTable(), $filter)
                                    ->fetchAssocAll('id', 'lang');
        foreach($name as $parent=>&$list) {
            foreach($list as $id=>&$row) {
                cmfLang::completeList($row, get($lang, $id), $filedsLang, cmfAdminNotTranslate);
            }
        }
		$line = array();
		$this->getNameListTree($name, $line);
		return $line;
	}

}

?>