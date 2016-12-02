<?php


class content_static_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'content_static_edit_db';
	}

	protected function getTable() {
		return db_content_static_lang;
	}

}

?>