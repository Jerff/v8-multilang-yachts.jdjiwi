<?php


class shipyards_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_shipyards;
	}

	public function updateData($list, $send) {
		cmfControllerShipyards::update($list);
	}

}

?>