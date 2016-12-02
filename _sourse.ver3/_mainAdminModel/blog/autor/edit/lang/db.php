<?php


class blog_autor_edit_lang_db extends driver_db_lang_edit {

	public function returnParent() {
		return 'blog_autor_edit_db';
	}

	protected function getTable() {
		return db_blog_autor_lang;
	}

}

?>