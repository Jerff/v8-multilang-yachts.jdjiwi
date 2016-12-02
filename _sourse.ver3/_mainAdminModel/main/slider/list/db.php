<?php

class main_slider_list_db extends driver_db_lang_list {


    public function returnParent() {
        return 'main_slider_edit_db';
    }

    protected function getTable() {
        return db_main_slider;
    }

    protected function getTableLang() {
        return db_main_slider_lang;
    }

    public function loadData(&$row) {
        if ($row['image']) {
            $row['image'] = cmfBaseImg . cmfPathMain . $row['image'];
        }
        parent::loadData($row);
    }

    protected function getSort() {
        return array('main', 'pos' => 'DESC');
    }

    protected function getFields() {
		return array('id', 'parent', 'pos', 'image', 'main', 'visible');
	}

	protected function getFieldsLang() {
		return array('name');
	}

    protected function startSaveWhere() {
        return array('page', 'parent');
    }

    protected function getWhereFilter() {
        return array('page' => $this->getFIlter('pageData'), 'AND', 'parent' => cmfAdminMenu::getSubMenuId());
    }

    protected function getWhereId($list) {
        return array('id' => $list, 'AND', 'page' => $this->getFIlter('pageData'), 'AND', 'parent' => cmfAdminMenu::getSubMenuId());
    }

}

?>