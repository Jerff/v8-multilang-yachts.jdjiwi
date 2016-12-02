<?php

cmfLoad('catalog/function');
if ($yachtsList = cmfCache::get('brokerage/menu')) {
    list($yachtsList, $typeList, $networkR) = $yachtsList;
} else {

    $sql = cmfRegister::getSql();
    $yachtsList = $sql->placeholder("SELECT page, type, yachts FROM ?t WHERE visible='yes' ORDER BY id", db_showcase)
            ->fetchRowAll();
    $typeList = array();
    if ($yachtsList) {
        foreach ($yachtsList as $row) {
            list($page, $type, $yachts) = $row;
            switch ($page) {
                case 'arenda':
                    $typeList['arenda'][$yachts] = $yachts;
                    break;

                case 'sale':
                    $typeList['sale'][$yachts] = $yachts;
                    break;

                case 'brokerage':
                    $typeList['brokerage'][$yachts] = $yachts;
                    break;

                default:
                    break;
            }
        }


        if (!empty($typeList['arenda'])) {
            $mType = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_arenda_type)
                    ->fetchRowAll(0, 1);

            $paramValue1 = cmfParam::getParamId($paramId1 = cmfConfig::get('site', 'arendaParam1'));
            $paramValue2 = cmfParam::getParamId($paramId2 = cmfConfig::get('site', 'arendaParam3'));
            $res = $sql->placeholder("SELECT type, `update`, id, uri, image_small, param, priceHour, priceLightDay, priceDay, priceWeek, currency FROM ?t WHERE id ?@ AND visible='yes'", db_arenda_yachts, $typeList['arenda'])
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t WHERE id ?@", db_arenda_yachts_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                $typeList['arenda'][$id] = array(
                    'name' => $row['name'],
                    'param1' => cmfParam::selectParam($row['param'], $paramId1, $paramValue1),
                    'param2' => cmfParam::selectParam($row['param'], $paramId2, $paramValue2, false),
                    'image' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $row['image_small'], 250, 130, $row['name'], 'mr-a', $row['id'], $row['update']),
                    'title' => htmlspecialchars($row['image_title']),
                    'priceHour' => cmfPrice::view2($row['priceHour'], $row['currency']),
                    'priceLightDay' => cmfPrice::view2($row['priceLightDay'], $row['currency']),
                    'priceDay' => cmfPrice::view2($row['priceDay'], $row['currency']),
                    'priceWeek' => cmfPrice::view2($row['priceWeek'], $row['currency']),
                    'url' => cmfGetUrl('/arenda/yachts/', array($mType[$row['type']], $row['uri']))
                );
                list(,,, $typeList['arenda'][$id]['width']) = getimagesize($typeList['arenda'][$id]['image']);
            }
        }


        if (!empty($typeList['sale'])) {
            $paramId = cmfConfig::get('site', 'shipyardsParam');
            $paramValue = cmfParam::getParamId($paramId);
            $res = $sql->placeholder("SELECT y.id, `update`, y.type, y.uri, y.image_small, y.param, y.price, y.currency, s.uri AS sUri FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) WHERE s.id=y.shipyards AND y.id ?@ AND y.visible='yes'", db_shipyards_yachts, db_shipyards, $typeList['sale'])
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, (SELECT s.name FROM ?t s WHERE s.id=(SELECT yatch.shipyards FROM ?t yatch WHERE yatch.id=y.id)) AS sName FROM ?t y WHERE y.id ?@", db_shipyards_lang, db_shipyards_yachts, db_shipyards_yachts_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                $typeList['sale'][$id] = array(
                    'shipyards' => $row['sName'],
                    'name' => $row['name'],
                    'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue),
                    'image' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $row['image_small'], 250, 130, $row['name'], 'mr-s', $row['id'], $row['update']),
                    'title' => htmlspecialchars($row['image_title']),
                    'price' => cmfPrice::view($row['price'], $row['currency']),
                    'url' => cmfGetUrl('/shipyards/yachts/', array($row['sUri'], $row['uri']))
                );
                list(,,, $typeList['sale'][$id]['width']) = getimagesize($typeList['sale'][$id]['image']);
            }
        }

        if (!empty($typeList['brokerage'])) {
            $mType = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_brokerage_type)
                    ->fetchRowAll(0, 1);

            $paramId = cmfConfig::get('site', 'brokerageParam');
            $paramValue = cmfParam::getParamId($paramId);
            $param2Id = cmfConfig::get('site', 'brokerageShowcaseParam');
            $paramValue2 = cmfParam::getParamId($param2Id);
            $res = $sql->placeholder("SELECT y.id, `update`, y.type, y.uri, y.image_small, y.param, y.price, y.currency FROM ?t y WHERE y.id ?@ AND y.visible='yes'", db_brokerage_yachts, $typeList['brokerage'])
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, y.shipyardsName AS sName FROM ?t y WHERE y.id ?@", db_brokerage_yachts_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $mBYachts = array();
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                $typeList['brokerage'][$id] = array(
                    'shipyards' => $row['sName'],
                    'name' => $row['name'],
                    'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue, false),
                    'param2' => cmfParam::selectParam($row['param'], $param2Id, $paramValue2, true),
                    'image' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $row['image_small'], 250, 130, $row['name'], 'mr-b', $row['id'], $row['update']),
                    'title' => htmlspecialchars($row['image_title']),
                    'price' => cmfPrice::view($row['price'], $row['currency']),
                    'url' => cmfGetUrl('/brokerage/yachts/', array($mType[$row['type']], $row['uri']))
                );
                list(,,, $typeList['brokerage'][$id]['width']) = getimagesize($typeList['brokerage'][$id]['image']);
            }
        }
    }
    $networkR = cmfConfig::get('site', 'blogNetwork');


    cmfCache::set('brokerage/menu', array($yachtsList, $typeList, $networkR), 'shipyards');
}

$this->assing2('boardAdd', cmfGetUrl('/board/add/'));
$this->assing2('mMenu', cmfMenu::getRMenu());
$this->assing2('mBMenu', cmfMenu::getBMenu());
$this->assing('yachtsList', $yachtsList);
$this->assing('typeList', $typeList);
$this->assing('networkR', $networkR);
?>