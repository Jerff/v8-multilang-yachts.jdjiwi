<?php
cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');

if ($mType = cmfCache::getParam('brokerage', array($infoId))) {
    list($mType, $mYachts) = $mType;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_brokerage_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, content, menu FROM ?t WHERE id ?@", db_brokerage_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
    $mType['all'] = array(
        'name' => word('Все яхты'),
        'content' => '',
        'sel' => true,
        'url' => cmfGetUrl('/brokerage/')
    );
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'name' => $row['name'],
            'menu' => $row['menu'],
            'content' => $row['content'],
            'uri' => $row['uri'],
            'url' => cmfGetUrl('/brokerage/type/', array($row['uri']))
        );
    }


    $paramId = cmfConfig::get('site', 'brokerageParam');
    $paramValue = cmfParam::getParamId($paramId);
    $param2Id = cmfConfig::get('site', 'brokerageShowcaseParam');
    $paramValue2 = cmfParam::getParamId($param2Id);
    $res = $sql->placeholder("SELECT y.`type`, y.`update`, y.id, y.uri, y.image_small, y.image_main, y.param, y.price, y.currency FROM ?t y WHERE y.type ?@ AND y.visible='yes' ORDER BY y.pos, y.name", db_brokerage_yachts, array_keys($mType))
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, y.price, y.shipyardsName AS sName FROM ?t y WHERE y.id ?@", db_brokerage_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mYachts = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $data = array(
            'shipyards' => $row['sName'],
            'name' => $row['name'],
            'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue, false),
            'param2' => cmfParam::selectParam($row['param'], $param2Id, $paramValue2, true),
            'image' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $row['image_small'], 250, 130,$row['name'], 'b', $row['id'], $row['update']),
            'title' => htmlspecialchars($row['image_title']),
            'price' => cmfPrice::view($row['price'], $row['currency']),
            'url' => cmfGetUrl('/brokerage/yachts/', array($mType[$row['type']]['uri'], $row['uri']))
        );
        list(,,, $data['width']) = getimagesize($data['image']);
        $mYachts['all'][$id] = $data;
        $mYachts[$row['type']][$id] = $data;
    }
    cmfCache::setParam('brokerage', array($infoId), array($mType, $mYachts), 'shipyards');
}

$this->assing('mType', $mType);
$this->assing('mYachts', $mYachts);


cmfGlobal::set('body', 'rent');
$this->setTeplates('main.yachts.php');
?>