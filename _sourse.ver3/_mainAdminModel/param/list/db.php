<?php


class param_list_db extends driver_db_lang_list {

	public function returnParent() {
		return 'param_edit_db';
	}

	protected function getTable() {
		return db_param;
	}

    protected function getTableLang() {
        return db_param_lang;
    }

    protected function getFields() {
        return array('id', 'type', 'visible');
    }

    protected function getFieldsLang() {
        return array('name', 'prefix');
    }

	protected function getSort() {
		return array();
	}

	protected function getSortLang() {
		return array('name');
	}

	public function getParamList($section, $where) {
        $fileds = array('id', 'type', 'value');                                                          //
        $param = $this->getSql()->placeholder("SELECT ?fields:p FROM ?t p LEFT JOIN ?t l ON(l.param=p.id) WHERE l.`group`=? AND ?w:p AND p.id IN(SELECT param FROM ?t WHERE `group`=? AND visible='yes' UNION SELECT param FROM ?t WHERE `group`=? AND visible='yes') ORDER BY l.pos", $fileds, $this->getTable(), db_param_group_notice, $section, $where, db_param_group_notice, $section, db_param_group_search, $section)
        						->fetchAssocAll('id');
        $list = $this->getSql()->placeholder("SELECT id, lang, name, prefix  FROM ?t WHERE id ?@", $this->getTableLang(), array_keys($param))
            ->fetchAssocAll('id', 'lang');
        foreach($param as $id=>$row) {
            cmfLang::data($row, get($list, $id));
        	if($row['prefix']) {
        		$row['name'] .=  " ({$row['prefix']})";
        	}
        	$row['value'] = empty($row['value']) ? array() : unserialize($row['value']);
            $param[$id] = $row;
        }
        return $param;
	}

}

?>