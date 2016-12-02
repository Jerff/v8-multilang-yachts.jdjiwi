<?php


class main_info_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'main_info_db';
	}

	protected function getTable() {
		return db_main_lang;
	}

}

?>