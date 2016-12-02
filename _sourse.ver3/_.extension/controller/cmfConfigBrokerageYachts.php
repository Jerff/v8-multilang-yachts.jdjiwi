<?php

class cmfConfigBrokerageYachts extends cmfControllerDriver {

    const name = '/brokerage/yachts/';

    static public function update($id = null) {
        self::updateSearch($id);
        self::updateUri(null, $id);
    }

    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
    }

    static public function updateSearch($where = null) {
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
        $mParam = cmfModulLoad('param_edit_db')->getFeildOfId('value', lengthId);
        $mParam =cmfString::unserialize($mParam);
//        $fields = array('id', 'visible', 'type', 'uri', 'name', 'notice');
//        $res = $sql->placeholder("SELECT ?fields:y, t.uri AS tUri, t.visible AS tVisible FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE ?w:y", $fields, db_brokerage_yachts, db_brokerage_type, $where)
//                ->fetchAssocAll();
        $fields1 = array('id', 'visible', 'type', 'param', 'price', 'currency', 'uri');
        $fields2 = array('lang', 'name', 'shipyardsName', 'notice');
        $res = $sql->placeholder("SELECT ?fields:a, ?fields:b, t.uri AS tUri, t.visible AS tVisible FROM ?t a LEFT JOIN ?t b ON(b.id=a.id) LEFT JOIN ?t t ON(a.type=t.id) WHERE ?w:a", $fields1, $fields2, db_brokerage_yachts, db_brokerage_yachts_lang, db_brokerage_type, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = ($row['visible'] == 'yes' and $row['tVisible'] == 'yes') ? 'yes' : 'no';
            $send['price'] = cmfPrice::priceToUsd($row['price'], $row['currency']);
            $send['section'] = 0;
            $send['type'] = $row['type'];
            $send['id'] = $row['id'];
            $send['lang'] = $row['lang'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl(self::name, array($row['tUri'], $row['uri']));
            $send['name'] = $row['name'];
            $send['shipyardsName'] = $row['shipyardsName'];
            $send['notice'] = strip_tags($row['notice']);
            $send['content'] = cmfSearchData::reformStr($row['name'] . ' ' . $row['notice']);
            $param = cmfString::unserialize($row['param']);
            $send['length'] = get($mParam, get($param, lengthId, -1), 0);
            cmfSearchData::save($send);
        }
    }

    static public function updateUri($where1 = null, $where2 = null) {
        self::updateWhere($where1);
        self::updateWhere($where2);

        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, t.id, y.id, CONCAT(i.isUri, '/', t.uri, '/', y.uri) FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) LEFT JOIN ?t i ON(i.pages='brokerage')  WHERE ?w:t AND ?w:y", db_content_url, self::name, db_brokerage_yachts, db_brokerage_type, db_menu_info, $where1, $where2);
    }

}

?>