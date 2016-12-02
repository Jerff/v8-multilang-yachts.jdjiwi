<?php


class menu_sale_db extends driver_db_list {

	protected function getTable() {
		return db_menu_sale;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}
}

?>