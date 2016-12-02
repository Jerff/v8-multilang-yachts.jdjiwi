<?php


class table_data_db extends driver_db_list {

	protected function getTable() {
		return db_table_data;
	}

    public function loadData(&$row) {
        $field = cmfString::unserialize(get($row, 'value'));
        foreach (cmfGlobal::get('filedList') as $index => $value) {
            $row['field' .$index] = get($field, $index);
        }
        parent::loadData($row);
    }

	protected function startSaveWhere() {
		return array('table');
	}

    protected function getWhereFilter() {
		$filter = array();
		$filter['table'] = $this->getFilter('table');
		return $filter;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

}

?>