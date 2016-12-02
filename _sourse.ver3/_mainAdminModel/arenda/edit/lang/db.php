<?php


class arenda_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'arenda_edit_db';
	}

	protected function getTable() {
		return db_arenda_lang;
	}

}

?>