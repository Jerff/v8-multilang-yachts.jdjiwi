<?php


class brokerage_type_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_brokerage_type;
	}

	public function updateData($list, $send) {
		cmfConfigBrokerageType::update($list);
	}

}

?>