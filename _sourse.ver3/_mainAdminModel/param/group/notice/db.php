<?php


class param_group_notice_db extends driver_db_list {

	protected function getTable() {
		return db_param_group_notice;
	}

    protected function getWhereFilter() {
        return array('group'=>$this->getFilter('group'));
    }

    protected function startSaveWhere() {
		return array('group');
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('param');
	}

}

?>