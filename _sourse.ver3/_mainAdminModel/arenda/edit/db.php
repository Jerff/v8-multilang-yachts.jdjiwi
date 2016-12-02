<?php


class arenda_edit_db extends driver_db_edit_tree {

	protected function getTable() {
		return db_arenda;
	}

	public function updateData($list, $send) {
		cmfControllerArenda::update($list);
	}

}

?>