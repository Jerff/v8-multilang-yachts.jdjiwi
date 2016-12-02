<?php

class foto_list_db extends driver_db_gallery_list_lang {

    public function returnParent() {
        return 'foto_edit_db';
    }

    protected function getTable() {
        return db_foto;
    }

    protected function getTableLang() {
        return db_foto_lang;
    }

    protected function getFields() {
		return array('id', 'pos', 'image', 'image_main', 'image_small', 'main', 'visible');
	}

	protected function getFieldsLang() {
		return array('name');
	}

    public function loadData(&$row) {
        if ($row['image_main']) {
            $row['image_main'] = cmfBaseImg . cmfPathFoto . $row['image_main'];
        }
        if ($row['image_small']) {
            $row['image_small'] = cmfBaseImg . cmfPathFoto . $row['image_small'];
        }
        parent::loadData($row);
    }

}

?>