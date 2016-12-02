<?php


class table_list_db extends driver_db_list {

	public function returnParent() {
		return 'table_edit_db';
	}

	protected function getTable() {
		return db_table;
	}

	public function updateData($list, $send) {
	}

}

?>