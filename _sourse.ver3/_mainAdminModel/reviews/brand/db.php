<?php


class reviews_brand_db extends driver_db_list {

	protected function getTable() {
		return db_brand;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}

}

?>