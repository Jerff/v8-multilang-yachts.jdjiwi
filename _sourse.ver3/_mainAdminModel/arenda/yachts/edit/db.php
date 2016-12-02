<?php


class arenda_yachts_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_arenda_yachts;
	}

	public function updateData($list, $send) {
		cmfControllerArendaYachts::update($list);
	}

	protected function startSaveWhere() {
		return array('type');
	}

}

?>