<?php


class _seo_head_db extends driver_db_list {

	protected function getTable() {
		return db_seo_head;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('head');
	}

	protected function getSort() {
		return array('url'=>'DESC');
	}

}

?>