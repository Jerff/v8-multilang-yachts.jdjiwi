<?php


class menu_list_lang_db extends driver_db_list_lang {

	protected function getTable() {
		return db_menu_lang;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

}

?>