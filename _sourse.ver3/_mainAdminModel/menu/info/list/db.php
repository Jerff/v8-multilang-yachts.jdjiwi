<?php


class menu_info_list_db extends driver_db_list_lang_tree {

	public function returnParent() {
		return 'menu_info_edit_db';
	}

	protected function getTable() {
		return db_menu_info;
	}

	protected function getTableLang() {
		return db_menu_info_lang;
	}

	protected function getFields() {
		return array('id', 'parent', 'level', 'pos', 'isUri', 'visible');
	}

	protected function getFieldsLang() {
		return array('name');
	}

}

?>