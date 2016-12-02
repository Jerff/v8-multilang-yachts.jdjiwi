<?php


class _seo_redirect_db extends driver_db_list {

	protected function getTable() {
		return db_seo_redirect;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('redirect');
	}

	protected function getSort() {
		return array('type'=>'DESC', 'url');
	}

}

?>