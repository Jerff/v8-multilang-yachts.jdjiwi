<?php

cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$typeId = cmfGlobal::get('$param1');

if ($info = cmfCache::getParam('new/sale', array($infoId, $typeId))) {
    list($info, $request, $headerId, $mPath, $type, $mType, $mYachts) = $info;
} else {

    if (!$info = cmfContent::info($infoId))
        return 404;
    list($info, $request, $headerId, $mPath) = $info;


    $sql = cmfRegister::getSql();
    $type = $sql->placeholder("SELECT id, uri FROM ?t WHERE id=? AND visible='yes'", db_shipyards_type, $typeId)
            ->fetchAssoc();
    if (!$type)
        return 404;
    $list = $sql->placeholder("SELECT lang, name, menu, content, title, keywords, description FROM ?t WHERE id=?", db_shipyards_type_lang, $typeId)
            ->fetchAssocAll('lang');
    cmfLang::data($type, $list);
    $mPath[] = $type['name'];
//    $mPath[cmfGetUri('/sale/new/type/', array($type['uri']))] = $type['name'];


    $res = $sql->placeholder("SELECT id, name, menu, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'name' => $row['name'],
            'menu' => $row['menu'],
            'uri' => $row['uri'],
            'url' => cmfGetUrl('/sale/new/type/', array($row['uri']))
        );
    }
    $mType[$typeId]['sel'] = true;


    $paramId = cmfConfig::get('site', 'shipyardsParam');
    $paramValue = cmfParam::getParamId($paramId);
    $res = $sql->placeholder("SELECT y.type, y.`update`, y.id, y.uri, y.image_small, y.param, y.price, y.currency, s.uri AS sUri FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) WHERE s.id=y.shipyards AND y.type=? AND y.catalog='yes' AND y.visible='yes' ORDER BY y.pos, y.name", db_shipyards_yachts, db_shipyards, $typeId)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT y.id, y.lang, y.name, y.image_title, (SELECT s.name FROM ?t s WHERE s.id=(SELECT yatch.shipyards FROM ?t yatch WHERE yatch.id=y.id)) AS sName FROM ?t y WHERE y.id ?@", db_shipyards_lang, db_shipyards_yachts, db_shipyards_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mYachts = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mYachts[$id] = array(
            'shipyards' => $row['sName'],
            'name' => $row['name'],
            'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue),
            'image' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $row['image_small'], 250, 130,$row['name'], 'n-t', $row['id'], $row['update']),
            'title' => htmlspecialchars($row['image_title']),
            'price' => cmfPrice::view($row['price'], $row['currency']),
            'url' => cmfGetUrl('/shipyards/yachts/', array($row['sUri'], $row['uri']))
        );
        list(,,, $mYachts[$id]['width']) = getimagesize($mYachts[$id]['image']);
    }

    cmfCache::setParam('new/sale', array($infoId, $typeId), array($info, $request, $headerId, $mPath, $type, $mType, $mYachts), 'photo');
}
cmfGlobal::set('body', 'rent');


foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setH1($type['name']);
cmfSeo::set('title', $type['title']);
cmfSeo::set('keywords', $type['keywords']);
cmfSeo::set('description', $type['description']);
cmfGlobal::set('body', 'rent');


cmfGlobal::set('header-content', $this->run('/sale/new/type/yachts/'));


$this->assing('type', $type);
$this->assing('mType', $mType);
$this->assing('mYachts', $mYachts);
?>