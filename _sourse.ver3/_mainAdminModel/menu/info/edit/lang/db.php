<?php


class menu_info_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'menu_info_edit_db';
	}

	protected function getTable() {
		return db_menu_info_lang;
	}

}

?>