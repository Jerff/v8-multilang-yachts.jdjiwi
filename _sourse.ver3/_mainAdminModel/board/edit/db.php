<?php

class board_edit_db extends driver_db_edit {

    protected function getTable() {
        return db_board;
    }

    public function updateData($list, $send) {
        cmfControllerBoard::update($list);
    }

    public function loadData(&$data) {
        $param = cmfString::unserialize($data['param']);
        foreach (cmfGlobal::get('$param') as $k => $v) {
            foreach (array('paramKey' . $k, 'paramValue' . $k) as $key) {
                $data[$key] = get($param, $key);
            }

        }
    }

}

?>