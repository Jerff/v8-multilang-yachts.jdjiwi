<?php

class cmfControllerArenda extends cmfControllerDriver {

    const name = '/arenda/';
    const ukraine = 3;

    static public function update($id = null) {
        self::updateUri($id);
        self::updateSearch($id);
        self::updateCountYachts($id);
    }

    static public function delete($id) {
        cmfContentUrl::delete(self::name, $id);
        cmfSearchData::delete(self::name, $id);
        self::updateCountYachts($id);
    }

    static public function updateSearch($where = null) {
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
        $fields = array('id', 'visible', 'name', 'content', 'isUri');
        $res = $sql->placeholder("SELECT ?fields FROM ?t WHERE ?w", $fields, db_arenda, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = $row['visible'];
            $send['id'] = $row['id'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/arenda/', array($row['isUri']));
            $send['content'] = cmfSearchData::reformStr($row['name'] . ' ' . $row['content']);
            $send['notice'] = strip_tags($row['content']);
            $send['name'] = $row['name'];
            cmfSearchData::save($send);
        }
    }

    static public function updateCountYachts($id = null) {
        if ($id) {
            $where = array('id' => $id);
        } else {
            $where = array(1);
        }
        cmfRegister::getSql()->placeholder("UPDATE ?t s SET s.isProduct=(SELECT count(y.id) FROM ?t y WHERE y.id IN(SELECT l.yachts FROM ?t l WHERE l.arenda=s.id) AND y.type IN(SELECT t.id FROM ?t t WHERE t.visible='yes') AND y.visible='yes') WHERE ?w:s", db_arenda, db_arenda_yachts, db_arenda_list, db_arenda_type, $where);
    }

    static public function updateUri($where = null) {
        self::updateWhere($where);
//        cmfRegister::getSql()->placeholder("
//                REPLACE ?t SELECT ?, id, 0, 0, isUri FROM ?t WHERE id!=? OR ?w", db_content_url, self::name, db_arenda, arendaMenu, $where);


        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, n.id, 0, CONCAT(n.isUri) FROM ?t n LEFT JOIN ?t i ON(i.pages='charter') WHERE ?w:n AND (n.id!=? AND n.path NOT LIKE '%[?s]%')", db_content_url, self::name, db_arenda, db_menu_info, $where, arendaMenu, arendaMenu);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, n.id, 0, CONCAT(n.isUri) FROM ?t n LEFT JOIN ?t i ON(i.pages='arenda-ukraine') WHERE ?w:n AND (n.id!=? AND n.path LIKE '%[?s]%')", db_content_url, self::name, db_arenda, db_menu_info, $where, arendaMenu, arendaMenu);
//        cmfControllerArendaType::updateUri($where);
    }

}

?>