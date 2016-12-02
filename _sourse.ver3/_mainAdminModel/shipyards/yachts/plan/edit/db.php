<?php


class shipyards_yachts_plan_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_shipyards_yachts_plan;
	}

    protected function startSaveWhere() {
        return array('yachts');
    }

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'yachts'=> cmfAdminMenu::getSubMenuId());
	}

}

?>