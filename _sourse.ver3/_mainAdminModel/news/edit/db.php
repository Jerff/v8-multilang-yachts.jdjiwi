<?php


class news_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_news;
	}

	public function updateData($list, $send) {
		cmfControllerNews::update($list);
	}

}

?>