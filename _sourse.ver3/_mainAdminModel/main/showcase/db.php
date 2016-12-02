<?php

class main_showcase_db extends driver_db_list {

    function __construct($id = null) {
        $this->setIdName('image');
        parent::__construct($id);
    }

    protected function getTable() {
        return db_showcase;
    }

    public function setNewRecord() {
        cmfCommand::set('$isReload');
    }

}

?>