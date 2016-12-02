<?php


class menu_info_edit_db extends driver_db_edit_tree_lang {

	protected function getTable() {
		return db_menu_info;
	}

	protected function getTableLang() {
		return db_menu_info_lang;
	}

	public function updateData($list, $send) {
		cmfControllerInfo::update($list);
	}

}

?>