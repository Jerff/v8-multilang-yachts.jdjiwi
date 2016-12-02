<?php


class main_info_db extends driver_db_edit {

	protected function getTable() {
		return db_main;
	}

	public function updateData($list, $send) {
		cmfControllerMainInfo::update($list);
	}

}

?>