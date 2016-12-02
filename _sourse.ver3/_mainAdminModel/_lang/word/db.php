<?php


class _lang_word_db extends driver_db_list {

	protected function getTable() {
		return db_lang_word;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('lang');
	}

    public function getSort() {
        return array('name');
    }

}

?>