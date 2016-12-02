<?php


class arenda_yachts_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'arenda_yachts_edit_db';
	}

	protected function getTable() {
		return db_arenda_yachts_lang;
	}

}

?>