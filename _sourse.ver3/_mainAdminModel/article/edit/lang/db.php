<?php


class article_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'article_edit_db';
	}

	protected function getTable() {
		return db_article_lang;
	}

}

?>