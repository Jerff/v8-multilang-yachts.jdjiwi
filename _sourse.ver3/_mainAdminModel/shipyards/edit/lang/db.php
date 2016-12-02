<?php


class shipyards_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'shipyards_edit_db';
	}

	protected function getTable() {
		return db_shipyards_lang;
	}

}

?>