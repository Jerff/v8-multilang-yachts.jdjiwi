<?php


class shipyards_yachts_plan_multi_db extends driver_db_list {

    public function returnParent() {
		return 'shipyards_yachts_plan_edit_db';
	}

	protected function getTable() {
		return db_shipyards_yachts_plan;
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