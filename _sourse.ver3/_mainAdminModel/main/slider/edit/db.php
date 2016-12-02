<?php


class main_slider_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_main_slider;
	}

    protected function startSaveWhere() {
        return array('page', 'parent');
    }

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'page'=>$this->getFilter('pageData'), 'AND', 'parent'=>cmfAdminMenu::getSubMenuId());
	}

}

?>