<?php


class reviews_list_db extends driver_db_lang_list {

	public function returnParent() {
		return 'reviews_edit_db';
	}

	protected function getTable() {
		return db_reviews;
	}

	protected function getTableLang() {
		return db_reviews_lang;
	}

	protected function getSort() {
		return array('main', 'date'=>'DESC');
	}

	protected function getFields() {
		return array('id', 'date', 'main', 'visible');
	}

	protected function getFieldsLang() {
		return array('header');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y", strtotime($row['date']));
		parent::loadData($row);
	}

	public function updateData($list, $send) {
	}

}

?>