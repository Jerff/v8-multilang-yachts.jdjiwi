<?php

class cmfSelectYachts {

    static public function selectArenda() {
        $post = cmfGlobal::get('$post');
        $param = array(
            $aType = get($post, 'aType'),
//            $minAPrice = get($post, 'minAPrice'),
//            $maxAPrice = get($post, 'maxAPrice'),
//            $minASize = get($post, 'minASize'),
//            $maxASize = get($post, 'maxASize'),
            $charter = get($post, 'charter')
        );
        if ($mType = cmfCache::getParam('cmfSelectYachts::selectArenda', $param)) {
            list($mType, $mArenda, $yachts) = $mType;
        } else {

            $sql = cmfRegister::getSql();
            $mArenda = $mList = array();
            foreach (array(3, 0) as $parentId) {
                $res = cmfRegister::getSql()->placeholder("SELECT id FROM ?t WHERE parent=? AND isVisible='yes' ORDER BY level, pos", db_arenda, $parentId)
                        ->fetchAssocAll('id');
                $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name, name_search FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res))
                        ->fetchAssocAll('id', 'lang');
                foreach ($res as $id => $row) {
                    cmfLang::data($row, get($list, $id));
                    $mArenda[$id] = empty($row['name_search']) ? $row['name'] : $row['name_search'];
                    if((empty($mList) and empty($charter)) or $charter==$id) {
                        $mList[] = $sql->getQuery("(id=? OR path LIKE '%[?s]%')", $id, $id);
                    }
                }
            }
            $mList = cmfRegister::getSql()->placeholder("SELECT id FROM ?t WHERE " . implode(' OR ', $mList), db_arenda)
                    ->fetchRowAll(0, 0);


            $res = $sql->placeholder("SELECT id FROM ?t WHERE id IN(SELECT type FROM ?t WHERE id IN(SELECT yachts FROM ?t WHERE arenda ?@) AND visible='yes') AND visible='yes' ORDER BY pos", db_arenda_type, db_arenda_yachts, db_arenda_list, $mList)
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_type_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $mType = $mTypeList = array();
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                if(empty($aType) or isset($aType[$id])) {
                    $mTypeList[$id] = $id;
                }
                $mType[$id] = $row['name'];
            }

            $yachts = $sql->placeholder("SELECT min(price), max(price), min(length), max(length) FROM ?t WHERE page='/arenda/yachts/' AND section ?@ AND type ?@ AND visible='yes'", db_search, $mList, $mTypeList)
                    ->fetchAssoc();
            $yachts = array_map('round', $yachts);

            cmfCache::setParam('cmfSelectYachts::selectArenda', $param, array($mType, $mArenda, $yachts), 'photo');
        }
        return array($mType, $mArenda, $yachts);
    }

    static public function selectSale() {
        $post = cmfGlobal::get('$post');
        $param = array(
            $sType = get($post, 'sType'),
//            $minSPrice = get($post, 'minSPrice'),
//            $maxSPrice = get($post, 'maxSPrice'),
//            $minSSize = get($post, 'minSSize'),
//            $maxSSize = get($post, 'maxSSize'),
            $shipyard = get($post, 'shipyard')
        );

        if ($mType = cmfCache::getParam('cmfSelectYachts::selectSale', $param)) {
            list($mType, $mShipyard, $yachts) = $mType;
        } else {

            $sql = cmfRegister::getSql();
            $res = $sql->placeholder("SELECT id FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards)
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_shipyards_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $mShipyard = $keyShipyard = array();
            $mShipyard[0] = word('Все верфи');
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                if(empty($shipyard) or $shipyard==$id or $shipyard==$row['name']) {
                    $keyShipyard[$row['name']] = $id;
                }
                $mShipyard[$id] = $row['name'];
            }

            $res = $sql->placeholder("SELECT id FROM ?t WHERE type IN(SELECT id FROM ?t WHERE visible='yes') AND visible='yes' ORDER BY pos", db_brokerage_yachts, db_brokerage_type)
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, shipyardsName FROM ?t WHERE id ?@", db_brokerage_yachts_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $keyBrokerage = $keyBrokerageList = array();
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                if (!isset($keyShipyard[$row['shipyardsName']])) {
                    $mShipyard[$row['shipyardsName']] = $row['shipyardsName'];
                }
                if(empty($shipyard) or $shipyard==$row['shipyardsName'] or isset($keyShipyard[$row['shipyardsName']])) {
                    $keyBrokerage[$row['shipyardsName']] = $row['shipyardsName'];
                }
            }
//            pre($keyShipyard, $keyBrokerage);


            $res = $sql->placeholder("SELECT id FROM ?t WHERE id IN(SELECT type FROM ?t WHERE shipyards ?@ AND visible='yes') AND visible='yes' ORDER BY pos", db_shipyards_type, db_shipyards_yachts, array_keys($mShipyard))
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $mType = $keyShipyardType = $keyShipyardList = array();
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                if(empty($sType) or isset($sType[$id])) {
                    $keyShipyardType[$row['name']] = $id;
                }
                $mType[$id] = $row['name'];
            }


            $res = $sql->placeholder("SELECT id FROM ?t WHERE id IN(SELECT y.type FROM ?t y LEFT JOIN ?t l ON(y.id=l.id) WHERE l.shipyardsName ?@ AND y.visible='yes') AND visible='yes' ORDER BY pos", db_brokerage_type, db_brokerage_yachts, db_brokerage_yachts_lang, $mShipyard)
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_brokerage_type_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $keyBrokerageType = array();
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                if (!isset($keyShipyardType[$row['name']])) {
                    $mType[$row['name']] = $row['name'];
                }
                if(empty($sType) or isset($sType[$row['name']]) or isset($keyShipyardType[$row['name']])) {
                    $keyBrokerageType[$row['name']] = $id;
                }
            }

            $yachts = $sql->placeholder("SELECT min(price), max(price), min(length), max(length) FROM ?t WHERE (page='/shipyards/yachts/' AND section ?@ AND type ?@) OR (page='/brokerage/yachts/' AND shipyardsName ?@ AND type ?@) AND visible='yes'", db_search, $keyShipyard, $keyShipyardType, $keyBrokerage, $keyBrokerageType)
//            $yachts = $sql->placeholder("SELECT min(price), max(price), min(length), max(length) FROM ?t WHERE (page='/brokerage/yachts/' AND type ?@) AND visible='yes'", db_search, $keyBrokerageType)
                    ->fetchAssoc();
            $yachts = array_map('round', $yachts);

            cmfCache::setParam('cmfSelectYachts::selectSale', $param, array($mType, $mShipyard, $yachts), 'photo');
        }
        return array($mType, $mShipyard, $yachts);
    }

}

?>