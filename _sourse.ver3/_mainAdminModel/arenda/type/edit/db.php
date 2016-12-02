<?php

class arenda_type_edit_db extends driver_db_edit {

    protected function getTable() {
        return db_arenda_type;
    }

    public function updateData($list, $send) {
        cmfControllerArendaType::update($list);
    }

}

?>