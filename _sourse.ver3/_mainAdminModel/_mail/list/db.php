<?php


class _mail_list_db extends driver_db_list {

	protected function getTable() {
		return db_mail_list;
	}

	protected function getSort() {
		return array('name');
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('mail');
	}

}

?>