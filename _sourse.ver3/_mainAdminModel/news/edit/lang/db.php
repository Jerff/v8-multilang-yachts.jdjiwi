<?php


class news_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'news_edit_db';
	}

	protected function getTable() {
		return db_news_lang;
	}

}

?>