<?php

cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');

if ($mType = cmfCache::getParam('new/sale', array($infoId))) {
    list($mType, $mYachts) = $mType;
} else {
    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, content, menu FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
        $mType['all'] = array(
        'name' => word('Все яхты'),
        'content' => '',
        'sel' => true,
        'url' => cmfGetUrl('/sale/new/')
    );
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'name' => $row['name'],
            'content' => $row['content'],
            'menu' => $row['menu'],
            'uri' => $row['uri'],
            'url' => cmfGetUrl('/sale/new/type/', array($row['uri']))
        );
    }
    $mType[key($mType)]['sel'] = true;


    $paramId = cmfConfig::get('site', 'shipyardsParam');
    $paramValue = cmfParam::getParamId($paramId);
    $res = $sql->placeholder("SELECT y.type, y.`update`, y.id, y.uri, y.image_small, y.param, y.price, y.currency, s.uri AS sUri FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) WHERE s.id=y.shipyards AND y.type ?@ AND y.catalog='yes' AND y.visible='yes' ORDER BY y.pos, y.name", db_shipyards_yachts, db_shipyards, array_keys($mType))
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, (SELECT s.name FROM ?t s WHERE s.id=(SELECT yatch.shipyards FROM ?t yatch WHERE yatch.id=y.id)) AS sName FROM ?t y WHERE y.id ?@", db_shipyards_lang, db_shipyards_yachts, db_shipyards_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mYachts = array();
	$i = 1;
    foreach ($res as $id=>$row) {
        cmfLang::data($row, get($list, $id));

        $data = array(
            'shipyards' => $row['sName'],
            'name' => $row['name'],
            'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue),
            'image' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $row['image_small'], 250, 130,$row['name'], 'n', $row['id'], $row['update']),
            'title' => htmlspecialchars($row['image_title']),
            'price' => cmfPrice::view($row['price'], $row['currency']),
            'url' => cmfGetUrl('/shipyards/yachts/', array($row['sUri'], $row['uri']))
        );
        list(,,, $data['width']) = getimagesize($data['image']);
        $mYachts['all'][$id] = $data;
        $mYachts[$row['type']][$id] = $data;
    }
//    foreach ($mYachts as $k => $list) {
//        $mYachts[$k] = array_splice($list, 0, 3);
//    }

    cmfCache::setParam('new/sale', array($infoId), array($mType, $mYachts), 'new/sale');
}

$this->assing('mType', $mType);
$this->assing('mYachts', $mYachts);


cmfGlobal::set('body', 'rent');
$this->setTeplates('main.yachts.php');

list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'shipyardsPhone'));
$this->assing2('phone1', $phone1);
$this->assing2('phone2', $phone2);
?>
