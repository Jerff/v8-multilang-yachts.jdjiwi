<?php


class content_static_list_db extends driver_db_lang_list {

	public function returnParent() {
		return 'content_static_edit_db';
	}

	protected function getTable() {
		return db_content_static;
	}

	protected function getTableLang() {
		return db_content_static_lang;
	}

	protected function getSort() {
		return array('name');
	}

	protected function getFields() {
		return array('id', 'name', 'notice');
	}

	protected function getFieldsLang() {
		return array('content');
	}

	protected function getWhereFilter() {
		$filter = array();

		$section = $this->getFilter('section');
		if($section) {
			$section = addslashes(urldecode($section));
			$filter[] = "name LIKE '%$section%'";
			$filter[] = 'AND';
		}
		$filter[] = 1;
		return $filter;
	}

	public function filterSection() {
		$res = $this->getSql()->placeholder("SELECT DISTINCT(SUBSTRING_INDEX(name, ':', 1)) AS name FROM ?t ORDER BY ?o", $this->getTable(), $this->getSort());
		$filter = array();
		while($row=$res->fetchAssoc()) {
            $filter[($row['name'])] = array('name'=>$row['name']);
		}
		$res->free();
		return $filter;
	}

}

?>