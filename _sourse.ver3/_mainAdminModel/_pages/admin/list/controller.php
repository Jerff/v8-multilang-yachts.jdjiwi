<?php


class _pages_admin_list_controller extends driver_controller_list_one {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_pages_admin_list_modul');

		// url
		$this->setSubmitUrl('/admin/pages/admin/');
		$this->setEditUrl('/admin/pages/admin/');

		$this->callFuncWriteAdd('updatePages|updateCache');
	}


	public function delete($id) {
		$id = cmfModulLoad('_pages_admin_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function copy($id) {
		$this->copyInit(true);
		cmfModulLoad('_pages_admin_edit_controller')->copy($id);
		$this->copyInit(false);
	}


	protected function updatePages() {
		cmfUpdatePages::start();
	}

	protected function updateCache() {
		cmfCacheAdmin::deleteTag('access,menu,load');
	}


	public function getCount() {
		$listId = $this->getDataId();

		$type = cmfGlobal::get('pageType');
		$data = array();
		if(($type and $type!=='tree') or !count($listId)) return $data;

		$sql = $this->getSql();

		$query = '';
		$sep = '';
		foreach($listId as $id) {
			$query .= $sep .
			$sql->getQuery("(SELECT ?i AS id, count(id) AS count FROM ?t WHERE `type` IN('pages', 'pagesSystem') AND path LIKE '%[?i]%')", $id, db_pages_admin, $id);
			$sep = ' UNION ';
		}
		return $sql->query($query)->fetchRowAll(0, 1);
	}

}

?>