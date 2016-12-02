<?php


class reviews_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'reviews_edit_db';
	}

	protected function getTable() {
		return db_reviews_lang;
	}

}

?>