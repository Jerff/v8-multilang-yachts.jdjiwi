<?php


class shipyards_yachts_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'shipyards_yachts_edit_db';
	}

	protected function getTable() {
		return db_shipyards_yachts_lang;
	}

}

?>