<?php

class cmfControllerShipyards extends cmfControllerDriver {

    const name = '/shipyards/';
    const page = '/shipyards/one/';

    static public function update($id) {
        self::updateSearch($id);
        self::updateCountYachts($id);
        self::updateUri($id);
    }

    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
        cmfControllerShipyardsYachts::deleteShipyards($id);
    }

    static public function updateSearch($where = null) {
        if ($where)
            cmfControllerShipyardsYachts::updateSearch(array('shipyards' => $where));
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
//        $fields = array('id', 'visible', 'name', 'content', 'uri');
//        $res = $sql->placeholder("SELECT ?fields FROM ?t WHERE ?w", $fields, db_shipyards, $where)
//                ->fetchAssocAll();
        $fields1 = array('id', 'visible', 'uri');
        $fields2 = array('lang', 'name', 'content');
        $res = $sql->placeholder("SELECT ?fields:a, ?fields:b FROM ?t a LEFT JOIN ?t b ON(b.id=a.id) WHERE ?w:a", $fields1, $fields2, db_shipyards, db_shipyards_lang, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = $row['visible'];
            $send['id'] = $row['id'];
            $send['lang'] = $row['lang'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/shipyards/one/', array($row['uri']));
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
        cmfRegister::getSql()->placeholder("UPDATE ?t s SET s.isProduct=(SELECT count(y.id) FROM ?t y WHERE y.shipyards=s.id AND y.type IN(SELECT t.id FROM ?t t WHERE t.visible='yes') AND y.visible='yes') WHERE ?w:s", db_shipyards, db_shipyards_yachts, db_shipyards_type, $where);
    }

    static public function updateUri($where1 = null) {
        self::updateWhere($where1);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, s.id, 0, CONCAT(i.isUri, '/', s.uri) FROM ?t s LEFT JOIN ?t i ON(i.pages='shipyards') WHERE ?w:s", db_content_url, self::page, db_shipyards, db_menu_info, $where1);

        cmfControllerShipyardsYachts::updateUri($where1);
    }

}

?>