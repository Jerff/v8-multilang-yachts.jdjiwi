<?php


class brokerage_yachts_foto_multi_db extends driver_db_list {

    public function returnParent() {
		return 'brokerage_yachts_foto_edit_db';
	}

	protected function getTable() {
		return db_brokerage_yachts_foto;
	}

    public function runList($id=null, $offset=null, $limit=null) {
		return array();
	}

    protected function startSaveWhere() {
        return array('yachts');
    }

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'yachts'=> cmfAdminMenu::getSubMenuId());
	}

}

?>