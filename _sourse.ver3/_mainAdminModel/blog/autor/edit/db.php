<?php


class blog_autor_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_blog_autor;
	}

	public function updateData($list, $send) {
		cmfControllerBlogAutor::update($list);
	}

}

?>