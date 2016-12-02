<?php


class menu_useful_db extends driver_db_list {

	protected function getTable() {
		return db_menu_useful;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}
}

?>