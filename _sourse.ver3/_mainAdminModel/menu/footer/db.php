<?php


class menu_footer_db extends driver_db_list {

	protected function getTable() {
		return db_menu_footer;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

}

?>