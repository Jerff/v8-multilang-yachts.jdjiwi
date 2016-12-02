<?php


class content_list_db extends driver_db_list_lang_tree {

	public function returnParent() {
		return 'content_edit_db';
	}

	protected function getTable() {
		return db_content;
	}

	protected function getTableLang() {
		return db_content_lang;
	}

	protected function getFields() {
		return array('id', 'parent', 'level', 'pos', 'isUri', 'visible');
	}

	protected function getFieldsLang() {
		return array('name');
	}

}

?>