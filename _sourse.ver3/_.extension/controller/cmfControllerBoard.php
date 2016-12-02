<?php

cmfLoad('controller/cmfControllerDriver');
class cmfControllerBoard extends cmfControllerDriver {

    const name = '/board/item/';

    static public function update($id = null) {
        self::updateUri($id);
        self::updateSearch($id);
    }

    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
    }

    static public function updateSearch($where = null) {
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
        $fields = array('id', 'visible', 'name', 'phone', 'contact', 'notice');
        $res = $sql->placeholder("SELECT ?fields:a FROM ?t a WHERE ?w:a", $fields, db_board, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = $row['visible'];
            $send['id'] = $row['id'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/board/item/', array($row['id']));
            $send['content'] = cmfSearchData::reformStr($row['name'] . ' ' . $row['phone'] . ' ' . $row['contact'] . ' ' . $row['notice']);
            $send['notice'] = strip_tags($row['notice']);
            $send['name'] = $row['name'];

            $send['lang'] = 1;
            cmfSearchData::save($send);
            $send['lang'] = 2;
            cmfSearchData::save($send);
        }
    }

    static public function updateUri($where = null) {
        self::updateWhere($where);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, n.id, 0, CONCAT(i.isUri, '/', n.id) FROM ?t n LEFT JOIN ?t i ON(i.pages='board') WHERE ?w:n", db_content_url, self::name, db_board, db_menu_info, $where);
    }

}

?>