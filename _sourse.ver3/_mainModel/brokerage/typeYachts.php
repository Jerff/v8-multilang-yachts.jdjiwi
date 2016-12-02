<?php

cmfRedirectSeo(cmfGetUrl("/brokerage/"));
cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$typeId = cmfGlobal::get('$param1');
$typeUri = cmfGlobal::get('$typeUri');

if ($mType = cmfCache::getParam('brokerage/type', array($infoId, $typeId))) {
    list($mType, $mYachts) = $mType;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_brokerage_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_brokerage_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
    $mType['all'] = array(
        'name' => word('Все яхты'),
        'url' => cmfGetUrl('/brokerage/')
    );
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'name' => $row['name'],
            'menu' => $row['menu'],
            'uri' => $row['uri'],
            'url' => cmfGetUrl('/brokerage/type/', array($row['uri']))
        );
    }
    $mType[$typeId]['sel'] = true;


    $paramId = cmfConfig::get('site', 'brokerageParam');
    $paramValue = cmfParam::getParamId($paramId);
    $param2Id = cmfConfig::get('site', 'brokerageShowcaseParam');
    $paramValue2 = cmfParam::getParamId($param2Id);
    $res = $sql->placeholder("SELECT y.id, y.`update`, y.uri, y.image_small, y.param, y.price, y.currency FROM ?t y WHERE y.type=? AND y.visible='yes' ORDER BY y.pos, y.name", db_brokerage_yachts, $typeId)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, y.shipyardsName AS sName FROM ?t y WHERE y.id ?@", db_brokerage_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mYachts = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mYachts[$id] = array(
            'shipyards' => $row['sName'],
            'name' => $row['name'],
            'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue, false),
            'param2' => cmfParam::selectParam($row['param'], $param2Id, $paramValue2, true),
            'image' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $row['image_small'], 250, 130,$row['name'], 'b', $row['id'], $row['update']),
            'title' => htmlspecialchars($row['image_title']),
            'price' => cmfPrice::view($row['price'], $row['currency']),
            'url' => cmfGetUrl('/brokerage/yachts/', array($mType[$typeId]['uri'], $row['uri']))
        );
        list(,,, $mYachts[$id]['width']) = getimagesize($mYachts[$id]['image']);
    }

    cmfCache::setParam('brokerage/type', array($infoId, $typeId), array($mType, $mYachts), 'shipyards');
}

$this->assing('mType', $mType);
$this->assing('mYachts', $mYachts);
//list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'brokeragePhone'));
//$this->assing2('phone1', $phone1);
//$this->assing2('phone2', $phone2);
?>