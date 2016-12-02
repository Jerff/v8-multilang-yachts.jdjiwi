<?php


class menu_rigth_db extends driver_db_list {

	protected function getTable() {
		return db_menu_rigth;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

    protected function getWhereFilter() {
		$filter = array();
		$filter['level'] = $this->getFilter('section');
		return $filter;
	}

}

?>