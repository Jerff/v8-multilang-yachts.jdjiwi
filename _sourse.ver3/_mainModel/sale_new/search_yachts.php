<?php


$pageId = (int) cmfPages::getParam(3);
$searchUri = cmfPages::getParam(1);
if (empty($pageId))
    $pageId = true;
$limit = cmfConfig::get('site', 'searchYachtsLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;


cmfLoad('catalog/function');
$post = cmfGlobal::get('$post');
//pre($post);
switch ($post['type']) {
    case 'arenda':
        $mTypeList = $post['aType'];
        $charter = $post['charter'];

        $minAPrice = $post['minAPrice'];
        $maxAPrice = $post['maxAPrice'];
        $minASize = $post['minASize'];
        $maxASize = $post['maxASize'];

        $sql = cmfRegister::getSql();
        $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_arenda_type)
                ->fetchAssocAll('id');
        $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_type_lang, array_keys($res))
                ->fetchAssocAll('id', 'lang');
        $mType = array();
        $mType['all'] = array(
            'name' => word('Все яхты')
        );
        foreach ($res as $id => $row) {
            cmfLang::data($row, get($list, $id));
            $mType[$id] = array(
                'name' => $row['name'],
                'uri' => $row['uri']
            );
        }


        $paramValue1 = cmfParam::getParamId($paramId1 = cmfConfig::get('site', 'arendaParam1'));
        $paramValue2 = cmfParam::getParamId($paramId2 = cmfConfig::get('site', 'arendaParam3'));
        $list = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id FROM ?t WHERE page='/arenda/yachts/' AND section IN(SELECT id FROM ?t WHERE id=? OR path LIKE '%[?s]%') AND type ?@ AND price>=? AND price<=? AND length>=? AND length<=? AND visible='yes' GROUP BY id LIMIT ?i, ?i", db_search, db_arenda, $charter, $charter, $mTypeList, $minAPrice, $maxAPrice, $minASize, $maxASize, $offset, $limit)
                ->fetchRowAll(0, 0);
        $count = $sql->getFoundRows();

        if ($list) {
            $res = $sql->placeholder("SELECT type, `update`, id, uri, image_small, param, priceHour, priceLightDay, priceDay, priceWeek, currency FROM ?t WHERE id ?@ AND visible='yes' ORDER BY FIELD(id, ?s)", db_arenda_yachts, $list, implode(',', $list))
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t WHERE id ?@", db_arenda_yachts_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $mYachts = $mYachtsList = array();
            foreach ($res as $id => $row) {

                cmfLang::data($row, get($list, $id));
                $mYachtsList['all'][$id] = $id;
                $mYachtsList[$row['type']][$id] = $id;
                $mYachts[$id] = array(
                    'name' => $row['name'],
                    'param1' => cmfParam::selectParam($row['param'], $paramId1, $paramValue1),
                    'param2' => cmfParam::selectParam($row['param'], $paramId2, $paramValue2, false),
                    'image' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $row['image_small'], 250, 130, $row['name'], 'n-s-a', $row['id'], $row['update']),
                    'title' => htmlspecialchars($row['image_title']),
                    'priceHour' => cmfPrice::view2($row['priceHour'], $row['currency']),
                    'priceLightDay' => cmfPrice::view2($row['priceLightDay'], $row['currency']),
                    'priceDay' => cmfPrice::view2($row['priceDay'], $row['currency']),
                    'priceWeek' => cmfPrice::view2($row['priceWeek'], $row['currency']),
                    'url' => cmfGetUrl('/arenda/yachts/', array($mType[$row['type']]['uri'], $row['uri']))
                );
                list(,,, $mYachts[$id]['width']) = getimagesize($mYachts[$id]['image']);
            }

            foreach ($mType as $id => $row) {
                if (!isset($mYachtsList[$id])) {
                    unset($mType[$id]);
                }
            }
            reset($mType);
            $mType[key($mType)]['sel'] = true;

            $this->assing('mType', $mType);
            $this->assing('mYachtsList', $mYachtsList);
            $this->assing('mYachts', $mYachts);

            $mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'searchYachtsPages'), create_function('&$page, $k, $v', '
                $page[$k]["url"] = $k==1 ? cmfGetUrl("/sale/new/search/result/", array("' . $searchUri . '")) : cmfGetUrl("/sale/new/search/result/page/", array("' . $searchUri . '", $k));
            '));
            if ($mPageUrl) {
                $this->assing('mPageUrl', $mPageUrl);
            }
        }
        $this->assing2('type', 'arenda');
        break;

    case 'sale':

        $shipyard = $post['shipyard'];
        $mTypeList = $post['sType'];

        $minSPrice = $post['minSPrice'];
        $maxSPrice = $post['maxSPrice'];
        $minSSize = $post['minSSize'];
        $maxSSize = $post['maxSSize'];

        $sql = cmfRegister::getSql();
        $whereShipyard = $whereBrokerage = array();
        if (empty($shipyard)) {
            $res = $sql->placeholder("SELECT id, name FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards)
                    ->fetchRowAll(0, 1);
            $whereShipyard['section'] = array_keys($res);
            $whereBrokerage['shipyardsName'] = $res;
        } elseif (is_numeric($shipyard)) {
            $res = $sql->placeholder("SELECT id, name FROM ?t WHERE id=? AND visible='yes'", db_shipyards, $shipyard)
                    ->fetchRowAll(0, 1);
            $whereShipyard['section'] = array_keys($res);
            $whereBrokerage['shipyardsName'] = $res;
        } else {
            $whereShipyard[] = 1;
            $whereBrokerage['shipyardsName'] = $shipyard;
        }


        $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE id ?@ AND visible='yes' ORDER BY pos", db_shipyards_type, $mTypeList)
                ->fetchAssocAll('id');
        $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
                ->fetchAssocAll('id', 'lang');
        $mType = $shipyardTypeList = array();
        $mType['all'] = array(
            'name' => word('Все яхты')
        );
        foreach ($res as $id => $row) {
            cmfLang::data($row, get($list, $id));
            $mType[$row['name']] = array(
                'name' => $row['name'],
                'menu' => $row['menu']
            );
            $shipyardTypeList[$id] = $row['name'];
        }

        $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE id IN(SELECT type FROM ?t WHERE visible='yes') AND visible='yes' ORDER BY pos", db_brokerage_type, db_brokerage_yachts)
                ->fetchAssocAll('id');
        $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_brokerage_type_lang, array_keys($res))
                ->fetchAssocAll('id', 'lang');
        $brokerageTypeList = $brokerageUri = array();
        foreach ($res as $id => $row) {
            cmfLang::data($row, get($list, $id));
            $mType[$row['name']] = array(
                'name' => $row['name'],
                'menu' => $row['menu']
            );
            $brokerageTypeList[$id] = $row['name'];
            $brokerageUri[$id] = $row['uri'];
        }


        $yachtsList = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, type, page FROM ?t WHERE ((page='/shipyards/yachts/' AND ?w AND type ?@) OR (page='/brokerage/yachts/' AND ?w AND type ?@)) AND price>=? AND price<=? AND length>=? AND length<=? AND visible='yes' GROUP BY id LIMIT ?i, ?i", db_search, $whereShipyard, array_keys($shipyardTypeList), $whereBrokerage, array_keys($brokerageTypeList), $minSPrice, $maxSPrice, $minSSize, $maxSSize, $offset, $limit)
                ->fetchRowAll();
        $count = $sql->getFoundRows();
        if ($yachtsList) {

            $yachtsShipyardsList = $yachtsBrokerageList = array();
            $typeList = array();
            foreach ($yachtsList as $row) {
                list($id, $type, $page) = $row;
                switch ($page) {
                    case '/shipyards/yachts/':
                        $typeList['shipyards']['all'][$id] = $id;
                        $typeList['shipyards'][$shipyardTypeList[$type]][$id] = $id;
                        $yachtsShipyardsList[$id] = $id;
                        break;

                    case '/brokerage/yachts/':
                        $typeList['brokerage']['all'][$id] = $id;
                        $typeList['brokerage'][$brokerageTypeList[$type]][$id] = $id;
                        $yachtsBrokerageList[$id] = $id;
                        break;

                    default:
                        break;
                }
            }


            if ($mBYachts = cmfCache::getParam('brokerage/select/yachts', $yachtsBrokerageList)) {
                list($mBYachts) = $mBYachts;
            } else {
                $paramId = cmfConfig::get('site', 'brokerageParam');
                $paramValue = cmfParam::getParamId($paramId);
                $res = $sql->placeholder("SELECT y.id, y.`update`, y.type, y.uri, y.image_small, y.param, y.price, y.currency FROM ?t y WHERE y.id ?@ AND y.visible='yes' ORDER BY y.pos, y.name", db_brokerage_yachts, $yachtsBrokerageList)
                        ->fetchAssocAll('id');
                $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, y.shipyardsName AS sName FROM ?t y WHERE y.id ?@", db_brokerage_yachts_lang, array_keys($res))
                        ->fetchAssocAll('id', 'lang');
                $mBYachts = array();
                foreach ($res as $id => $row) {
                    cmfLang::data($row, get($list, $id));
                    $mBYachts[$id] = array(
                        'shipyards' => $row['sName'],
                        'name' => $row['name'],
                        'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue, false),
                        'image' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $row['image_small'], 250, 130, $row['name'], 'n-s-b', $row['id'], $row['update']),
                        'title' => htmlspecialchars($row['image_title']),
                        'price' => cmfPrice::view($row['price'], $row['currency']),
                        'url' => cmfGetUrl('/brokerage/yachts/', array($brokerageUri[$row['type']], $row['uri']))
                    );
                    list(,,, $mBYachts[$id]['width']) = getimagesize($mBYachts[$id]['image']);
                }

                cmfCache::setParam('brokerage/select/yachts', $yachtsBrokerageList, array($mBYachts), 'brokerage');
            }


            if ($mSYachts = cmfCache::getParam('shipyards/select/yachts', $yachtsShipyardsList)) {
                list($mSYachts) = $mSYachts;
            } else {
                $paramId = cmfConfig::get('site', 'shipyardsParam');
                $paramValue = cmfParam::getParamId($paramId);
                $res = $sql->placeholder("SELECT y.id, y.`update`, y.type, y.uri, y.image_small, y.param, y.price, y.currency, s.uri AS sUri FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) WHERE s.id=y.shipyards AND y.id ?@ AND y.visible='yes'", db_shipyards_yachts, db_shipyards, $yachtsShipyardsList)
                        ->fetchAssocAll('id');
                $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, (SELECT s.name FROM ?t s WHERE s.id=(SELECT yatch.shipyards FROM ?t yatch WHERE yatch.id=y.id)) AS sName FROM ?t y WHERE y.id ?@", db_shipyards_lang, db_shipyards_yachts, db_shipyards_yachts_lang, array_keys($res))
                        ->fetchAssocAll('id', 'lang');
                $mSYachts = array();
				$i=0;
                foreach ($res as $id => $row) {
                    cmfLang::data($row, get($list, $id));
                    $mSYachts[$id] = array(
                        'shipyards' => $row['sName'],
                        'name' => $row['name'],
                        'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue),
                        'image' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $row['image_small'], 250, 130, $row['name'], 'n-s-s', $row['id'], $row['update']),
                        'title' => htmlspecialchars($row['image_title']),
                        'price' => cmfPrice::view($row['price'], $row['currency']),
                        'url' => cmfGetUrl('/shipyards/yachts/', array($row['sUri'], $row['uri']))
                    );
                    list(,,, $mSYachts[$id]['width']) = getimagesize($mSYachts[$id]['image']);
                }

                cmfCache::setParam('shipyards/select/yachts', $yachtsBrokerageList, array($mSYachts), 'shipyards');
            }

            foreach ($mType as $id => $row) {
                if (!isset($typeList['shipyards'][$id]) and !isset($typeList['brokerage'][$id])) {
                    unset($mType[$id]);
                }
            }
            reset($mType);
            $mType[key($mType)]['sel'] = true;

            $this->assing('mType', $mType);
            $this->assing('typeList', $typeList);
            $this->assing('mBYachts', $mBYachts);
            $this->assing('mSYachts', $mSYachts);

            $mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'searchYachtsPages'), create_function('&$page, $k, $v', '
                $page[$k]["url"] = $k==1 ? cmfGetUrl("/sale/new/search/result/", array("' . $searchUri . '")) : cmfGetUrl("/sale/new/search/result/page/", array("' . $searchUri . '", $k));
            '));
            if ($mPageUrl) {
                $this->assing('mPageUrl', $mPageUrl);
            }
        }

        $this->assing2('type', 'sale');
        break;

    default:
        break;
}
?>