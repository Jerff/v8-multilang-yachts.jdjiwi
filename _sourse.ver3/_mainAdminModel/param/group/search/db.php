<?php


class param_group_search_db extends driver_db_list {

	protected function getTable() {
		return db_param_group_search;
	}

	protected function getWhereFilter() {
		return array('group'=>$this->getFilter('group'));
	}

	protected function startSaveWhere() {
		return array('group');
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('param');
		switch($this->getFilter('group')) {
		    case 'shipyards';
		        cmfControllerShipyardsYachts::updateSearch();
		        break;
		}
	}
}

?>