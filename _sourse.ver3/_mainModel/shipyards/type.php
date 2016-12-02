<?php

cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$typeId = cmfGlobal::get('$param1');
if (!$infoId or !$typeId)
    return 404;

if ($info = cmfCache::getParam('shipyards/type', array($infoId, $typeId))) {
    list($info, $mMenu, $request, $headerId, $mPath, $type, $sType) = $info;
} else {
    if (!$info = cmfContent::info($infoId))
        return 404;
    list($info, $request, $headerId, $mPath) = $info;
    $mMenu = cmfContent::initSubMenu($info, $infoId);

    $sql = cmfRegister::getSql();
    $type = $sql->placeholder("SELECT id FROM ?t WHERE id=? AND visible='yes'", db_shipyards_type, $typeId)
            ->fetchAssoc();
    if (!$type)
        return 404;
    $list = $sql->placeholder("SELECT lang, header, notice, content, title, keywords, description FROM ?t WHERE id=?", db_shipyards_type_lang, $typeId)
            ->fetchAssocAll('lang');
    cmfLang::data($type, $list);
    $mPath[] = $type['header'];


    $sType = $this->run('/shipyards/type/list/');


//    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
//            ->fetchAssocAll('id');
//    $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
//            ->fetchAssocAll('id', 'lang');
//    $mType = array();
//    $mType['all'] = array(
//        'name' => word('Все верфи'),
//        'url' => cmfGetUrl('/shipyards/')
//    );
//    foreach ($res as $id => $row) {
//        cmfLang::data($row, get($list, $id));
//        $mType[$id] = array(
//            'name' => $row['name'],
//            'menu' => $row['menu'],
//            'url' => cmfGetUrl('/shipyards/type/', array($row['uri']))
//        );
//    }
//    $mType[$typeId]['sel'] = true;
//
//
//    $res = $sql->placeholder("SELECT id, uri, image FROM ?t WHERE id IN(SELECT shipyards FROM ?t WHERE `type`=? AND visible='yes') AND visible='yes' ORDER BY `main`, pos", db_shipyards, db_shipyards_yachts, $typeId)
//            ->fetchAssocAll('id');
//    $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t WHERE id ?@", db_shipyards_lang, array_keys($res))
//            ->fetchAssocAll('id', 'lang');
//    $mShipyards = array();
//    foreach ($res as $id => $row) {
//        cmfLang::data($row, get($list, $id));
//        $mShipyards[$id] = array(
//            'title' => htmlspecialchars($row['image_title']),
//            'name' => $row['name'],
//            'image' => cmfImage::preview(cmfBaseImg . cmfPathShipyards . $row['image'], 99, 45),
//            'url' => cmfGetUrl('/shipyards/one/', array($row['uri']))
//        );
//        list(,,, $mShipyards[$id]['width']) = getimagesize($mShipyards[$id]['image']);
//    }

    cmfCache::setParam('shipyards/type', array($infoId, $typeId), array($info, $mMenu, $request, $headerId, $mPath, $type, $sType), 'shipyards');
}
foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu);

cmfMenu::setH1($type['header']);

cmfGlobal::set('header-content', $sType);

$this->assing('type', $type);
$this->assing('mType', $mType);
$this->assing('mShipyards', $mShipyards);
cmfGlobal::set('body', 'rent');
$this->setTeplates('main.yachts.php');

cmfSeo::set('title', $type['title']);
cmfSeo::set('keywords', $type['keywords']);
cmfSeo::set('description', $type['description']);
?>