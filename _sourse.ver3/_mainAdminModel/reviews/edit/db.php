<?php


class reviews_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_reviews;
	}

	public function updateData($list, $send) {
//		cmfControllerArticle::update($list);
	}

}

?>