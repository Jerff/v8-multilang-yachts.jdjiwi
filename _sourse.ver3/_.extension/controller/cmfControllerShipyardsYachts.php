<?php

class cmfControllerShipyardsYachts extends cmfControllerDriver {

    const name = '/shipyards/yachts/';

    static public function update($id=0) {
        self::updateSearch($id);
        $shipyards = cmfRegister::getSql()->placeholder("SELECT shipyards FROM ?t WHERE id ?@", db_shipyards_yachts, $id)
                ->fetchRow(0);
        cmfControllerShipyards::updateCountYachts($shipyards);
        self::updateUri(null, $id);
    }

    static public function deleteShipyards($id) {
        cmfSearchData::delete(self::name, array('shipyards' => $id));
    }

    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
        cmfControllerShipyards::updateCountYachts();
    }

    static public function updateSearch($where = null) {
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
        $mParam = cmfModulLoad('param_edit_db')->getFeildOfId('value', lengthId);
        $mParam = cmfString::unserialize($mParam);
        $res = $sql->placeholder("SELECT param FROM ?t WHERE param IN(SELECT id FROM ?t WHERE visible='yes') AND `group`='shipyards' AND visible='yes' ORDER BY pos", db_param_group_search, db_param)
                ->fetchRowAll();
        $_param = array();
        for ($i = 0; $i < 10; $i++) {
            $_param['param' . $i] = get2($res, $i, 0, 0);
        }

//        $fields = array('id', 'visible', 'type', 'shipyards', 'uri', 'param', 'name', 'notice');
        $fields1 = array('id', 'visible', 'type', 'shipyards', 'uri', 'param', 'price', 'currency');
        $fields2 = array('lang', 'name', 'notice');
        $res = $sql->placeholder("SELECT ?fields:y, ?fields:yL, s.uri AS sUri, s.visible AS sVisible, t.visible AS tVisible FROM ?t y LEFT JOIN ?t yL ON(yL.id=y.id) LEFT JOIN ?t s ON(y.shipyards=s.id) LEFT JOIN ?t t ON(y.type=t.id) WHERE ?w:y", $fields1, $fields2, db_shipyards_yachts, db_shipyards_yachts_lang, db_shipyards, db_shipyards_type, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = ($row['visible'] == 'yes' and $row['sVisible'] == 'yes' and $row['tVisible'] == 'yes') ? 'yes' : 'no';
            $send['section'] = $row['shipyards'];
            $send['price'] = cmfPrice::priceToUsd($row['price'], $row['currency']);
            $send['name'] = $row['name'];
            $send['type'] = $row['type'];
            $send['id'] = $row['id'];
            $send['lang'] = $row['lang'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl(self::name, array($row['sUri'], $row['uri']));
            $send['content'] = cmfSearchData::reformStr($row['name'] . ' ' . $row['notice']);
            $send['notice'] = strip_tags($row['notice']);
            $param = cmfString::unserialize($row['param']);
            $send['length'] = get($mParam, get($param, lengthId, -1), 0);

            cmfSearchData::save($send);
        }
    }

    static public function updateUri($where1 = null, $where2 = null) {
        self::updateWhere($where1);
        self::updateWhere($where2);

        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, s.id, y.id, CONCAT(i.isUri, '/', s.uri, '/', y.uri) FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) LEFT JOIN ?t i ON(i.pages='shipyards')  WHERE ?w:s AND ?w:y", db_content_url, self::name, db_shipyards_yachts, db_shipyards, db_menu_info, $where1, $where2);
    }

}

?>