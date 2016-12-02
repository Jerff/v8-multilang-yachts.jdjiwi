<?php


class menu_arenda_db extends driver_db_list {

	protected function getTable() {
		return db_menu_arenda;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}
}

?>