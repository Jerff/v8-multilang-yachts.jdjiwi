<?php


class article_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_article;
	}

	public function updateData($list, $send) {
		cmfControllerArticle::update($list);
	}

}

?>