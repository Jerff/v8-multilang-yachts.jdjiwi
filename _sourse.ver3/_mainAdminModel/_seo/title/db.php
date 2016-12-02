<?php


class _seo_title_db extends driver_db_edit {

	protected function getTable() {
		return db_seo_title;
	}

	public function save($data) {
		if(count($data)) {
			if(isset($data['uri'])) {
				$uri = $data['uri'];
				$this->setId($uri);
			}
			else $uri = $this->getId();
			unset($data['uri']);
			$this->getSql()->add2($this->getTable(), $data, $this->getWhere());
			$this->setUpdateData($data);
		}
	}


/*	public function delete() {
		$this->getSql()->del($this->getTable(), $this->getWhere());
	}
*/
	protected function getWhereId($id) {
		return array('uri'=>$id, 'AND', 'lang'=>cmfLang::getId());
	}


	public function updateData($list, $send) {
		cmfUpdateCache::update('seoTitle');
	}

}

?>