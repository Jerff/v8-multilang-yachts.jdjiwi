<?php

cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$typeId = cmfGlobal::get('$param1');
if (!$infoId or !$typeId)
    return 404;

if ($mType = cmfCache::getParam('shipyards/type/list', array($infoId, $typeId))) {
    list($mType, $mShipyards) = $mType;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
    $mType['all'] = array(
        'name' => word('Все верфи'),
        'url' => cmfGetUrl('/shipyards/')
    );
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'name' => $row['name'],
            'menu' => $row['menu'],
            'url' => cmfGetUrl('/shipyards/type/', array($row['uri']))
        );
    }
    $mType[$typeId]['sel'] = true;


    $res = $sql->placeholder("SELECT id, `update`, uri, image FROM ?t WHERE id IN(SELECT shipyards FROM ?t WHERE `type`=? AND visible='yes') AND visible='yes' ORDER BY `main`, pos", db_shipyards, db_shipyards_yachts, $typeId)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t WHERE id ?@", db_shipyards_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mShipyards = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mShipyards[$id] = array(
            'title' => htmlspecialchars($row['image_title']),
            'name' => $row['name'],
            'image' => cmfImage::preview(cmfBaseImg . cmfPathShipyards . $row['image'], 99, 45,$row['name'], 's-t', $row['id'], $row['update']),
            'url' => cmfGetUrl('/shipyards/one/', array($row['uri']))
        );
        list(,,, $mShipyards[$id]['width']) = getimagesize($mShipyards[$id]['image']);
    }

    cmfCache::setParam('shipyards/type/list', array($infoId, $typeId), array($mType, $mShipyards), 'shipyards');
}

$this->assing('mType', $mType);
$this->assing('mShipyards', $mShipyards);
?>