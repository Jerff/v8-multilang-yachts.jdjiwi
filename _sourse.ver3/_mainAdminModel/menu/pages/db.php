<?php


class menu_pages_db extends driver_db_list {

	protected function getTable() {
		return db_menu_pages;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

}

?>