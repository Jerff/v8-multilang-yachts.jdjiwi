<?php


class param_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'param_edit_db';
	}

	protected function getTable() {
		return db_param_lang;
	}

}

?>