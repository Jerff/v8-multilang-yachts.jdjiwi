<?php


class shipyards_type_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_shipyards_type;
	}

	public function updateData($list, $send) {
		cmfControllerShipyardsType::update($list);
	}

}

?>