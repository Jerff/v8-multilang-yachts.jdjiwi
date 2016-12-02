<?php

class arenda_yachts_plan_list_db extends driver_db_gallery_list {

    protected function getTable() {
        return db_arenda_yachts_plan;
    }

    public function returnParent() {
        return 'arenda_yachts_plan_edit_db';
    }

    public function loadData(&$row) {
        if ($row['image_main']) {
            $row['image_main'] = cmfBaseImg . cmfPathArendaYachtsPlan . $row['image_main'];
        }
        if ($row['image_small']) {
            $row['image_small'] = cmfBaseImg . cmfPathArendaYachtsPlan . $row['image_small'];
        }
        parent::loadData($row);
    }

    protected function getSort() {
        return array('main', 'pos');
    }

    protected function startSaveWhere() {
        return array('yachts');
    }

    protected function getWhereFilter() {
        return array('yachts' => cmfAdminMenu::getSubMenuId());
    }

    protected function getWhereId($list) {
        return array('id' => $list, 'AND', 'yachts' => cmfAdminMenu::getSubMenuId());
    }

}

?>