<?php


class reviews_brand_lang_db extends driver_db_list_lang {

	protected function getTable() {
		return db_brand_lang;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

}

?>