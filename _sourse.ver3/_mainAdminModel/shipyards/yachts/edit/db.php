<?php


class shipyards_yachts_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_shipyards_yachts;
	}

	public function updateData($list, $send) {
		cmfControllerShipyardsYachts::update($list);
	}

	protected function startSaveWhere() {
		return array('type');
	}

}

?>