<?php


class brokerage_type_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'brokerage_type_edit_db';
	}

	protected function getTable() {
		return db_brokerage_type_lang;
	}

}

?>