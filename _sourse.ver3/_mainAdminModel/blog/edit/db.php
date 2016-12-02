<?php


class blog_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_blog;
	}

	public function updateData($list, $send) {
		cmfControllerBlog::update($list);
	}

}

?>