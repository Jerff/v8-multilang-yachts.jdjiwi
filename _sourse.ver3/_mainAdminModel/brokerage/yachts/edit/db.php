<?php


class brokerage_yachts_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_brokerage_yachts;
	}

	public function updateData($list, $send) {
		cmfConfigBrokerageYachts::update($list);
	}

}

?>