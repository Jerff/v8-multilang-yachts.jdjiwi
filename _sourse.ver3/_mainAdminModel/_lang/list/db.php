<?php


class _lang_list_db extends driver_db_list {

	protected function getTable() {
		return db_lang;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('lang');
	}

}

?>