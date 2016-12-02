<?php


class _pages_access_read_db extends driver_db_list_product_attach {

	protected function getTable() {
		return db_access_read;
	}

	protected function attach()  {
		return 'group';
	}

	protected function product()  {
		return 'modul';
	}

	public function updateData($list, $send) {
		if($send) {
			cmfCacheAdmin::deleteTag('access');
		}
	}

	public function getPagesRead() {
		$_admin = cmfModulLoad('_administrator_group_list_db')->getListId();

		return $this->getSql()->placeholder("SELECT * FROM ?t WHERE `group` ?@", $this->getTable(), $_admin)
									->fetchAssocAll('modul', 'group', 'group');
	}

}

?>