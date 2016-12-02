<?php


class article_list_db extends driver_db_lang_list {

	public function returnParent() {
		return 'article_edit_db';
	}

	protected function getTable() {
		return db_article;
	}

	protected function getTableLang() {
		return db_article_lang;
	}

	protected function getSort() {
		return array('main', 'date'=>'DESC');
	}

	protected function getFields() {
		return array('id', 'date', 'uri', 'main', 'visible');
	}

	protected function getFieldsLang() {
		return array('header', 'notice');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y", strtotime($row['date']));
		parent::loadData($row);
	}

	public function updateData($list, $send) {
	}

}

?>