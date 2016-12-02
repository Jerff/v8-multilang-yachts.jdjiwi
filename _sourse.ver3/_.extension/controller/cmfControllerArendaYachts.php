<?php

class cmfControllerArendaYachts extends cmfControllerDriver {

    const name = '/arenda/yachts/';

    static public function update($id = null) {
        self::updateUri(null, $id);
        self::updateSearch($id);

        $arenda = cmfRegister::getSql()->placeholder("SELECT arenda FROM ?t WHERE yachts ?@", db_arenda_list, (array)$id)
                ->fetchRowAll(0, 0);
        cmfControllerArenda::updateCountYachts($arenda);
    }

    static public function delete($id) {
        cmfContentUrl::delete(self::name, array('param2' => $id));
        cmfSearchData::delete(self::name, $id);
        cmfControllerArenda::updateCountYachts();
    }

    static public function updateSearch($where = null) {
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
        $mParam = cmfModulLoad('param_edit_db')->getFeildOfId('value', lengthId);
        $mParam = cmfString::unserialize($mParam);
        $fields1 = array('id', 'visible', 'type', 'uri', 'param', 'priceHour', 'currency');
        $fields2 = array('lang', 'name', 'notice');
        $res = $sql->placeholder("SELECT ?fields:y, ?fields:lang, t.uri AS tUri, t.visible AS tVisible, a.visible AS aVisible, l.arenda FROM ?t y LEFT JOIN ?t lang ON(y.id=lang.id) LEFT JOIN ?t t ON(y.type=t.id) LEFT JOIN ?t l ON(y.id=l.yachts) LEFT JOIN ?t a ON(l.arenda=a.id) WHERE /*y.id=17 AND*/ ?w:y", $fields1, $fields2, db_arenda_yachts, db_arenda_yachts_lang, db_arenda_type, db_arenda_list, db_arenda, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {

            $send = array();
            $send['visible'] = ($row['visible'] == 'yes' and $row['tVisible'] == 'yes' and $row['aVisible'] == 'yes') ? 'yes' : 'no';
            $send['name'] = $row['name'];
            $send['price'] = cmfPrice::priceToUsd($row['priceHour'], $row['currency']);
            $send['section'] = (int) $row['arenda'];
            $send['type'] = $row['type'];
            $send['id'] = $row['id'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl(self::name, array($row['tUri'], $row['uri']));
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
//        cmfRegister::getSql()->placeholder("
//                REPLACE ?t SELECT ?, y.id, 0, 0, CONCAT(t.uri, '/', y.uri) FROM ?t t LEFT JOIN ?t y ON(t.id=y.type) WHERE y.id IN(SELECT l.yachts FROM ?t l WHERE l.arenda IN(SELECT a.id FROM ?t a WHERE ?w:a )) AND ?w:t AND ?w:y",
//                db_content_url, self::name, db_arenda_type, db_arenda_yachts, db_arenda_list, db_arenda, $where1, $where2, $where3);
//        cmfRegister::getSql()->placeholder("
//                REPLACE ?t SELECT ?, y.id, 0, 0, CONCAT(t.uri, '/', y.uri) FROM ?t t LEFT JOIN ?t y ON(t.id=y.type) WHERE y.id IN(SELECT l.yachts FROM ?t l WHERE l.arenda IN(SELECT a.id FROM ?t a WHERE ?w:a )) AND ?w:t AND ?w:y", db_content_url, self::name, db_arenda_type, db_arenda_yachts, db_arenda_list, db_arenda, $where1, $where2, $where3);

        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, a.id, y.id, CONCAT(t.uri, '/', y.uri)
                FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) LEFT JOIN ?t a ON(1=1) LEFT JOIN ?t i ON(i.pages=IF(a.id=? OR a.path LIKE '%[?s]%', 'arenda-ukraine', 'charter'))
                WHERE y.id IN(SELECT l.yachts FROM ?t l WHERE l.arenda=a.id) AND ?w:t AND ?w:y
                GROUP BY y.id ORDER BY a.pos, a.name"
                , db_content_url, self::name, db_arenda_yachts, db_arenda_type, db_arenda, db_menu_info, arendaMenu, arendaMenu, db_arenda_list, $where1, $where2);
    }

}

?>