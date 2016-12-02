<?php


class content_edit_db extends driver_db_edit_tree_lang {

	protected function getTable() {
		return db_content;
	}

	protected function getTableLang() {
		return db_content_lang;
	}

	public function updateData($list, $send) {
		cmfControllerContent::update($list);
	}

}

?>